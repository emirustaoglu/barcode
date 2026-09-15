<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use emirustaoglu\Barcode\BarcodeFormat;
use emirustaoglu\Barcode\BarcodeOptions;
use emirustaoglu\Barcode\BarcodeService;

$service = new BarcodeService();

$result = $service->qr(
    $_GET['data'] ?? 'Dijital Ofisim',
    new BarcodeOptions(format: BarcodeFormat::SVG, scale: 4, padding: 8)
);

header('Content-Type: ' . $result->mimeType);
echo $result->content();
