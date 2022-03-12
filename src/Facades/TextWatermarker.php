<?php

namespace FilippoToso\PdfWatermarker\Facades;

use Illuminate\Support\Facades\Facade;
use FilippoToso\PdfWatermarker\Laravel\TextWatermarker as FacadeAccessor;

/**
 * @method static \FilippoToso\PdfWatermarker\Laravel\TextWatermarker output($filename)
 * @method static \FilippoToso\PdfWatermarker\Laravel\TextWatermarker position($position, $offsetX = 0, $offsetY = 0)
 * @method static \FilippoToso\PdfWatermarker\Laravel\TextWatermarker asBackground()
 * @method static \FilippoToso\PdfWatermarker\Laravel\TextWatermarker asOverlay()
 * @method static \FilippoToso\PdfWatermarker\Laravel\TextWatermarker pageRange($fromPage, $toPage = null)
 * @method static \FilippoToso\PdfWatermarker\Laravel\TextWatermarker save($output = null)
 * @method static \FilippoToso\PdfWatermarker\Laravel\TextWatermarker output($filename)
 * @method static \FilippoToso\PdfWatermarker\Laravel\TextWatermarker text($text)
 * @method static \FilippoToso\PdfWatermarker\Laravel\TextWatermarker font($font)
 * @method static \FilippoToso\PdfWatermarker\Laravel\TextWatermarker size($size)
 * @method static \FilippoToso\PdfWatermarker\Laravel\TextWatermarker angle($angle)
 * @method static \FilippoToso\PdfWatermarker\Laravel\TextWatermarker color($color)
 * @method static \Illuminate\Http\Response stream($filename = null)
 * @method static \Illuminate\Http\Response download($filename = null)
 *
 * @see \FilippoToso\PdfWatermarker\Laravel\TextWatermarker
 */
class TextWatermarker extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FacadeAccessor::class;
    }
}
