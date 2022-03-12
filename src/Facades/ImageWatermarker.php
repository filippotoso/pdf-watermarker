<?php

namespace FilippoToso\PdfWatermarker\Facades;

use Illuminate\Support\Facades\Facade;
use FilippoToso\PdfWatermarker\Watermarkers\ImageWatermarker as FacadeAccessor;

/**
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\ImageWatermarker output($filename)
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\ImageWatermarker position($position, $offsetX = 0, $offsetY = 0)
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\ImageWatermarker asBackground()
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\ImageWatermarker asOverlay()
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\ImageWatermarker pageRange($fromPage, $toPage = null)
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\ImageWatermarker save($output = null)
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\ImageWatermarker watermark($filename)
 * @method static \Illuminate\Http\Response stream($filename = null)
 * @method static \Illuminate\Http\Response download($filename = null)
 *
 * @see \FilippoToso\PdfWatermarker\Watermarkers\ImageWatermarker
 */
class ImageWatermarker extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FacadeAccessor::class;
    }
}
