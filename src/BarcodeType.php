<?php

declare(strict_types=1);

namespace emirustaoglu\Barcode;

enum BarcodeType: string
{
    case UPC_A = 'upca';
    case UPC_E = 'upce';
    case EAN_13 = 'ean13';
    case EAN_13_NO_PAD = 'ean13nopad';
    case EAN_13_PAD = 'ean13pad';
    case EAN_8 = 'ean8';
    case CODE_39 = 'code39';
    case CODE_39_ASCII = 'code39ascii';
    case CODE_93 = 'code93';
    case CODE_93_ASCII = 'code93ascii';
    case CODE_128 = 'code128';
    case CODE_128_A = 'code128a';
    case CODE_128_B = 'code128b';
    case CODE_128_C = 'code128c';
    case CODE_128_AC = 'code128ac';
    case CODE_128_BC = 'code128bc';
    case EAN_128 = 'ean128';
    case EAN_128_A = 'ean128a';
    case EAN_128_B = 'ean128b';
    case EAN_128_C = 'ean128c';
    case EAN_128_AC = 'ean128ac';
    case EAN_128_BC = 'ean128bc';
    case CODABAR = 'codabar';
    case ITF = 'itf';
    case ITF_14 = 'itf14';
    case QR = 'qr';
    case QR_L = 'qrl';
    case QR_M = 'qrm';
    case QR_Q = 'qrq';
    case QR_H = 'qrh';
    case DATA_MATRIX = 'dmtx';
    case DATA_MATRIX_SQUARE = 'dmtxs';
    case DATA_MATRIX_RECTANGULAR = 'dmtxr';
    case GS1_DATA_MATRIX = 'gs1dmtx';
    case GS1_DATA_MATRIX_SQUARE = 'gs1dmtxs';
    case GS1_DATA_MATRIX_RECTANGULAR = 'gs1dmtxr';

    public function isMatrix(): bool
    {
        return match ($this) {
            self::QR, self::QR_L, self::QR_M, self::QR_Q, self::QR_H,
            self::DATA_MATRIX, self::DATA_MATRIX_SQUARE, self::DATA_MATRIX_RECTANGULAR,
            self::GS1_DATA_MATRIX, self::GS1_DATA_MATRIX_SQUARE, self::GS1_DATA_MATRIX_RECTANGULAR => true,
            default => false,
        };
    }
}
