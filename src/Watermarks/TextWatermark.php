<?php

namespace FilippoToso\PdfWatermarker\Watermarks;

use FilippoToso\PdfWatermarker\Contracts\Watermark;

class TextWatermark implements Watermark
{
    public const COLOR_PATTERN = '/#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})?/si';

    protected const PADDING = 5; // In pixels

    protected $height;
    protected $width;
    protected $tmpFile;

    /**
     * Create a text watermark
     *
     * @param string $text The text that will be rendered
     * @param string $font The path of the TTF font file
     * @param integer $size The font size
     * @param integer $angle The angle of the text
     * @param string $color The color of the text in #RRGGBBAA (Red, Green, Blue and Alpha). For instance #FF00007F (Red with 50% opacity)
     */
    public function __construct($text, $font = null, $size = 10, $angle = 0, $color = '#00000000')
    {
        $this->tmpFile = sys_get_temp_dir() . '/' . uniqid() . '.png';

        $textBox = imagettfbbox($size, $angle, $font, $text);

        $textBox = $this->textBox($textBox);

        $this->width = $textBox['width'];
        $this->height = $textBox['height'];

        $image = imagecreatetruecolor($this->width, $this->height);

        imagesavealpha($image, true);
        imageinterlace($image, false);
        imagealphablending($image, false);

        $colorArray = $this->rgbaToArray($color);

        $white = imagecolorallocatealpha($image, 255, 255, 255, 127);
        imagefill($image, 0, 0, $white);

        $textColor = imagecolorallocatealpha($image, $colorArray['red'], $colorArray['green'], $colorArray['blue'], $colorArray['alpha']);
        imagettftext($image, $size, $angle, $textBox['left'], $textBox['top'], $textColor, $font, $text);
        imagepng($image, $this->tmpFile);

        imagedestroy($image);
    }

    protected function textBox($rect)
    {

        $minX = min([$rect[0], $rect[2], $rect[4], $rect[6]]);
        $maxX = max([$rect[0], $rect[2], $rect[4], $rect[6]]);
        $minY = min([$rect[1], $rect[3], $rect[5], $rect[7]]);
        $maxY = max([$rect[1], $rect[3], $rect[5], $rect[7]]);

        // Add  px of padding to avoid cropping
        return [
            'left' => abs($minX) + static::PADDING,
            'top' => abs($minY) + static::PADDING,
            'width' => $maxX - $minX + static::PADDING * 2,
            'height' => $maxY - $minY + static::PADDING * 2,
            'box' => $rect
        ];
    }

    protected function rgbaToArray($color, $defaultAlpha = 0)
    {
        $result = [
            'red' => 0,
            'green' => 0,
            'blue' => 0,
            'alpha' => $defaultAlpha,
        ];

        $pattern = static::COLOR_PATTERN;
        if (preg_match($pattern, $color, $matches)) {
            $result = [
                'red' => hexdec($matches[1]),
                'green' => hexdec($matches[2]),
                'blue' => hexdec($matches[3]),
                'alpha' => ($matches[4] ?? null) ? floor(hexdec($matches[4]) / 2) : $defaultAlpha,
            ];
        }

        return $result;
    }

    /**
     * Return the path to the tmp file
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->tmpFile;
    }

    /**
     * Returns the watermark's height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Returns the watermark's width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }
}
