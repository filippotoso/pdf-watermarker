<?php

namespace FilippoToso\PdfWatermarker\Facades;

use Illuminate\Support\Facades\Facade;
use FilippoToso\PdfWatermarker\Watermarkers\TextWatermarker as FacadeAccessor;

/**
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\TextWatermarker output($filename)
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\TextWatermarker position($position, $offsetX = 0, $offsetY = 0)
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\TextWatermarker asBackground()
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\TextWatermarker asOverlay()
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\TextWatermarker pageRange($fromPage, $toPage = null)
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\TextWatermarker save($output = null)
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\TextWatermarker output($filename)
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\TextWatermarker text($text)
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\TextWatermarker font($font)
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\TextWatermarker size($size)
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\TextWatermarker angle($angle)
 * @method static \FilippoToso\PdfWatermarker\Watermarkers\TextWatermarker color($color)
 * @method static \Illuminate\Http\Response stream($filename = null)
 * @method static \Illuminate\Http\Response download($filename = null)
 *
 * @see \FilippoToso\PdfWatermarker\Watermarkers\TextWatermarker
 */
class TextWatermarker extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FacadeAccessor::class;
    }
}
