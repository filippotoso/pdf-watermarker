<?php

namespace FilippoToso\PdfWatermarker\Facades;

use Illuminate\Support\Facades\Facade;
use FilippoToso\PdfWatermarker\Laravel\ImageWatermarker as FacadeAccessor;

/**
 * @method static \FilippoToso\PdfWatermarker\Laravel\ImageWatermarker output($filename)
 * @method static \FilippoToso\PdfWatermarker\Laravel\ImageWatermarker position($position, $offsetX = 0, $offsetY = 0)
 * @method static \FilippoToso\PdfWatermarker\Laravel\ImageWatermarker asBackground()
 * @method static \FilippoToso\PdfWatermarker\Laravel\ImageWatermarker asOverlay()
 * @method static \FilippoToso\PdfWatermarker\Laravel\ImageWatermarker pageRange($fromPage, $toPage = null)
 * @method static \FilippoToso\PdfWatermarker\Laravel\ImageWatermarker save($output = null)
 * @method static \FilippoToso\PdfWatermarker\Laravel\ImageWatermarker watermark($filename)
 * @method static \Illuminate\Http\Response stream($filename = null)
 * @method static \Illuminate\Http\Response download($filename = null)
 *
 * @see \FilippoToso\PdfWatermarker\Laravel\ImageWatermarker
 */
class ImageWatermarker extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FacadeAccessor::class;
    }
}
