<?php

declare(strict_types=1);

namespace emirustaoglu\Barcode;

final class Barcode
{
    private function __construct() {}

    public static function service(): BarcodeService
    {
        return new BarcodeService();
    }
}
