# Excel Export Fix

## Masalah
Error: `Interface "Maatwebsite\Excel\Concerns\FromCollection" not found`

## Penyebab
Package `maatwebsite/excel` versi lama (v1.1.5) terinstall, yang tidak kompatibel dengan Laravel 10 dan PHP 8.1.

## Solusi yang Diterapkan

### 1. Update Package
- Hapus package lama: `composer remove maatwebsite/excel`
- Install versi baru: `composer require "maatwebsite/excel:^3.1.48" --ignore-platform-req=ext-gd --ignore-platform-req=ext-zip`

### 2. Perbaiki Export Class
File: `app/Exports/LaporanKeuanganExport.php`
- Tambahkan implements interface yang diperlukan:
  - `FromCollection`
  - `WithHeadings`
  - `WithTitle`
  - `WithStyles`
- Update type hints untuk method `collection()` dan `styles()`

### 3. Publish Config
```cmd
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
```

### 4. Clear Cache
```cmd
php artisan config:clear
php artisan cache:clear
```

## Verifikasi
- ✓ Semua test berhasil (10/10 LaporanKeuanganExportTest)
- ✓ Tidak ada error diagnostik
- ✓ Interface Laravel Excel terdeteksi dengan benar

## Catatan
Extension GD dan ZIP di-ignore saat instalasi karena tidak aktif di PHP. Jika ingin menggunakan fitur manipulasi gambar di Excel, aktifkan extension tersebut di `php.ini`.
