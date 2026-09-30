# Emir Ustaoglu Barcode

[Türkçe](README.tr.md) · [English](README.md)

[![Latest Version](https://img.shields.io/packagist/v/emirustaoglu/barcode.svg)](https://packagist.org/packages/emirustaoglu/barcode)
[![Monthly Downloads](https://img.shields.io/packagist/dm/emirustaoglu/barcode.svg)](https://packagist.org/packages/emirustaoglu/barcode/stats)
[![Total Downloads](https://img.shields.io/packagist/dt/emirustaoglu/barcode.svg)](https://packagist.org/packages/emirustaoglu/barcode/stats)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-777BB4.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

A lightweight PHP library for generating **QR codes and 1D/2D barcodes** without requiring a large external barcode framework.

The library provides a simple, application-friendly API for generating barcode content as **SVG, PNG, JPEG, or GIF**. It is suitable for web applications, ERP/accounting software, invoices, product labels, inventory systems, documents, URLs, identifiers, and other machine-readable data.

## Features

- QR Code generation
- QR error correction levels: L, M, Q, H
- Data Matrix generation
- GS1 Data Matrix generation
- Square and rectangular Data Matrix variants
- UPC-A / UPC-E
- EAN-13 / EAN-8
- Code 39 / Code 39 ASCII
- Code 93 / Code 93 ASCII
- Code 128 and Code 128 subsets
- EAN-128 / GS1-128 variants
- Codabar
- ITF / ITF-14
- SVG output without GD
- PNG, JPEG and GIF output through PHP GD
- Base64 and Data URI helpers
- Save generated output directly to a file
- Typed PHP API with enums and options objects
- No runtime framework dependency
- PSR-4 autoloading
- PHP 8.1+

## Installation

Install the package with Composer:

```bash
composer require emirustaoglu/barcode
```

Then load Composer's autoloader:

```php
require __DIR__ . '/vendor/autoload.php';
```

## Basic Usage

```php
use emirustaoglu\Barcode\BarcodeService;

$barcode = new BarcodeService();

$result = $barcode->qr('https://github.com/emirustaoglu');

echo $result->content();
```

SVG is the default output format and does not require PHP GD.

## QR Code

QR codes can be generated directly through the `qr()` helper:

```php
use emirustaoglu\Barcode\BarcodeFormat;
use emirustaoglu\Barcode\BarcodeOptions;
use emirustaoglu\Barcode\BarcodeService;

$barcode = new BarcodeService();

$result = $barcode->qr(
    'https://github.com/emirustaoglu/',
    new BarcodeOptions(
        format: BarcodeFormat::SVG,
        scale: 4,
        padding: 8
    )
);

echo $result->content();
```

### QR error correction

The QR encoder supports the standard four error correction levels:

- `L` — Low
- `M` — Medium
- `Q` — Quartile
- `H` — High

Use the corresponding barcode type when a specific level is required:

```php
$barcode->generate(BarcodeType::QR_L, 'Hello World');
$barcode->generate(BarcodeType::QR_M, 'Hello World');
$barcode->generate(BarcodeType::QR_Q, 'Hello World');
$barcode->generate(BarcodeType::QR_H, 'Hello World');
```

The default `QR` type uses the library's default QR error-correction configuration.

## Data Matrix

Standard Data Matrix:

```php
$result = $barcode->dataMatrix('Emir USTAOĞLU');

echo $result->content();
```

Square Data Matrix:

```php
$result = $barcode->generate(
    BarcodeType::DATA_MATRIX_SQUARE,
    'PRODUCT-123456'
);
```

Rectangular Data Matrix:

```php
$result = $barcode->generate(
    BarcodeType::DATA_MATRIX_RECTANGULAR,
    'PRODUCT-123456'
);
```

GS1 Data Matrix variants are also available:

```php
$result = $barcode->generate(
    BarcodeType::GS1_DATA_MATRIX,
    '01012345678901281725010110ABC123'
);
```

## 1D Barcodes

### EAN-13

```php
$result = $barcode->ean13('869000000001');

$result->save(__DIR__ . '/ean13.svg');
```

### EAN-8

```php
$result = $barcode->ean8('1234567');
```

### Code 128

```php
$result = $barcode->code128('EMIR-2026-00001');
```

Specific Code 128 subsets can be selected when required:

```php
$barcode->generate(BarcodeType::CODE_128_A, 'ABC123');
$barcode->generate(BarcodeType::CODE_128_B, 'ABC123');
$barcode->generate(BarcodeType::CODE_128_C, '12345678');
```

### UPC

```php
$barcode->generate(BarcodeType::UPC_A, '012345678905');
$barcode->generate(BarcodeType::UPC_E, '01234565');
```

### Code 39 / Code 93

```php
$barcode->generate(BarcodeType::CODE_39, 'ABC-123');
$barcode->generate(BarcodeType::CODE_39_ASCII, 'ABC-123');

$barcode->generate(BarcodeType::CODE_93, 'ABC123');
$barcode->generate(BarcodeType::CODE_93_ASCII, 'ABC123');
```

### Codabar

```php
$barcode->generate(BarcodeType::CODABAR, 'A123456A');
```

### ITF / ITF-14

```php
$barcode->generate(BarcodeType::ITF, '12345678');
$barcode->generate(BarcodeType::ITF_14, '1234567890123');
```

## Output Formats

The library supports four output formats:

- SVG
- PNG
- JPEG
- GIF

SVG is useful for invoices, PDFs, HTML documents, labels and other situations where scalable output is preferred.

```php
use emirustaoglu\Barcode\BarcodeFormat;
use emirustaoglu\Barcode\BarcodeOptions;

$options = new BarcodeOptions(
    format: BarcodeFormat::SVG,
    scale: 4
);

$result = $barcode->qr('Hello World', $options);
```

### PNG / JPEG / GIF

Raster formats require the PHP GD extension.

```php
$result = $barcode->qr(
    'Hello World',
    new BarcodeOptions(
        format: BarcodeFormat::PNG,
        width: 300,
        height: 300,
        padding: 10
    )
);

$result->save(__DIR__ . '/qr.png');
```

For JPEG:

```php
$result = $barcode->qr(
    'Hello World',
    new BarcodeOptions(
        format: BarcodeFormat::JPEG
    )
);

$result->save(__DIR__ . '/qr.jpg');
```

For GIF:

```php
$result = $barcode->qr(
    'Hello World',
    new BarcodeOptions(
        format: BarcodeFormat::GIF
    )
);

$result->save(__DIR__ . '/qr.gif');
```

## Working With the Result

Every generation method returns a `BarcodeResult`.

```php
$result = $barcode->qr('https://github.com/emirustaoglu');
```

Get the generated content:

```php
echo $result->content();
```

Get Base64:

```php
$base64 = $result->base64();
```

Get a Data URI:

```php
$dataUri = $result->dataUri();
```

This can be used directly in HTML:

```php
<img src="<?= htmlspecialchars($result->dataUri(), ENT_QUOTES, 'UTF-8') ?>" alt="QR Code">
```

Save to a file:

```php
$result->save(__DIR__ . '/barcode.svg');
```

The result also exposes:

```php
$result->type;
$result->format;
$result->data;
$result->mimeType;
```

## Options

Generation options are provided through `BarcodeOptions`.

```php
$options = new BarcodeOptions(
    format: BarcodeFormat::SVG,
    scale: 4,
    padding: 8,
    showText: true
);

$result = $barcode->qr('123456789', $options);
```

The API keeps common settings simple while still allowing access to lower-level renderer options when necessary.

## Generic API

Instead of using convenience methods such as `qr()` or `ean13()`, any supported type can be generated through `generate()`:

```php
use emirustaoglu\Barcode\BarcodeService;
use emirustaoglu\Barcode\BarcodeType;

$barcode = new BarcodeService();

$result = $barcode->generate(
    BarcodeType::CODE_128,
    'ABC123456'
);
```

String type names are also accepted:

```php
$result = $barcode->generate(
    'code128',
    'ABC123456'
);
```

## Supported Barcode Types

| Type | Description |
|---|---|
| `upca` | UPC-A |
| `upce` | UPC-E |
| `ean13` | EAN-13 |
| `ean13nopad` | EAN-13 without padding variant |
| `ean13pad` | EAN-13 padded variant |
| `ean8` | EAN-8 |
| `code39` | Code 39 |
| `code39ascii` | Code 39 ASCII |
| `code93` | Code 93 |
| `code93ascii` | Code 93 ASCII |
| `code128` | Code 128 |
| `code128a` | Code 128 A |
| `code128b` | Code 128 B |
| `code128c` | Code 128 C |
| `code128ac` | Code 128 A/C |
| `code128bc` | Code 128 B/C |
| `ean128` | EAN-128 / GS1-128 |
| `ean128a` | EAN-128 A |
| `ean128b` | EAN-128 B |
| `ean128c` | EAN-128 C |
| `ean128ac` | EAN-128 A/C |
| `ean128bc` | EAN-128 B/C |
| `codabar` | Codabar |
| `itf` | Interleaved 2 of 5 |
| `itf14` | ITF-14 |
| `qr` | QR Code |
| `qrl` | QR Code — Low error correction |
| `qrm` | QR Code — Medium error correction |
| `qrq` | QR Code — Quartile error correction |
| `qrh` | QR Code — High error correction |
| `dmtx` | Data Matrix |
| `dmtxs` | Data Matrix — Square |
| `dmtxr` | Data Matrix — Rectangular |
| `gs1dmtx` | GS1 Data Matrix |
| `gs1dmtxs` | GS1 Data Matrix — Square |
| `gs1dmtxr` | GS1 Data Matrix — Rectangular |

## Web Response Example

The library does not require a framework. It can be used directly from a controller, route or PHP endpoint.

For example:

```php
use emirustaoglu\Barcode\BarcodeFormat;
use emirustaoglu\Barcode\BarcodeService;

$barcode = new BarcodeService();
$result = $barcode->qr('https://github.com/emirustaoglu');

header('Content-Type: ' . $result->mimeType);
echo $result->content();
```

The same result can instead be saved, embedded as Base64, returned from an API response, or passed to another document-generation process.

## Requirements

- PHP 8.1 or newer
- Composer
- PHP GD extension for PNG, JPEG and GIF output

SVG generation does not require GD.

## Design Goals

The package is intentionally small and focused on barcode generation. It does not attempt to become a complete document, image or PDF framework.

The public API is designed around a few simple concepts:

- `BarcodeService` — main generation service
- `BarcodeType` — supported symbologies
- `BarcodeFormat` — output format
- `BarcodeOptions` — generation and rendering options
- `BarcodeResult` — generated output and related helpers

This keeps barcode generation independent from the application's HTTP layer, framework, routing system or document-generation code.

## License

This project is released under the MIT License.

The barcode encoder implementation includes code originally distributed under the MIT License by **Kreative Software**, Copyright (c) 2016-2018 Kreative Software. The original copyright and license notice is retained in the relevant source file.

See the source distribution for the complete license text.

Modern refactoring, package architecture, public API design,
documentation, integration work, and enhancements were carried out
by Yusuf Emir USTAOĞLU.
