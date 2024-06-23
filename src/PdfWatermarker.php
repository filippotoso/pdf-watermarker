<?php

namespace FilippoToso\PdfWatermarker;

use setasign\Fpdi\Fpdi;
use FilippoToso\PdfWatermarker\Contracts\Watermarker;
use FilippoToso\PdfWatermarker\Contracts\Watermark;
use FilippoToso\PdfWatermarker\Support\Pdf;
use FilippoToso\PdfWatermarker\Support\Position;

class PdfWatermarker implements Watermarker
{
    protected const DEFAULT_FILENAME = 'document.pdf';
    protected const DEFAULT_RESOLUTION = 96;

    protected $watermark;
    protected $totalPages;
    protected $specificPages = [];
    protected $position;
    protected $asBackground = false;
    protected $resolution = self::DEFAULT_RESOLUTION;

    /** @var Fpdi */
    protected $fpdi;

    public function __construct(Pdf $file, Watermark $watermark, $resolution = self::DEFAULT_RESOLUTION)
    {
        $this->fpdi = new Fpdi();
        $this->totalPages = $this->fpdi->setSourceFile($file->getRealPath());
        $this->watermark = $watermark;
        $this->position = new Position(Position::MIDDLE_CENTER);
        $this->resolution = $resolution;
    }

    /**
     * Set page range.
     *
     * @param int $startPage - the first page to be watermarked
     * @param int $endPage - (optional) the last page to be watermarked
     */
    public function setPageRange($startPage = 1, $endPage = null)
    {
        $endPage = is_null($endPage) ? $this->totalPages : $endPage;

        foreach (range($startPage, $endPage) as $pageNumber) {
            $this->specificPages[] = $pageNumber;
        }
    }

    /**
     * Apply the watermark below the PDF's content.
     */
    public function setAsBackground()
    {
        $this->asBackground = true;
    }

    /**
     * Apply the watermark over the PDF's content.
     */
    public function setAsOverlay()
    {
        $this->asBackground = false;
    }

    /**
     * Set the Position of the Watermark
     *
     * @param Position $position
     */
    public function setPosition(Position $position)
    {
        $this->position = $position;
    }

    /**
     * Loop through the pages while applying the watermark.
     */
    protected function process()
    {
        foreach (range(1, $this->totalPages) as $pageNumber) {
            $this->importPage($pageNumber);

            if (in_array($pageNumber, $this->specificPages) || empty($this->specificPages)) {
                $this->watermarkPage($pageNumber);
            } else {
                $this->watermarkPage($pageNumber, false);
            }
        }
    }

    /**
     * Import page.
     *
     * @param int $pageNumber - page number
     */
    protected function importPage($pageNumber)
    {
        $templateId = $this->fpdi->importPage($pageNumber);
        $templateDimension = $this->fpdi->getTemplateSize($templateId);

        if ($templateDimension['width'] > $templateDimension['height']) {
            $orientation = "L";
        } else {
            $orientation = "P";
        }

        $this->fpdi->addPage($orientation, array($templateDimension['width'], $templateDimension['height']));
    }

    /**
     * Apply the watermark to a specific page.
     *
     * @param int $pageNumber - page number
     * @param bool $watermark_visible - (optional) Make the watermark visible. True by default.
     */
    protected function watermarkPage($pageNumber, $watermark_visible = true)
    {
        $templateId = $this->fpdi->importPage($pageNumber);
        $templateDimension = $this->fpdi->getTemplateSize($templateId);

        $wWidth = ($this->watermark->getWidth() / $this->resolution) * 25.4; //in mm
        $wHeight = ($this->watermark->getHeight() / $this->resolution) * 25.4; //in mm

        $watermarkCoords = $this->calculateWatermarkCoordinates(
            $wWidth,
            $wHeight,
            $templateDimension['width'],
            $templateDimension['height']
        );

        if ($watermark_visible) {
            if ($this->asBackground) {
                $this->fpdi->Image($this->watermark->getFilePath(), $watermarkCoords[0], $watermarkCoords[1], -$this->resolution);
                $this->fpdi->useTemplate($templateId);
            } else {
                $this->fpdi->useTemplate($templateId);
                $this->fpdi->Image($this->watermark->getFilePath(), $watermarkCoords[0], $watermarkCoords[1], -$this->resolution);
            }
        } else {
            $this->fpdi->useTemplate($templateId);
        }
    }

    /**
     * Calculate the coordinates of the watermark's position.
     *
     * @param int $wWidth - watermark's width
     * @param int $wHeight - watermark's height
     * @param int $tWidth - page width
     * @param int $Height -page height
     *
     * @return array - coordinates of the watermark's position
     */
    protected function calculateWatermarkCoordinates($wWidth, $wHeight, $tWidth, $tHeight)
    {
        switch ($this->position->getName()) {
            case 'TopLeft':
                $x = 0;
                $y = 0;
                break;
            case 'TopCenter':
                $x = ($tWidth - $wWidth) / 2;
                $y = 0;
                break;
            case 'TopRight':
                $x = $tWidth - $wWidth;
                $y = 0;
                break;
            case 'MiddleLeft':
                $x = 0;
                $y = ($tHeight - $wHeight) / 2;
                break;
            case 'MiddleRight':
                $x = $tWidth - $wWidth;
                $y = ($tHeight - $wHeight) / 2;
                break;
            case 'BottomLeft':
                $x = 0;
                $y = $tHeight - $wHeight;
                break;
            case 'BottomCenter':
                $x = ($tWidth - $wWidth) / 2;
                $y = $tHeight - $wHeight;
                break;
            case 'BottomRight':
                $x = $tWidth - $wWidth;
                $y = $tHeight - $wHeight;
                break;
            case 'MiddleCenter':
            default:
                $x = ($tWidth - $wWidth) / 2;
                $y = ($tHeight - $wHeight) / 2;
                break;
        }

        $x += $this->position->getOffsetX();
        $y += $this->position->getOffsetY();

        return array($x, $y);
    }

    /**
     * @param string $fileName
     * @return void
     */
    public function save($fileName = 'document.pdf')
    {
        $this->process();
        $this->fpdi->Output($fileName, 'F');
    }

    /**
     * Return a Laravel response to stream the watermarked PDF
     *
     * @param string $filename
     * @return Illuminate\Http\Response|self
     */
    public function stream($filename = null)
    {
        return $this->response($filename, true);
    }

    /**
     * Return a Laravel response to download the watermarked PDF
     *
     * @param string $filename
     * @return Illuminate\Http\Response|self
     */
    public function download($filename = null)
    {
        return $this->response($filename, false);
    }

    /**
     * Return a Laravel response to download the watermarked PDF
     *
     * @param string $filename
     * @return Illuminate\Http\Response|self
     */
    protected function response($filename, $inline = true)
    {
        if ($filename) {
            $filename = basename($filename);
        } else {
            $filename = static::DEFAULT_FILENAME;
        }

        $this->process();

        if (class_exists(\Illuminate\Http\Response::class)) {
            return response()->streamDownload(function () use ($filename) {
                echo ($this->string());
            }, $filename, [
                'Content-Type' => $inline ? 'application/pdf' : 'application/octet-stream',
                'Cache-Control' => 'private, max-age=0, must-revalidate',
                'Pragma' => 'public',
            ], $inline ? 'inline' : 'attachment');
        } else {
            if ($inline) {
                $this->fpdi->Output($filename, 'I');
            } else {
                $this->fpdi->Output($filename, 'D');
            }
            return $this;
        }
    }

    /**
     * @param string $fileName
     * @return void
     */
    public function string($fileName = 'document.pdf')
    {
        $this->process();
        return $this->fpdi->Output($fileName, 'S');
    }
}
