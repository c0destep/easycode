<?php

namespace Upload\Validation;

use Upload\Exception;
use Upload\FileInfoInterface;
use Upload\ValidationInterface;

class Dimensions implements ValidationInterface
{
    /**
     * @var integer
     */
    protected int $width;

    /**
     * @var integer
     */
    protected int $height;

    /**
     * @param int $expectedWidth
     * @param int $expectedHeight
     */
    function __construct(int $expectedWidth, int $expectedHeight)
    {
        $this->width = $expectedWidth;
        $this->height = $expectedHeight;
    }

    /**
     * @inheritdoc
     */
    public function validate(FileInfoInterface $fileInfo)
    {
        $dimensions = $fileInfo->getDimensions();
        $filename = $fileInfo->getNameWithExtension();

        if (!$dimensions) {
            throw new Exception(sprintf('%s: File extension not detected.', $filename));
        }

        if ($dimensions['width'] !== $this->width) {
            throw new Exception(
                sprintf(
                    '%s: Image width(%dpx) does not match required width(%dpx)',
                    $filename,
                    $dimensions['width'],
                    $this->width
                )
            );
        }

        if ($dimensions['height'] !== $this->height) {
            throw new Exception(
                sprintf(
                    '%s: Image height(%dpx) does not match required height(%dpx)',
                    $filename,
                    $dimensions['height'],
                    $this->height
                )
            );
        }
    }
}