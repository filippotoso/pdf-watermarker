<?php

namespace FilippoToso\PdfWatermarker\Contracts;

interface Watermark
{
    /**
     * Return the path to the tmp file
     *
     * @return string
     */
    public function getFilePath();

    /**
     * Returns the watermark's height
     *
     * @return int
     */
    public function getHeight();

    /**
     * Returns the watermark's width
     *
     * @return int
     */
    public function getWidth();
}
