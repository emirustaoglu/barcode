<?php

declare(strict_types=1);

namespace emirustaoglu\Barcode;

enum BarcodeFormat: string
{
    case SVG = 'svg';
    case PNG = 'png';
    case JPEG = 'jpeg';
    case GIF = 'gif';
}
