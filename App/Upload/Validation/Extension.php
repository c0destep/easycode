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

namespace Upload\Validation;

use RuntimeException;
use Upload\Exception;
use Upload\FileInfoInterface;
use Upload\ValidationInterface;

/**
 * Validate File Extension
 *
 * This class validates an uploads file extension. It takes file extension with out dot
 * or array of extensions. For example: 'png' or array('jpg', 'png', 'gif').
 *
 * WARNING! Validation only by file extension not very secure.
 *
 * @author  Alex Kucherenko <kucherenko.email@gmail.com>
 * @package Upload
 */
class Extension implements ValidationInterface
{
    /**
     * Array of acceptable file extensions without leading dots
     * @var array
     */
    protected array $allowedExtensions;

    /**
     * Constructor
     *
     * @param array|string $allowedExtensions Allowed file extensions
     * @example new \Upload\Validation\Extension(array('png','jpg','gif'))
     * @example new \Upload\Validation\Extension('png')
     */
    public function __construct(array|string $allowedExtensions)
    {
        if (is_string($allowedExtensions) === true) {
            $allowedExtensions = array($allowedExtensions);
        }

        $this->allowedExtensions = array_map('strtolower', $allowedExtensions);
    }

    /**
     * Validate
     *
     * @param FileInfoInterface $fileInfo
     * @throws RuntimeException If validation fails
     */
    public function validate(FileInfoInterface $fileInfo)
    {
        $fileExtension = strtolower($fileInfo->getExtension());

        if (in_array($fileExtension, $this->allowedExtensions) === false) {
            throw new Exception(sprintf('Invalid extension. It must be one of the following: %s', implode(', ', $this->allowedExtensions)), $fileInfo);
        }
    }
}