<?php

namespace FilippoToso\PdfWatermarker\Laravel;

use FilippoToso\PdfWatermarker\Laravel\Exceptions\InvalidInputFileException;
use FilippoToso\PdfWatermarker\Laravel\Exceptions\InvalidWatermarkFileException;
use FilippoToso\PdfWatermarker\PdfWatermarker as Watermarker;
use FilippoToso\PdfWatermarker\Watermarks\ImageWatermark;
use FilippoToso\PdfWatermarker\Support\Pdf;

class ImageWatermarker extends BaseWatermarketer
{
    protected $watermark;

    /**
     * Set the watermark image
     *
     * @param string $filename
     * @return ImageWatermarker
     */
    public function watermark($filename)
    {
        $this->watermark = $filename;
        return $this;
    }

    protected function watermarker(): Watermarker
    {
        if (!is_readable($this->input)) {
            throw new InvalidInputFileException('The specified input file is not valid');
        }

        if (!is_readable($this->watermark)) {
            throw new InvalidWatermarkFileException('The specified watermark file is not valid');
        }

        $pdf = new Pdf($this->input);

        $watermark = new ImageWatermark($this->watermark);

        $watermarker = new Watermarker($pdf, $watermark);

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
