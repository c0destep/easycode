<?php
/**
 * Upload
 *
 * @author      Josh Lockhart <info@joshlockhart.com>
 * @copyright   2012 Josh Lockhart
 * @link        http://www.joshlockhart.com
 * @version     2.0.0
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Upload;

use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use ReturnTypeWillChange;
use RuntimeException;

/**
 * File
 *
 * This class provides the implementation for an uploaded file. It exposes
 * common attributes for the uploaded file (e.g. name, extension, media type)
 * and allows you to attach validations to the file that must pass for the
 * upload to succeed.
 *
 * @author Josh Lockhart <info@joshlockhart.com>
 * @since 1.0.0
 * @package Upload
 */
class File implements ArrayAccess, IteratorAggregate, Countable
{
    /**
     * Upload error code messages
     * @var array
     */
    protected static array $errorCodeMessages = array(
        1 => 'File size exceeds allowed size',
        2 => 'File size exceeds allowed size',
        3 => 'Error while uploading',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk',
        8 => 'A PHP extension stopped the file upload'
    );

    /**
     * Storage delegate
     * @var StorageInterface
     */
    protected StorageInterface $storage;

    /**
     * File information
     * @var array[\Upload\FileInfoInterface]
     */
    protected array $objects = array();

    /**
     * Validations
     * @var array[\Upload\ValidationInterface]
     */
    protected array $validations = array();

    /**
     * Validation errors
     * @var array[String]
     */
    protected array $errors = array();

    /**
     * Before validation callback
     * @var callable
     */
    protected $beforeValidationCallback;

    /**
     * After validation callback
     * @var callable
     */
    protected $afterValidationCallback;

    /**
     * Before upload callback
     * @var callable
     */
    protected $beforeUploadCallback;

    /**
     * After upload callback
     * @var callable
     */
    protected $afterUploadCallback;

    /**
     * Constructor
     *
     * @param string $key The $_FILES[] key
     * @param StorageInterface $storage The upload delegate instance
     * @throws RuntimeException                  If file uploads are disabled in the php.ini file
     * @throws InvalidArgumentException          If $_FILES[] does not contain key
     */
    public function __construct(string $key, StorageInterface $storage)
    {
        // Check if file uploads are allowed
        if (!ini_get('file_uploads')) {
            throw new RuntimeException('File uploads are disabled in your PHP.ini file');
        }

        // Check if key exists
        if (isset($_FILES[$key]) === false) {
            throw new InvalidArgumentException("Cannot find uploaded file(s) identified by key: $key");
        }

        // Collect file info
        if (is_array($_FILES[$key]['tmp_name']) === true) {
            foreach ($_FILES[$key]['tmp_name'] as $index => $tmpName) {
                if ($_FILES[$key]['error'][$index] !== UPLOAD_ERR_OK) {
                    $this->errors[] = sprintf(
                        '%s: %s',
                        $_FILES[$key]['name'][$index],
                        static::$errorCodeMessages[$_FILES[$key]['error'][$index]]
                    );
                    continue;
                }

                $this->objects[] = FileInfo::createFromFactory(
                    $_FILES[$key]['tmp_name'][$index],
                    $_FILES[$key]['name'][$index]
                );
            }
        } else {
            if ($_FILES[$key]['error'] !== UPLOAD_ERR_OK) {
                $this->errors[] = sprintf(
                    '%s: %s',
                    $_FILES[$key]['name'],
                    static::$errorCodeMessages[$_FILES[$key]['error']]
                );
            }

            $this->objects[] = FileInfo::createFromFactory(
                $_FILES[$key]['tmp_name'],
                $_FILES[$key]['name']
            );
        }

        $this->storage = $storage;
    }

    /********************************************************************************
     * Callbacks
     *******************************************************************************/

    /**
     * Convert human-readable file size (e.g. "10K" or "3M") into bytes
     *
     * @param string $input
     * @return float|int
     */
    public function humanReadableToBytes(string $input): float|int
    {
        $number = (int)$input;
        $units = array(
            'b' => 1,
            'k' => 1024,
            'm' => 1048576,
            'g' => 1073741824
        );
        $unit = strtolower(substr($input, -1));
        if (isset($units[$unit])) {
            $number = $number * $units[$unit];
        }

        return $number;
    }

    public function setName(string $name): void
    {
        self::setName($name);
    }

    public function getName(): void
    {
        self::getName();
    }

    /**
     * Set `beforeValidation` callable
     *
     * @param callable $callable Should accept one `\Upload\FileInfoInterface` argument
     * @return File Self
     * @throws InvalidArgumentException If argument is not a Closure or invokable object
     */
    public function beforeValidate(callable $callable): static
    {
        if (is_object($callable) === false || method_exists($callable, '__invoke') === false) {
            throw new InvalidArgumentException('Callback is not a Closure or invokable object.');
        }
        $this->beforeValidation = $callable;

        return $this;
    }

    /**
     * Set `afterValidation` callable
     *
     * @param callable $callable Should accept one `\Upload\FileInfoInterface` argument
     * @return File Self
     * @throws InvalidArgumentException If argument is not a Closure or invokable object
     */
    public function afterValidate(callable $callable): static
    {
        if (is_object($callable) === false || method_exists($callable, '__invoke') === false) {
            throw new InvalidArgumentException('Callback is not a Closure or invokable object.');
        }
        $this->afterValidation = $callable;

        return $this;
    }

    /**
     * Set `beforeUpload` callable
     *
     * @param callable $callable Should accept one `\Upload\FileInfoInterface` argument
     * @return File Self
     * @throws InvalidArgumentException If argument is not a Closure or invokable object
     */
    public function beforeUpload(callable $callable): static
    {
        if (is_object($callable) === false || method_exists($callable, '__invoke') === false) {
            throw new InvalidArgumentException('Callback is not a Closure or invokable object.');
        }
        $this->beforeUpload = $callable;

        return $this;
    }

    /**
     * Set `afterUpload` callable
     *
     * @param callable $callable Should accept one `\Upload\FileInfoInterface` argument
     * @return File Self
     * @throws InvalidArgumentException If argument is not a Closure or invokable object
     */
    public function afterUpload(callable $callable): static
    {
        if (is_object($callable) === false || method_exists($callable, '__invoke') === false) {
            throw new InvalidArgumentException('Callback is not a Closure or invokable object.');
        }
        $this->afterUpload = $callable;

        return $this;
    }

    /**
     * Add file validations
     *
     * @param array[\Upload\ValidationInterface] $validations
     * @return File Self
     */
    public function addValidations(array $validations): static
    {
        foreach ($validations as $validation) {
            $this->addValidation($validation);
        }

        return $this;
    }

    /********************************************************************************
     * Validation and Error Handling
     *******************************************************************************/

    /**
     * Add file validation
     *
     * @param ValidationInterface $validation
     * @return File Self
     */
    public function addValidation(ValidationInterface $validation): static
    {
        $this->validations[] = $validation;

        return $this;
    }

    /**
     * Get file validations
     *
     * @return array[\Upload\ValidationInterface]
     */
    public function getValidations(): array
    {
        return $this->validations;
    }

    /**
     * Get file validation errors
     *
     * @return array[String]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /********************************************************************************
     * Helper Methods
     *******************************************************************************/

    public function __call($name, $arguments)
    {
        $count = count($this->objects);
        $result = null;

        if ($count) {
            if ($count > 1) {
                $result = array();
                foreach ($this->objects as $object) {
                    $result[] = call_user_func_array(array($object, $name), $arguments);
                }
            } else {
                $result = call_user_func_array(array($this->objects[0], $name), $arguments);
            }
        }

        return $result;
    }

    /**
     * Upload file (delegated to storage object)
     *
     * @return bool
     * @throws Exception If validation fails
     * @throws Exception|\Exception If upload fails
     */
    public function upload(): bool
    {
        if ($this->isValid() === false) {
            throw new Exception('File validation failed');
        }

        foreach ($this->objects as $fileInfo) {
            $this->applyCallback('beforeUpload', $fileInfo);
            $this->storage->upload($fileInfo);
            $this->applyCallback('afterUpload', $fileInfo);
        }

        return true;
    }

    /**
     * Is this collection valid and without errors?
     *
     * @return bool
     */
    public function isValid(): bool
    {
        foreach ($this->objects as $fileInfo) {
            // Before validation callback
            $this->applyCallback('beforeValidation', $fileInfo);

            // Check is uploaded file
            if ($fileInfo->isUploadedFile() === false) {
                $this->errors[] = sprintf(
                    '%s: %s',
                    $fileInfo->getNameWithExtension(),
                    'Is not an uploaded file'
                );
                continue;
            }

            // Apply user validations
            foreach ($this->validations as $validation) {
                try {
                    $validation->validate($fileInfo);
                } catch (Exception $e) {
                    $this->errors[] = sprintf(
                        '%s: %s',
                        $fileInfo->getNameWithExtension(),
                        $e->getMessage()
                    );
                }
            }

            // After validation callback
            $this->applyCallback('afterValidation', $fileInfo);
        }

        return empty($this->errors);
    }

    /********************************************************************************
     * Upload
     *******************************************************************************/

    /**
     * Apply callable
     * @param string $callbackName
     * @param FileInfoInterface $file
     */
    protected function applyCallback(string $callbackName, FileInfoInterface $file): void
    {
        if (in_array($callbackName, array('beforeValidation', 'afterValidation', 'beforeUpload', 'afterUpload')) === true) {
            if (isset($this->$callbackName) === true) {
                call_user_func_array($this->$callbackName, array($file));
            }
        }
    }

    public function getNameWithExtension(): string
    {
        return self::getNameWithExtension();
    }

    /********************************************************************************
     * Array Access Interface
     *******************************************************************************/

    public function offsetExists($offset): bool
    {
        return isset($this->objects[$offset]);
    }

    #[ReturnTypeWillChange] public function offsetGet($offset)
    {
        return $this->objects[$offset] ?? null;
    }

    #[ReturnTypeWillChange] public function offsetSet($offset, $value)
    {
        $this->objects[$offset] = $value;
    }

    #[ReturnTypeWillChange] public function offsetUnset($offset)
    {
        unset($this->objects[$offset]);
    }

    /********************************************************************************
     * Iterator Aggregate Interface
     *******************************************************************************/

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->objects);
    }

    /********************************************************************************
     * Helpers
     *******************************************************************************/

    /********************************************************************************
     * Countable Interface
     *******************************************************************************/

    public function count(): int
    {
        return count($this->objects);
    }
}
