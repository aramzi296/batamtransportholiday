# Cara Menambahkan Konfigurasi AWS ke File .env

## Status Saat Ini

✅ **Konfigurasi AWS sudah ada di `config/filesystems.php` dengan default values**
- Sistem akan bekerja tanpa perlu menambahkan ke `.env`
- Default values sudah menggunakan credentials Cloudflare R2

## Mengapa Perlu Menambahkan ke .env?

1. **Kemudahan Maintenance** - Lebih mudah mengubah credentials tanpa edit code
2. **Best Practice** - Credentials seharusnya di `.env`, bukan di code
3. **Environment Specific** - Bisa bedakan config untuk development/production

## Cara Menambahkan

### 1. Buka file `.env` di root project

### 2. Tambahkan konfigurasi berikut di bagian bawah file:

```env
# AWS S3 Storage Configuration (Cloudflare R2)
AWS_ACCESS_KEY_ID=a38e00c1f02eb7d75747d2dcd31f45fd
AWS_SECRET_ACCESS_KEY=4c7c58403abc316bfcae0a1b143749902215a8fa46199ea37c6e851d26bac868
AWS_DEFAULT_REGION=auto
AWS_BUCKET=dsarana
AWS_ENDPOINT=https://4cfba700e437e75f28ea333d97f0909a.r2.cloudflarestorage.com
AWS_USE_PATH_STYLE_ENDPOINT=false
```

### 3. Clear config cache:

```bash
php artisan config:clear
```

## Verifikasi

Jalankan di terminal:
```bash
php artisan tinker
```

Kemudian ketik:
```php
config('filesystems.disks.s3')
```

Pastikan semua nilai sudah sesuai dengan yang Anda set di `.env`.

## Catatan

- File `.env` tidak di-commit ke Git (ada di `.gitignore`)
- Jika tidak menambahkan ke `.env`, sistem tetap bekerja dengan default dari `config/filesystems.php`
- Untuk production, **WAJIB** menambahkan ke `.env` dan jangan hardcode credentials di code









