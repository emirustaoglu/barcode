<?php

declare(strict_types=1);

namespace emirustaoglu\Barcode;

final class BarcodeOptions
{
    public function __construct(
        public readonly BarcodeFormat $format = BarcodeFormat::SVG,
        public readonly ?int $width = null,
        public readonly ?int $height = null,
        public readonly ?float $scale = null,
        public readonly ?float $scaleX = null,
        public readonly ?float $scaleY = null,
        public readonly ?int $padding = null,
        public readonly ?int $paddingTop = null,
        public readonly ?int $paddingRight = null,
        public readonly ?int $paddingBottom = null,
        public readonly ?int $paddingLeft = null,
        public readonly string $background = 'FFFFFF',
        public readonly string $foreground = '000000',
        public readonly bool $showText = true,
        public readonly int $textSize = 10,
        public readonly int $textHeight = 10,
        public readonly string $textColor = '000000',
        public readonly string $textFont = 'monospace',
        public readonly array $moduleWidths = [],
        public readonly string $matrixShape = '',
        public readonly array $legacy = [],
    ) {
        if ($this->width !== null && $this->width <= 0) {
            throw new \InvalidArgumentException('width 0\dan büyük olmalıdır.');
        }
        if ($this->height !== null && $this->height <= 0) {
            throw new \InvalidArgumentException('height 0\dan büyük olmalıdır.');
        }
        if (($this->scale !== null && $this->scale <= 0) || ($this->scaleX !== null && $this->scaleX <= 0) || ($this->scaleY !== null && $this->scaleY <= 0)) {
            throw new \InvalidArgumentException('scale değerleri 0\dan büyük olmalıdır.');
        }
        foreach ([$this->padding, $this->paddingTop, $this->paddingRight, $this->paddingBottom, $this->paddingLeft] as $padding) {
            if ($padding !== null && $padding < 0) {
                throw new \InvalidArgumentException('padding negatif olamaz.');
            }
        }
    }

    public static function defaults(): self
    {
        return new self();
    }

    public function with(array $changes): self
    {
        return new self(
            format: $changes['format'] ?? $this->format,
            width: $changes['width'] ?? $this->width,
            height: $changes['height'] ?? $this->height,
            scale: $changes['scale'] ?? $this->scale,
            scaleX: $changes['scaleX'] ?? $this->scaleX,
            scaleY: $changes['scaleY'] ?? $this->scaleY,
            padding: $changes['padding'] ?? $this->padding,
            paddingTop: $changes['paddingTop'] ?? $this->paddingTop,
            paddingRight: $changes['paddingRight'] ?? $this->paddingRight,
            paddingBottom: $changes['paddingBottom'] ?? $this->paddingBottom,
            paddingLeft: $changes['paddingLeft'] ?? $this->paddingLeft,
            background: $changes['background'] ?? $this->background,
            foreground: $changes['foreground'] ?? $this->foreground,
            showText: $changes['showText'] ?? $this->showText,
            textSize: $changes['textSize'] ?? $this->textSize,
            textHeight: $changes['textHeight'] ?? $this->textHeight,
            textColor: $changes['textColor'] ?? $this->textColor,
            textFont: $changes['textFont'] ?? $this->textFont,
            moduleWidths: $changes['moduleWidths'] ?? $this->moduleWidths,
            matrixShape: $changes['matrixShape'] ?? $this->matrixShape,
            legacy: $changes['legacy'] ?? $this->legacy,
        );
    }

    public function toLegacyArray(): array
    {
        $options = $this->legacy;

        if ($this->scale !== null) $options['sf'] = $this->scale;
        if ($this->scaleX !== null) $options['sx'] = $this->scaleX;
        if ($this->scaleY !== null) $options['sy'] = $this->scaleY;

        if ($this->width !== null) $options['w'] = $this->width;
        if ($this->height !== null) $options['h'] = $this->height;

        if ($this->padding !== null) $options['p'] = $this->padding;
        if ($this->paddingTop !== null) $options['pt'] = $this->paddingTop;
        if ($this->paddingRight !== null) $options['pr'] = $this->paddingRight;
        if ($this->paddingBottom !== null) $options['pb'] = $this->paddingBottom;
        if ($this->paddingLeft !== null) $options['pl'] = $this->paddingLeft;

        $options['bc'] = $this->background;
        $options['cm'] = $this->foreground;
        $options['ts'] = $this->textSize;
        $options['th'] = $this->textHeight;
        $options['tc'] = $this->textColor;
        $options['tf'] = $this->textFont;

        if ($this->matrixShape !== '') {
            $options['ms'] = $this->matrixShape;
        }

        foreach ($this->moduleWidths as $key => $value) {
            $map = [0 => 'wq', 1 => 'wm', 2 => 'ww', 3 => 'wn', 4 => 'w4', 5 => 'w5', 6 => 'w6', 7 => 'w7', 8 => 'w8', 9 => 'w9'];
            if (isset($map[$key])) $options[$map[$key]] = (int)$value;
        }

        return $options;
    }

    /**
     * Escape hatch for features present in the original implementation but
     * not promoted to the public API yet.
     */
    public function legacyOption(string $key, mixed $value): self
    {
        $legacy = $this->legacy;
        $legacy[$key] = $value;
        return $this->with(['legacy' => $legacy]);
    }
}
