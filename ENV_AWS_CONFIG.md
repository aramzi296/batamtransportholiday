# Konfigurasi AWS S3 (Cloudflare R2) untuk .env

Tambahkan konfigurasi berikut ke file `.env` Anda:

```env
# AWS S3 Storage Configuration (Cloudflare R2)
AWS_ACCESS_KEY_ID=a38e00c1f02eb7d75747d2dcd31f45fd
AWS_SECRET_ACCESS_KEY=4c7c58403abc316bfcae0a1b143749902215a8fa46199ea37c6e851d26bac868
AWS_DEFAULT_REGION=auto
AWS_BUCKET=dsarana
AWS_ENDPOINT=https://4cfba700e437e75f28ea333d97f0909a.r2.cloudflarestorage.com
AWS_USE_PATH_STYLE_ENDPOINT=false
```

## Catatan Penting:

1. **File .env tidak di-commit ke Git** - File `.env` biasanya ada di `.gitignore` untuk keamanan
2. **Default values sudah ada** - Jika tidak menambahkan ke `.env`, sistem akan menggunakan default values dari `config/filesystems.php`
3. **Untuk production** - Disarankan untuk menambahkan ke `.env` agar mudah diubah tanpa edit code

## Cara Menambahkan:

1. Buka file `.env` di root project
2. Tambahkan konfigurasi di atas
3. Simpan file
4. Clear config cache: `php artisan config:clear`

## Verifikasi:

Setelah menambahkan, jalankan:
```bash
php artisan config:clear
php artisan tinker
```

Kemudian di tinker:
```php
config('filesystems.disks.s3')
```

Pastikan semua nilai sudah sesuai.




