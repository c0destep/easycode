<?php

namespace System\Libraries;

use GdImage;

class Images
{
    private string $filePath;
    private mixed $image;
    private int $width;
    private int $height;
    private string $bits;
    private string $mimeType;

    public function __construct(string $filePath)
    {
        if (file_exists($filePath)) {
            $this->filePath = $filePath;

            $info = getimagesize($filePath);

            $this->width = $info[0];
            $this->height = $info[1];
            $this->bits = $info['bits'] ?? "";
            $this->mimeType = $info['mime'] ?? "";

            if ($this->mimeType === 'image/gif') {
                $this->image = imagecreatefromgif($this->filePath);
            } elseif ($this->mimeType === 'image/png') {
                $this->image = imagecreatefrompng($this->filePath);
            } elseif ($this->mimeType === 'image/jpeg') {
                $this->image = imagecreatefromjpeg($this->filePath);
            }

            return $this;
        } else {
            setFlashError('Error: Could not load image ' . $filePath . '!');
            return null;
        }
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function getBits(): string
    {
        return $this->bits;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function save(string $filePath, int $quality = 90): void
    {
        $info = pathinfo($this->filePath);
        $extension = strtolower($info['extension']);

        if ($extension === 'jpeg' || $extension === 'jpg') {
            imagejpeg($this->image, $filePath, $quality);
        } elseif ($extension === 'png') {
            imagepng($this->image, $filePath, $quality / 10);
        } elseif ($extension === 'gif') {
            imagegif($this->image, $filePath);
        } else {
            imagedestroy($this->image);
        }
    }

    public function resize(int $width = 0, int $height = 0, string $default = ""): void
    {
        $scaleWidth = $width / $this->width;
        $scaleHeight = $height / $this->height;

        if ($default === 'w') {
            $scale = $scaleWidth;
        } elseif ($default === 'h') {
            $scale = $scaleHeight;
        } else {
            $scale = min($scaleWidth, $scaleHeight);
        }

        $newWidth = (int)($this->width * $scale);
        $newHeight = (int)($this->height * $scale);

        $x = ($width - $newWidth) / 2;
        $y = ($height - $newHeight) / 2;

        $imageOld = $this->image;
        $this->image = imagecreatetruecolor($width, $height);

        if ($this->mimeType === 'image/png') {
            imagealphablending($this->image, false);
            imagesavealpha($this->image, true);
            $background = imagecolorallocatealpha($this->image, 255, 255, 255, 127);
            imagecolortransparent($this->image, $background);
        } else {
            $background = imagecolorallocate($this->image, 255, 255, 255);
        }

        imagefilledrectangle($this->image, 0, 0, $width, $height, $background);

        imagecopyresampled($this->image, $imageOld, $x, $y, 0, 0, $newWidth, $newHeight, $this->width, $this->height);
        imagedestroy($imageOld);

        $this->width = $width;
        $this->height = $height;
    }

    public function watermark(Images $watermark, string $position = 'bottom_right'): void
    {
        switch ($position) {
            case 'top_left':
                $watermark_pos_x = 0;
                $watermark_pos_y = 0;
                break;
            case 'top_right':
                $watermark_pos_x = $this->width - $watermark->getWidth();
                $watermark_pos_y = 0;
                break;
            case 'bottom_left':
                $watermark_pos_x = 0;
                $watermark_pos_y = $this->height - $watermark->getHeight();
                break;
            default:
                $watermark_pos_x = $this->width - $watermark->getWidth();
                $watermark_pos_y = $this->height - $watermark->getHeight();
                break;
        }

        imagecopy($this->image, $watermark->getImage(), $watermark_pos_x, $watermark_pos_y, 0, 0, $watermark->getWidth(), $watermark->getHeight());

        imagedestroy($watermark->getImage());
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getImage(): GdImage|bool
    {
        return $this->image;
    }

    public function crop(int $topX, int $topY, int $bottomX, int $bottomY): void
    {
        $imageOld = $this->image;
        $this->image = imagecreatetruecolor($bottomX - $topX, $bottomY - $topY);

        if ($this->mimeType === 'image/png') {
            imagesavealpha($this->image, true);
            $color = imagecolorallocatealpha($this->image, 0, 0, 0, 127);
            imagefill($this->image, 0, 0, $color);
        }

        imagecopy($this->image, $imageOld, 0, 0, $topX, $topY, $this->width, $this->height);
        imagedestroy($imageOld);

        $this->width = $bottomX - $topX;
        $this->height = $bottomY - $topY;
    }

    public function rotate(float $degree, string $color = 'FFFFFF'): void
    {
        $rgb = $this->html2rgb($color);

        $this->image = imagerotate($this->image, $degree, imagecolorallocate($this->image, $rgb[0], $rgb[1], $rgb[2]));

        $this->width = imagesx($this->image);
        $this->height = imagesy($this->image);
    }

    private function html2rgb(string $color): ?array
    {
        if ($color[0] === '#') {
            $color = substr($color, 1);
        }

        if (strlen($color) === 6) {
            list($r, $g, $b) = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) === 3) {
            list($r, $g, $b) = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return null;
        }

        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);

        return array($r, $g, $b);
    }

    private function filter(): void
    {
        $args = func_get_args();
        call_user_func_array('imagefilter', $args);
    }

    private function text(string $text, int $x = 0, int $y = 0, int $size = 5, string $color = "000000"): void
    {
        $rgb = $this->html2rgb($color);
        imagestring($this->image, $size, $x, $y, $text, imagecolorallocate($this->image, $rgb[0], $rgb[1], $rgb[2]));
    }

    private function merge(Images $merge, int $x = 0, int $y = 0, int $opacity = 100): void
    {
        imagecopymerge($this->image, $merge->getImage(), $x, $y, 0, 0, $merge->getWidth(), $merge->getHeight(), $opacity);
    }
}
