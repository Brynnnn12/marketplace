# Fitur Download File Produk Digital

## Overview

Setelah pembayaran berhasil, user dapat mengakses dan mendownload file produk digital yang telah dibeli.

## Implementasi

### 1. Controller Download

-   **File:** `app/Http/Controllers/DownloadController.php`
-   **Method:**
    -   `downloadProduct(Order $order, Product $product)` - Download file individual
    -   `downloadAllOrderFiles(Order $order)` - Download semua file dalam order (ZIP jika > 1 file)

### 2. Routes Download

```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/download/{order}/{product}', [DownloadController::class, 'downloadProduct'])
        ->name('download.product');
    Route::get('/download/{order}/all', [DownloadController::class, 'downloadAllOrderFiles'])
        ->name('download.order');
});
```

### 3. Security Features

-   **Authentication Required:** User harus login
-   **Order Ownership:** User hanya bisa download file dari order mereka sendiri
-   **Payment Verification:** File hanya bisa didownload jika order status = 'paid'
-   **Product Verification:** Produk harus ada dalam order tersebut
-   **File Existence:** File harus ada di storage

## User Experience

### Halaman Success Payment

-   Setiap produk dengan file menampilkan tombol download individual
-   Tombol "Download Semua File" untuk download semua file sekaligus
-   Visual indicator:
    -   ✅ Hijau: File siap didownload (status paid)
    -   🔒 Abu-abu: File akan tersedia setelah pembayaran dikonfirmasi

### Dashboard User

-   Order history menampilkan tombol "Download File" untuk order yang sudah paid
-   Link langsung ke semua file dalam order

## Fitur Teknis

### Single File Download

-   Direct file download dengan nama yang user-friendly
-   Format: `{product_name}.{extension}`
-   MIME type detection otomatis

### Multiple Files Download

-   Otomatis create ZIP file temporary
-   Format ZIP: `order-{invoice_number}-files.zip`
-   Cleanup otomatis setelah download
-   Nama file dalam ZIP: `{product_name}.{extension}`

### Error Handling

-   **403 Unauthorized:** User tidak memiliki akses
-   **403 Payment Required:** Order belum dibayar
-   **404 Not Found:** File atau produk tidak ditemukan
-   **500 Server Error:** Gagal create ZIP

## Testing

### Test Download Individual File:

1. Login sebagai user
2. Beli produk yang memiliki file
3. Complete pembayaran
4. Di halaman success, klik tombol download pada produk
5. File harus terdownload dengan nama sesuai produk

### Test Download Multiple Files:

1. Login sebagai user
2. Beli beberapa produk dengan file dalam satu order
3. Complete pembayaran
4. Di halaman success, klik "Download Semua File"
5. ZIP file harus terdownload berisi semua file produk

### Test Security:

1. Coba akses URL download dari user lain → Should get 403
2. Coba download dari order belum bayar → Should get 403
3. Coba akses file yang tidak ada → Should get 404

## File Structure

```
storage/
├── app/
│   ├── products/
│   │   └── files/          # Original uploaded files
│   └── temp/              # Temporary ZIP files (auto-cleanup)
```

## Konfigurasi

### Storage Disk

Files disimpan menggunakan Laravel default storage (`storage/app/`).

### ZIP Requirements

Pastikan PHP ZipArchive extension terinstall:

```bash
# Ubuntu/Debian
sudo apt-get install php-zip

# Windows (biasanya sudah ada)
# CentOS/RHEL
sudo yum install php-zip
```

### Permissions

Pastikan folder storage writable:

```bash
chmod -R 775 storage/
chown -R www-data:www-data storage/
```

## URLs Download

-   **Single file:** `/download/{order}/{product}`
-   **All files:** `/download/{order}/all`

## Future Enhancements

-   Download statistics/tracking
-   Download limits (max downloads per order)
-   Expiry date untuk download links
-   Email notification dengan download links
-   Watermarking untuk file tertentu
