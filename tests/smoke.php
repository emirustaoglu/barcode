<?php

declare(strict_types=1);

require dirname(__DIR__) . '/src/BarcodeFormat.php';
require dirname(__DIR__) . '/src/BarcodeType.php';
require dirname(__DIR__) . '/src/BarcodeOptions.php';
require dirname(__DIR__) . '/src/BarcodeResult.php';
require dirname(__DIR__) . '/src/Legacy/BarcodeGenerator.php';
require dirname(__DIR__) . '/src/BarcodeService.php';

use emirustaoglu\Barcode\BarcodeFormat;
use emirustaoglu\Barcode\BarcodeOptions;
use emirustaoglu\Barcode\BarcodeService;

$service = new BarcodeService();
$result = $service->qr('HELLO', new BarcodeOptions(format: BarcodeFormat::SVG, scale: 4));

if (!str_contains($result->content(), '<svg')) {
    throw new RuntimeException('SVG üretilemedi.');
}

echo "QR SVG OK\n";