<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use emirustaoglu\Barcode\BarcodeFormat;
use emirustaoglu\Barcode\BarcodeOptions;
use emirustaoglu\Barcode\BarcodeService;
use emirustaoglu\Barcode\BarcodeType;

$barcode = new BarcodeService();

// 1) QR -> SVG (GD gerekmez)
$qr = $barcode->qr(
    'https://dijitalofisim.com.tr',
    new BarcodeOptions(
        format: BarcodeFormat::SVG,
        scale: 4,
        padding: 12,
        foreground: '000000',
        background: 'FFFFFF'
    )
);

$qr->save(__DIR__ . '/qr.svg');

echo $qr->dataUri() . PHP_EOL;

// 2) EAN-13 -> PNG (GD gerekir)
$ean = $barcode->ean13(
    '869000000001',
    new BarcodeOptions(
        format: BarcodeFormat::PNG,
        width: 400,
        height: 120,
        padding: 10,
        showText: true
    )
);

$ean->save(__DIR__ . '/ean13.png');

// 3) Generic API
$result = $barcode->generate(
    BarcodeType::CODE_128,
    'LEZIZA-2026-00001',
    new BarcodeOptions(format: BarcodeFormat::SVG, showText: true)
);
$result->save(__DIR__ . '/code128.svg');
