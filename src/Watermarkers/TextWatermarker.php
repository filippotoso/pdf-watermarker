<?php

namespace FilippoToso\PdfWatermarker\Watermarkers;

use FilippoToso\PdfWatermarker\Watermarkers\Exceptions\InvalidColorException;
use FilippoToso\PdfWatermarker\Watermarkers\Exceptions\InvalidFontFileException;
use FilippoToso\PdfWatermarker\Watermarkers\Exceptions\InvalidInputFileException;
use FilippoToso\PdfWatermarker\Watermarks\TextWatermark;
use FilippoToso\PdfWatermarker\PdfWatermarker as Watermarker;
use FilippoToso\PdfWatermarker\Support\Pdf;

class TextWatermarker extends BaseWatermarketer
{

    protected $text;
    protected $font;
    protected $size = 10;
    protected $angle = 0;
    protected $color = '#00000000';
    protected $opacity = 1;

    /**
     * Set the wtaermark text
     *
     * @param string $text
     * @return TextWatermarker
     */
    public function text($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Set the TTF font path
     *
     * @param string $font
     * @return TextWatermarker
     */
    public function font($font)
    {
        $this->font = $font;
        return $this;
    }

    /**
     * Set the font size
     *
     * @param float $size
     * @return TextWatermarker
     */
    public function size($size)
    {
        $this->size = (float)$size;
        return $this;
    }

    /**
     * Set the text angle
     *
     * @param float $angle
     * @return TextWatermarker
     */
    public function angle($angle)
    {
        $this->angle = (float)$angle;
        return $this;
    }

    /**
     * Set the text color in #RRGGBBAA format
     *
     * @param string $color
     * @return TextWatermarker
     */
    public function color($color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * Set the text opacity
     *
     * @param $opacity
     * @return $this
     */
    public function opacity($opacity)
    {
        $this->opacity = $opacity;
        return $this;
    }

    protected function watermarker(): Watermarker
    {
        if (!is_readable($this->input)) {
            throw new InvalidInputFileException('The specified input file is not valid');
        }

        if (!is_readable($this->font)) {
            throw new InvalidFontFileException('The specified font file is not valid');
        }

        if (!preg_match(TextWatermark::COLOR_PATTERN, $this->color)) {
            throw new InvalidColorException('The specified color format is not valid');
        }

        $pdf = new Pdf($this->input);

        $watermark = new TextWatermark($this->text, $this->font, $this->size, $this->angle, $this->color, $this->opacity);

        $watermarker = new Watermarker($pdf, $watermark, $this->resolution);

        if ($this->position) {
            $watermarker->setPosition($this->position);
        }

        if ($this->asBackground) {
            $watermarker->setAsBackground();
        }

        if ($this->asOverlay) {
            $watermarker->setAsOverlay();
        }

        $watermarker->setPageRange($this->pageRangeFrom, $this->pageRangeTo);

        return $watermarker;
    }
}
