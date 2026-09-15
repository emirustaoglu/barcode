<?php

declare(strict_types=1);

namespace emirustaoglu\Barcode;

final class BarcodeResult
{
    public function __construct(
        public readonly BarcodeType $type,
        public readonly BarcodeFormat $format,
        public readonly string $data,
        private readonly string $content,
        public readonly string $mimeType,
    ) {}

    public function content(): string
    {
        return $this->content;
    }

    public function base64(): string
    {
        return base64_encode($this->content);
    }

    public function dataUri(): string
    {
        return 'data:' . $this->mimeType . ';base64,' . $this->base64();
    }

    public function save(string $path): self
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            throw new \RuntimeException("Dizin bulunamadı: {$dir}");
        }
        if (file_put_contents($path, $this->content) === false) {
            throw new \RuntimeException("Barkod dosyası yazılamadı: {$path}");
        }
        return $this;
    }
}
