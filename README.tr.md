# Emir Ustaoglu Barcode

[Türkçe](README.tr.md) · [English](README.md)

[![Latest Version](https://img.shields.io/packagist/v/emirustaoglu/barcode.svg)](https://packagist.org/packages/emirustaoglu/barcode)
[![Monthly Downloads](https://img.shields.io/packagist/dm/emirustaoglu/barcode.svg)](https://packagist.org/packages/emirustaoglu/barcode/stats)
[![Total Downloads](https://img.shields.io/packagist/dt/emirustaoglu/barcode.svg)](https://packagist.org/packages/emirustaoglu/barcode/stats)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-777BB4.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

PHP 8.1+ için geliştirilmiş, hafif ve bağımsız bir barkod üretim kütüphanesi.

1D ve 2D barkodlar, QR kodları ve Data Matrix formatları için modern, tip güvenli ve framework bağımsız bir PHP API sunar.

---

## Özellikler

- QR Code desteği
- QR Code hata düzeltme seviyeleri
- Data Matrix desteği
- GS1 Data Matrix desteği
- UPC-A / UPC-E
- EAN-8 / EAN-13
- Code 39 / Code 93 / Code 128
- EAN-128 / GS1-128 varyantları
- Codabar
- ITF / ITF-14
- SVG çıktısı
- PNG çıktısı
- JPEG çıktısı
- GIF çıktısı
- PHP Enum tabanlı barkod tipleri
- Tip güvenli seçenekler
- Framework bağımsız kullanım
- Composer desteği
- Üretilen çıktıyı dosyaya kaydetme
- Base64 ve Data URI çıktıları
- Mevcut barkod encoder altyapısının korunması

---

## Kurulum

Composer üzerinden kurabilirsiniz:

```bash
composer require emirustaoglu/barcode
```

Ardından Composer autoload dosyasını dahil edin:

```php
require __DIR__ . '/vendor/autoload.php';
```

---

## Gereksinimler

- PHP 8.1 veya üzeri
- Composer (kurulum için)

PNG, JPEG ve GIF çıktıları için PHP GD extension gereklidir.

SVG çıktısı için GD gerekmez.

---

## Hızlı Başlangıç

En basit kullanım:

```php
use emirustaoglu\Barcode\BarcodeService;

$barcode = new BarcodeService();

$result = $barcode->qr('https://example.com');

echo $result->content();
```

Varsayılan çıktı formatı SVG'dir.

SVG çıktısını doğrudan tarayıcıya gönderebilirsiniz:

```php
header('Content-Type: image/svg+xml');
echo $result->content();
```

---

## Barkod Tipini Belirterek Kullanım

`BarcodeType` enum'ını kullanarak barkod tipini açıkça belirtebilirsiniz:

```php
use emirustaoglu\Barcode\BarcodeService;
use emirustaoglu\Barcode\BarcodeType;

$barcode = new BarcodeService();

$result = $barcode->generate(
    BarcodeType::CODE_128,
    '123456789'
);

echo $result->content();
```

String değer de kullanılabilir:

```php
$result = $barcode->generate(
    'code128',
    '123456789'
);
```

---

## QR Code

QR Code oluşturmak için:

```php
$result = $barcode->qr('Merhaba Dünya');

echo $result->content();
```

URL için:

```php
$result = $barcode->qr('https://example.com');

echo $result->content();
```

### QR Code Hata Düzeltme Seviyeleri

QR Code için farklı hata düzeltme seviyeleri desteklenir:

```php
use emirustaoglu\Barcode\BarcodeType;

$low = $barcode->generate(
    BarcodeType::QR_L,
    'Merhaba Dünya'
);

$medium = $barcode->generate(
    BarcodeType::QR_M,
    'Merhaba Dünya'
);

$quartile = $barcode->generate(
    BarcodeType::QR_Q,
    'Merhaba Dünya'
);

$high = $barcode->generate(
    BarcodeType::QR_H,
    'Merhaba Dünya'
);
```

Desteklenen seviyeler:

| Enum | QR Seviyesi |
|---|---|
| `QR_L` | Low |
| `QR_M` | Medium |
| `QR_Q` | Quartile |
| `QR_H` | High |

Hata düzeltme seviyesi yükseldikçe QR kodun bir kısmı zarar görse bile verinin okunabilme ihtimali artar. Bunun karşılığında aynı veri için daha büyük bir QR matrisi oluşabilir.

---

## EAN-13

```php
$result = $barcode->ean13('869000000001');

echo $result->content();
```

Enum ile:

```php
$result = $barcode->generate(
    BarcodeType::EAN_13,
    '869000000001'
);
```

---

## EAN-8

```php
$result = $barcode->ean8('1234567');

echo $result->content();
```

---

## Code 128

```php
$result = $barcode->code128('ABC123456');

echo $result->content();
```

---

## Data Matrix

Data Matrix oluşturmak için:

```php
$result = $barcode->dataMatrix('Merhaba Dünya');

echo $result->content();
```

Enum ile:

```php
$result = $barcode->generate(
    BarcodeType::DATA_MATRIX,
    'Merhaba Dünya'
);
```

---

## GS1 Data Matrix

GS1 Data Matrix varyantları da desteklenmektedir:

```php
use emirustaoglu\Barcode\BarcodeType;

$result = $barcode->generate(
    BarcodeType::GS1_DATA_MATRIX,
    '010123456789012817250101'
);

echo $result->content();
```

Kare ve dikdörtgen varyantları da kullanılabilir:

```php
BarcodeType::GS1_DATA_MATRIX
BarcodeType::GS1_DATA_MATRIX_SQUARE
BarcodeType::GS1_DATA_MATRIX_RECTANGULAR
```

---

## Barkod Seçenekleri

Barkod üretirken `BarcodeOptions` kullanabilirsiniz:

```php
use emirustaoglu\Barcode\BarcodeOptions;

$options = new BarcodeOptions();

$result = $barcode->generate(
    BarcodeType::CODE_128,
    '123456789',
    $options
);
```

Örneğin çıktı formatını belirlemek:

```php
use emirustaoglu\Barcode\BarcodeFormat;
use emirustaoglu\Barcode\BarcodeOptions;

$options = new BarcodeOptions(
    format: BarcodeFormat::SVG
);

$result = $barcode->generate(
    BarcodeType::CODE_128,
    '123456789',
    $options
);
```

---

## SVG Çıktısı

SVG varsayılan çıktı formatıdır ve PHP GD gerektirmez.

```php
$result = $barcode->generate(
    BarcodeType::QR,
    'https://example.com'
);

echo $result->content();
```

Dosyaya kaydetmek için:

```php
$result->save(__DIR__ . '/barcode.svg');
```

---

## PNG Çıktısı

PNG üretmek için PHP GD extension'ın aktif olması gerekir.

```php
use emirustaoglu\Barcode\BarcodeFormat;
use emirustaoglu\Barcode\BarcodeOptions;

$options = new BarcodeOptions(
    format: BarcodeFormat::PNG
);

$result = $barcode->generate(
    BarcodeType::CODE_128,
    '123456789',
    $options
);

$result->save(__DIR__ . '/barcode.png');
```

Tarayıcıya göndermek için:

```php
header('Content-Type: image/png');

echo $result->content();
```

---

## JPEG Çıktısı

```php
$options = new BarcodeOptions(
    format: BarcodeFormat::JPEG
);

$result = $barcode->generate(
    BarcodeType::CODE_128,
    '123456789',
    $options
);

$result->save(__DIR__ . '/barcode.jpg');
```

---

## GIF Çıktısı

```php
$options = new BarcodeOptions(
    format: BarcodeFormat::GIF
);

$result = $barcode->generate(
    BarcodeType::CODE_128,
    '123456789',
    $options
);

$result->save(__DIR__ . '/barcode.gif');
```

---

## BarcodeResult

`generate()` metodu doğrudan ham çıktı yerine bir `BarcodeResult` nesnesi döndürür.

Bu nesne üzerinden barkod hakkında bilgi alabilir veya üretilen çıktıyı farklı şekillerde kullanabilirsiniz.

```php
$result = $barcode->qr('Merhaba Dünya');
```

### Çıktıyı Alma

```php
$content = $result->content();
```

SVG için bu değer metinsel SVG içeriğidir.

PNG, JPEG veya GIF için ise binary görüntü verisidir.

### Base64

```php
$base64 = $result->base64();
```

### Data URI

```php
$dataUri = $result->dataUri();
```

Örneğin HTML içerisinde doğrudan kullanılabilir:

```php
echo '<img src="' . $result->dataUri() . '" alt="Barkod">';
```

### Dosyaya Kaydetme

```php
$result->save(__DIR__ . '/barcode.svg');
```

`save()` metodu `BarcodeResult` nesnesini tekrar döndürür.

---

## Genel API Kullanımı

Tüm barkod tipleri için ortak API kullanılabilir:

```php
use emirustaoglu\Barcode\BarcodeService;
use emirustaoglu\Barcode\BarcodeType;

$barcode = new BarcodeService();

$result = $barcode->generate(
    BarcodeType::CODE_128,
    'ABC123456'
);

echo $result->content();
```

Bu yaklaşım, uygulamanızdaki barkod tipini çalışma zamanında belirlemeniz gerektiğinde özellikle kullanışlıdır.

Örneğin:

```php
$type = BarcodeType::EAN_13;

$result = $barcode->generate(
    $type,
    '869000000001'
);
```

---

## Desteklenen Barkod Tipleri

| Enum | Değer | Açıklama |
|---|---|---|
| `UPC_A` | `upca` | UPC-A |
| `UPC_E` | `upce` | UPC-E |
| `EAN_13` | `ean13` | EAN-13 |
| `EAN_13_NO_PAD` | `ean13nopad` | EAN-13 No Pad |
| `EAN_13_PAD` | `ean13pad` | EAN-13 Pad |
| `EAN_8` | `ean8` | EAN-8 |
| `CODE_39` | `code39` | Code 39 |
| `CODE_39_ASCII` | `code39ascii` | Code 39 ASCII |
| `CODE_93` | `code93` | Code 93 |
| `CODE_93_ASCII` | `code93ascii` | Code 93 ASCII |
| `CODE_128` | `code128` | Code 128 |
| `CODE_128_A` | `code128a` | Code 128 A |
| `CODE_128_B` | `code128b` | Code 128 B |
| `CODE_128_C` | `code128c` | Code 128 C |
| `CODE_128_AC` | `code128ac` | Code 128 A/C |
| `CODE_128_BC` | `code128bc` | Code 128 B/C |
| `EAN_128` | `ean128` | EAN-128 |
| `EAN_128_A` | `ean128a` | EAN-128 A |
| `EAN_128_B` | `ean128b` | EAN-128 B |
| `EAN_128_C` | `ean128c` | EAN-128 C |
| `EAN_128_AC` | `ean128ac` | EAN-128 A/C |
| `EAN_128_BC` | `ean128bc` | EAN-128 B/C |
| `CODABAR` | `codabar` | Codabar |
| `ITF` | `itf` | ITF |
| `ITF_14` | `itf14` | ITF-14 |
| `QR` | `qr` | QR Code |
| `QR_L` | `qrl` | QR Code - Low |
| `QR_M` | `qrm` | QR Code - Medium |
| `QR_Q` | `qrq` | QR Code - Quartile |
| `QR_H` | `qrh` | QR Code - High |
| `DATA_MATRIX` | `dmtx` | Data Matrix |
| `DATA_MATRIX_SQUARE` | `dmtxs` | Kare Data Matrix |
| `DATA_MATRIX_RECTANGULAR` | `dmtxr` | Dikdörtgen Data Matrix |
| `GS1_DATA_MATRIX` | `gs1dmtx` | GS1 Data Matrix |
| `GS1_DATA_MATRIX_SQUARE` | `gs1dmtxs` | Kare GS1 Data Matrix |
| `GS1_DATA_MATRIX_RECTANGULAR` | `gs1dmtxr` | Dikdörtgen GS1 Data Matrix |

---

## Web Uygulamasında Kullanım

Örneğin bir QR kodu HTTP response olarak gönderebilirsiniz:

```php
use emirustaoglu\Barcode\BarcodeService;
use emirustaoglu\Barcode\BarcodeType;

$barcode = new BarcodeService();

$result = $barcode->generate(
    BarcodeType::QR,
    'https://example.com'
);

header('Content-Type: ' . $result->mimeType);

echo $result->content();
```

Aynı yaklaşım PNG, JPEG ve GIF çıktıları için de kullanılabilir.

---

## Tasarım

Bu kütüphane özellikle aşağıdaki prensipler doğrultusunda tasarlanmıştır:

### Framework Bağımsız

Laravel, Symfony veya başka bir framework'e bağımlı değildir.

PHP uygulamanızın herhangi bir bölümünde doğrudan kullanılabilir.

### Tip Güvenli API

Barkod tipleri ve çıktı formatları PHP Enum'ları ile tanımlanmıştır:

```php
BarcodeType::QR
BarcodeType::CODE_128
BarcodeType::EAN_13

BarcodeFormat::SVG
BarcodeFormat::PNG
BarcodeFormat::JPEG
BarcodeFormat::GIF
```

Bu sayede string değerleri uygulamanızın her yerine yaymak yerine merkezi ve tip güvenli bir API kullanabilirsiniz.

### Encoder ve Renderer Ayrımı

Kütüphanenin temel barkod üretim algoritmaları ile çıktı formatı birbirinden ayrılmıştır.

Böylece aynı barkod verisi SVG, PNG, JPEG veya GIF olarak üretilebilir.

### HTTP Bağımsız

Kütüphanenin çekirdek API'si:

- `$_GET`
- `header()`
- `echo`
- `exit`

gibi HTTP ortamına özel işlemlere bağlı değildir.

Bu nedenle CLI uygulamalarında, API'lerde, queue worker'larda, cron görevlerinde veya klasik web uygulamalarında kullanılabilir.

---

## Proje Yapısı

```text
emirustaoglu-barcode/
├── composer.json
├── README.md
├── README.tr.md
├── LICENSE
├── src/
│   ├── Barcode.php
│   ├── BarcodeFormat.php
│   ├── BarcodeOptions.php
│   ├── BarcodeResult.php
│   ├── BarcodeService.php
│   └── Legacy/
│       └── BarcodeGenerator.php
├── examples/
│   ├── basic.php
│   └── http.php
└── tests/
    └── smoke.php
```

`Legacy/BarcodeGenerator.php`, mevcut barkod encoder altyapısının korunduğu bölümdür.

Üst seviyedeki sınıflar ise daha modern, temiz ve Composer uyumlu bir PHP API sağlar.

---

## Lisans ve Atıf

Bu proje MIT License ile dağıtılmaktadır.

Barkod encoder altyapısının bir bölümü, MIT License altında yayımlanmış olan Kreative Software tarafından geliştirilmiş çalışmalara dayanmaktadır.

Orijinal lisans ve telif bildirimleri ilgili kaynak dosyalarda korunmuştur.

Modern paket mimarisi, public API tasarımı, refactoring, dokümantasyon, entegrasyon çalışmaları ve geliştirmeler Yusuf Emir USTAOĞLU tarafından gerçekleştirilmiştir.

Detaylar için:

```text
LICENSE
```

dosyasına bakabilirsiniz.

---

## Geliştirme

Projeyi klonladıktan sonra bağımlılıkları yükleyin:

```bash
composer install
```

Syntax kontrolü için:

```bash
php -l src/Barcode.php
php -l src/BarcodeService.php
```

Basit smoke testlerini çalıştırmak için:

```bash
php tests/smoke.php
```

---

## Katkıda Bulunma

Katkılar, hata bildirimleri ve geliştirme önerileri memnuniyetle karşılanır.

Özellikle:

- yeni barkod formatları,
- renderer geliştirmeleri,
- test kapsamının artırılması,
- PHP sürüm uyumluluğu,
- performans iyileştirmeleri

gibi katkılar değerlidir.

---

## Lisans

MIT License.

Copyright (c) 2016-2018 Kreative Software

Copyright (c) 2026 Yusuf Emir USTAOĞLU

Daha fazla bilgi için [`LICENSE`](LICENSE) dosyasına bakabilirsiniz.

---

## Dil

Bu dokümantasyonun İngilizce sürümü için:

[README.md](README.md)

Türkçe sürüm:

[README.tr.md](README.tr.md)
