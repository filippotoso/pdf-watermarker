# PDF Watermarker
PDFWatermarker enables you to add a text or an image as a watermark to existing PDF files. It uses FPDF that allows you to write PDF files and FPDI that allows you to import existing PDF documents into FPDF.

Using it, you can:

* Use a text and TTF font to create a watermark
* Use a jpg and png ( with alpha channels ) images with a 96 DPI resolution as a watermark
* Easily position the watermark on the pages of the PDF file

## Installation

Installing using composer

``` bash
composer require filippo-toso/pdf-watermarker
```
## Usage

In vanilla PHP you can watermark a PDF with an image in this way:

``` php
<?php

use FilippoToso\PdfWatermarker\Support\Pdf;
use FilippoToso\PdfWatermarker\Support\ImageWatermark;
use FilippoToso\PdfWatermarker\PdfWatermarker;

// Specify path to the existing pdf
$pdf = new Pdf('input.pdf');

// Specify path to image. The image must have a 96 DPI resolution.
$watermark = new Watermark('watermark.png'); 

// Create a new watermarker
$watermarker = new PDFWatermarker($pdf, $watermark); 
 
// Save the new PDF to its specified location
$watermarker->save('output.pdf');

```

## Options

You can also specify additional options:

``` php
use FilippoToso\PdfWatermarker\Support\Position;

// Set the position of the watermark including optional X/Y offsets
$position = new Position(Position::BOTTOM_CENTER, -50, -10);

// All possible positions can be found in Position::options
$watermarker->setPosition($position);

// Place watermark behind original PDF content. Default behavior places it over the content.
$watermarker->setAsBackground();

// Only Watermark specific range of pages
// This would only watermark page 3 and 4
$watermarker->setPageRange(3, 4);

```

## Output Methods

You can get the watermarked PDF in the following ways:

``` php
// The filename is optional for all output options
$watermarker->save();

// Start a download of the PDF
$watermarker->download('output.pdf');

// Send the PDF to standard out
$watermarker->output('output.pdf');

```

## Laravel Support

In Laravel you can use the two facades: ImageWatermarker and TextWatermarker:

``` php
use FilippoToso\PdfWatermarker\Facades\ImageWatermarker;
use FilippoToso\PdfWatermarker\Support\Position

ImageWatermarker::input('input.pdf')
    ->watermark('watermark.png')
    ->output('laravel-image.pdf')
    ->position(Position::BOTTOM_CENTER, -50, -10)
    ->asBackground()
    ->pageRange(3, 4)
    ->save();
```

``` php
use FilippoToso\PdfWatermarker\Facades\TextWatermarker;
use FilippoToso\PdfWatermarker\Support\Position

TextWatermarker::input('input.pdf')
    ->output('laravel-text.pdf')
    ->position(Position::BOTTOM_CENTER, -50, -10)
    ->asBackground()
    ->pageRange(3, 4)
    ->text('Hello World')
    ->angle(25)
    ->font('arial.ttf')
    ->size('25')
    ->color('#CC00007F')
    ->save();
```

You can also generate a valid response to stream or download the file:

``` php
use FilippoToso\PdfWatermarker\Facades\ImageWatermarker;

// Within a controller action
return ImageWatermarker::input('input.pdf')
        ->watermark('watermark.png')
        ->download('example.pdf');
```

``` php
use FilippoToso\PdfWatermarker\Facades\ImageWatermarker;

// Within a controller action
return ImageWatermarker::input('input.pdf')
        ->watermark('watermark.png')
        ->stream('example.pdf');
```