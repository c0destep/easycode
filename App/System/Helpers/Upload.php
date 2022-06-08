<?php

use Ramsey\Uuid\Uuid;
use System\Libraries\Images;
use Upload\File;
use Upload\Storage\FileSystem;
use Upload\Validation\Dimensions;
use Upload\Validation\Mimetype;
use Upload\Validation\Size;

if (!function_exists("uploadImage")) {
    /**
     * @param string $inputName
     * @param string|null $newNameImage
     * @param string $maxSize
     * @param string|null $minSize
     * @param array $pixels
     * @param array $mimes
     * @return array|string
     */
    function uploadImage(string $inputName, string $newNameImage = null, string $maxSize = "5M", string $minSize = null, array $pixels = [], array $mimes = ['image/png', 'image/gif', 'image/jpeg', 'image/jpg']): array|string
    {
        $config = getConfig("upload");
        $storage = new FileSystem(ROOT_PATH . $config['image']);
        $file = new File($inputName, $storage);

        $uuid = Uuid::uuid6();

        if (!is_null($newNameImage)) {
            $file->setName($newNameImage);
        } else {
            $file->setName($uuid->toString());
        }

        $validations = [];
        $validations[] = new Mimetype($mimes);
        $validations[] = new Size($file->humanReadableToBytes($maxSize), !is_null($minSize) ? $file->humanReadableToBytes($minSize) : 0);

        if (!empty($pixels) && is_array($pixels)) {
            $validations[] = new Dimensions($pixels['width'], $pixels['height']);
        }

        $file->addValidations($validations);

        try {
            $file->upload();
            return $file->getNameWithExtension();
        } catch (Exception) {
            return $file->getErrors();
        }
    }
}

if (!function_exists("imageCache")) {
    /**
     * @param string $filePath
     * @param int|null $width
     * @param int|null $height
     * @return string|null
     */
    function imageCache(string $filePath, int $width = null, int $height = null): ?string
    {
        $config = getConfig('upload');
        $filePath = str_replace(getConfig('route'), DIRECTORY_SEPARATOR, $filePath);
        $filePath = str_replace($config['image'], "", $filePath);

        if (!is_file(ROOT_PATH . $config['image'] . $filePath)) {
            return null;
        } else {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $imageOld = $filePath;

            if (!is_null($width) && !is_null($height)) {
                $imageNew = utf8_substr($filePath, 0, utf8_strrpos($filePath, '.')) . '-' . $width . 'x' . $height . '.' . $extension;
            } elseif (!is_null($width)) {
                $imageNew = utf8_substr($filePath, 0, utf8_strrpos($filePath, '.')) . '-' . $width . 'w.' . $extension;
            } elseif (!is_null($height)) {
                $imageNew = utf8_substr($filePath, 0, utf8_strrpos($filePath, '.')) . '-' . $height . 'h.' . $extension;
            } else {
                $imageNew = utf8_substr($filePath, 0, utf8_strrpos($filePath, '.')) . '.' . $extension;
            }

            if (!is_file(ROOT_PATH . getConfig('cache_image') . $imageNew) || (filectime(ROOT_PATH . $config['image'] . $imageOld) > filectime(ROOT_PATH . getConfig('cache_image') . $imageNew))) {
                list($widthOrig, $heightOrig, $imageType) = getimagesize(ROOT_PATH . $config['image'] . $imageOld);

                if (!in_array($imageType, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
                    return ROOT_PATH . $config['image'] . $imageOld;
                }

                $path = "";
                $directories = explode(DIRECTORY_SEPARATOR, dirname($imageNew));
                foreach ($directories as $directory) {
                    $path = $path . DIRECTORY_SEPARATOR . $directory;
                    if (!is_dir(ROOT_PATH . getConfig('cache_image') . $path)) {
                        mkdir(ROOT_PATH . getConfig('cache_image') . $path);
                    }
                }

                if (!is_null($width) && ($widthOrig !== $width) && !is_null($height) && ($heightOrig !== $height)) {
                    $image = new Images(ROOT_PATH . $config['image'] . $imageOld);
                    $image->resize($width, $height);
                    $image->save(ROOT_PATH . getConfig('cache_image') . $imageNew);
                } else {
                    copy(ROOT_PATH . $config['image'] . $imageOld, ROOT_PATH . getConfig('cache_image') . $imageNew);
                }
            }

            $imageNew = str_replace(' ', '%20', $imageNew);
            return getConfig('cache_image') . $imageNew;
        }
    }
}
