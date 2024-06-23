<?php

namespace FilippoToso\PdfWatermarker\Watermarkers;

use FilippoToso\PdfWatermarker\PdfWatermarker as Watermarker;
use FilippoToso\PdfWatermarker\Watermarkers\Exceptions\InvalidOutputFileException;
use FilippoToso\PdfWatermarker\Support\Position;

abstract class BaseWatermarketer
{
    protected const DEFAULT_FILENAME = 'document.pdf';

    protected $input;
    protected $output;
    protected $position;
    protected $asBackground = false;
    protected $asOverlay = false;
    protected $pageRangeFrom = 1;
    protected $pageRangeTo = null;
    protected $resolution = null;

    /**
     * Set the input PDF
     *
     * @param string $filename
     * @return BaseWatermarketer
     */
    public function input($filename)
    {
        $this->input = $filename;
        return $this;
    }

    /**
     * Set the output PDF (used by save())
     *
     * @param string $filename
     * @return BaseWatermarketer
     */
    public function output($filename)
    {
        $this->output = $filename;
        return $this;
    }

    /**
     * Set the text position with X/Y offsets
     *
     * @param string $position
     * @param integer $offsetX
     * @param integer $offsetY
     * @return BaseWatermarketer
     *
     * @see \FilippoToso\PdfWatermarker\Support\Position
     */
    public function position($position, $offsetX = 0, $offsetY = 0)
    {
        $this->position = new Position($position, $offsetX, $offsetY);
        return $this;
    }

    /**
     * Set the image resolution
     *
     * @param int $resolution
     * @return BaseWatermarketer
     */
    public function resolution($resolution)
    {
        $this->resolution = $resolution;
        return $this;
    }

    /**
     * Specify if the watermark has to be inserted in the background
     *
     * @return BaseWatermarketer
     */
    public function asBackground()
    {
        $this->asBackground = true;
        return $this;
    }

    /**
     * Specify if the watermark has to be inserted as an overlay
     *
     * @return BaseWatermarketer
     */
    public function asOverlay()
    {
        $this->asOverlay = true;
        return $this;
    }

    /**
     * Set the page range
     *
     * @param int $fromPage
     * @param int $toPage
     * @return BaseWatermarketer
     */
    public function pageRange($fromPage, $toPage = null)
    {
        $this->pageRangeFrom = (int)$fromPage;
        $this->pageRangeTo = (int)$toPage;
        return $this;
    }

    /**
     * Save the watermarked PDF in the path specified with the output() method or in the optional $output parameter
     *
     * @param string $output
     * @return BaseWatermarketer
     */
    public function save($output = null)
    {
        $output = is_null($output) ? $this->output : $output;

        if (is_null($output) || !is_writable(dirname($output)) || (file_exists($output) && !is_writable($output))) {
            throw new InvalidOutputFileException('The specified output file is not valid');
        }

        $watermarker = $this->watermarker();

        $watermarker->save($output);

        return $this;
    }

    /**
     * Return a Laravel response to stream the watermarked PDF
     *
     * @param string $filename
     * @return Illuminate\Http\Response|BaseWatermarketer
     */
    public function stream($filename = null)
    {
        return $this->response($filename, true);
    }

    /**
     * Return a Laravel response to download the watermarked PDF
     *
     * @param string $filename
     * @return Illuminate\Http\Response|BaseWatermarketer
     */
    public function download($filename = null)
    {
        return $this->response($filename, false);
    }

    protected function response($filename, $inline = true)
    {
        if ($filename) {
            $filename = basename($filename);
        } elseif ($this->output) {
            $filename = basename($this->output);
        } else {
            $filename = static::DEFAULT_FILENAME;
        }

        $watermarker = $this->watermarker();

        if (class_exists(\Illuminate\Http\Response::class)) {
            return response()->streamDownload(function () use ($watermarker, $filename) {
                echo ($watermarker->string());
            }, $filename, [
                'Content-Type' => $inline ? 'application/pdf' : 'application/octet-stream',
                'Cache-Control' => 'private, max-age=0, must-revalidate',
                'Pragma' => 'public',
            ], $inline ? 'inline' : 'attachment');
        } else {
            if ($inline) {
                $watermarker->stream($filename);
            } else {
                $watermarker->download($filename);
            }
            return $this;
        }
    }

    protected abstract function watermarker(): Watermarker;
}
