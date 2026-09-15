<?php

declare(strict_types=1);

namespace emirustaoglu\Barcode;

use emirustaoglu\Barcode\Legacy\BarcodeGenerator;
use InvalidArgumentException;

final class BarcodeService
{
    private BarcodeGenerator $generator;

    public function __construct(?BarcodeGenerator $generator = null)
    {
        $this->generator = $generator ?? new BarcodeGenerator();
    }

    public function generate(BarcodeType|string $type, string $data, ?BarcodeOptions $options = null): BarcodeResult
    {
        $type = $this->normalizeType($type);
        $options ??= BarcodeOptions::defaults();

        $format = $options->format;
        if ($format !== BarcodeFormat::SVG && !extension_loaded('gd')) {
            throw new \RuntimeException('PNG/JPEG/GIF üretmek için PHP GD extension gereklidir. SVG için GD gerekmez.');
        }

        $data = trim($data);
        if ($data === '') {
            throw new InvalidArgumentException('Barkod verisi boş olamaz.');
        }

        $legacyOptions = $options->toLegacyArray();
        $legacyOptions['RakamYaz'] = $options->showText ? '0' : '1';

        return match ($format) {
            BarcodeFormat::SVG => new BarcodeResult(
                $type,
                $format,
                $data,
                $this->generator->render_svg($type->value, $data, $legacyOptions, $legacyOptions['RakamYaz']),
                'image/svg+xml'
            ),
            BarcodeFormat::PNG => $this->renderRaster($type, $data, $legacyOptions, $format),
            BarcodeFormat::JPEG => $this->renderRaster($type, $data, $legacyOptions, $format),
            BarcodeFormat::GIF => $this->renderRaster($type, $data, $legacyOptions, $format),
        };
    }

    public function qr(string $data, ?BarcodeOptions $options = null): BarcodeResult
    {
        return $this->generate(BarcodeType::QR, $data, $options);
    }

    public function ean13(string $data, ?BarcodeOptions $options = null): BarcodeResult
    {
        return $this->generate(BarcodeType::EAN_13, $data, $options);
    }

    public function ean8(string $data, ?BarcodeOptions $options = null): BarcodeResult
    {
        return $this->generate(BarcodeType::EAN_8, $data, $options);
    }

    public function code128(string $data, ?BarcodeOptions $options = null): BarcodeResult
    {
        return $this->generate(BarcodeType::CODE_128, $data, $options);
    }

    public function dataMatrix(string $data, ?BarcodeOptions $options = null): BarcodeResult
    {
        return $this->generate(BarcodeType::DATA_MATRIX, $data, $options);
    }

    private function renderRaster(BarcodeType $type, string $data, array $legacyOptions, BarcodeFormat $format): BarcodeResult
    {
        $image = $this->generator->render_image($type->value, $data, $legacyOptions);

        if (!is_object($image) || !function_exists('imagepng')) {
            throw new \RuntimeException('GD image oluşturulamadı.');
        }

        ob_start();
        try {
            $ok = match ($format) {
                BarcodeFormat::PNG => imagepng($image),
                BarcodeFormat::JPEG => imagejpeg($image),
                BarcodeFormat::GIF => imagegif($image),
                default => false,
            };
            $content = ob_get_clean();
        } finally {
            imagedestroy($image);
        }

        if (!$ok || $content === false) {
            throw new \RuntimeException('Barkod görüntüsü üretilemedi.');
        }

        return new BarcodeResult(
            $type,
            $format,
            $data,
            $content,
            match ($format) {
                BarcodeFormat::PNG => 'image/png',
                BarcodeFormat::JPEG => 'image/jpeg',
                BarcodeFormat::GIF => 'image/gif',
                default => 'application/octet-stream',
            }
        );
    }

    private function normalizeType(BarcodeType|string $type): BarcodeType
    {
        if ($type instanceof BarcodeType) return $type;

        $normalized = strtolower(preg_replace('/[^a-z0-9]/i', '', $type) ?? '');
        foreach (BarcodeType::cases() as $case) {
            if ($case->value === $normalized) return $case;
        }

        $aliases = [
            'qrcode' => BarcodeType::QR,
            'datamatrix' => BarcodeType::DATA_MATRIX,
            'ean' => BarcodeType::EAN_13,
            'code128' => BarcodeType::CODE_128,
            'itf14' => BarcodeType::ITF_14,
        ];

        if (isset($aliases[$normalized])) return $aliases[$normalized];

        throw new InvalidArgumentException("Desteklenmeyen barkod tipi: {$type}");
    }
}
