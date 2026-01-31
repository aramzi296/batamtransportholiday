# Petunjuk Update Server (VPS + aaPanel) dengan Kode Terbaru dari GitHub

Panduan ini untuk mengupdate aplikasi **dsarana** di server live agar mengikuti kode terbaru dari GitHub.

---

## Persiapan

- Akses **SSH** ke VPS (username, password/SSH key).
- Pastikan di server sudah terpasang: **Git**, **PHP 8.2+**, **Composer**.
- Lokasi project di aaPanel biasanya: **`/www/wwwroot/namadomain-anda`** (sesuaikan dengan domain Anda).

---

## Cara 1: Update via SSH (Disarankan)

### 1. Masuk ke VPS via SSH

Dari komputer Anda, buka terminal/PowerShell (Windows) atau Terminal (Mac/Linux):

```bash
ssh root@IP_ATAU_DOMAIN_VPS
```

Atau jika pakai user lain:

```bash
ssh username@IP_ATAU_DOMAIN_VPS
```

Masukkan password atau gunakan SSH key jika sudah dikonfigurasi.

---

### 2. Masuk ke Direktori Project

```bash
cd /www/wwwroot/namadomain-anda
```

Ganti `namadomain-anda` dengan nama folder situs Anda di aaPanel (misal: `dsarana.com` atau `www.dsarana.com`).

---

### 3. Aktifkan Mode Maintenance (Opsional tapi Disarankan)

Agar pengunjung tidak error saat update:

```bash
php artisan down
```

---

### 4. Ambil Kode Terbaru dari GitHub

```bash
git fetch origin
git pull origin main
```

Jika ada konflik atau Anda pakai branch lain, sesuaikan nama branch (misal: `master` → ganti `main` dengan `master`).

---

### 5. Install/Update Dependensi PHP

```bash
composer install --no-dev --optimize-autoloader
```

- `--no-dev` = tidak install package development (untuk production).
- `--optimize-autoloader` = mempercepat autoload.

---

### 6. Jalankan Migration Database (Jika Ada)

```bash
php artisan migrate --force
```

`--force` dipakai agar migration jalan di environment production tanpa konfirmasi.

---

### 7. Clear & Cache Laravel

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### 8. Storage Link (Jika Belum Pernah)

Jika upload file disimpan di `storage/app/public`:

```bash
php artisan storage:link
```

---

### 9. Set Permission Folder Storage & Cache

```bash
chown -R www:www storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

Di aaPanel user web server biasanya **`www`**. Jika berbeda, sesuaikan (bisa cek di aaPanel → Website → PHP).

---

### 10. Matikan Mode Maintenance

```bash
php artisan up
```

---

## Cara 2: Update via Terminal aaPanel

1. Login **aaPanel** → **Terminal** (ikon terminal di sidebar).
2. Jalankan perintah yang sama seperti **Cara 1**, mulai dari langkah 2:

   ```bash
   cd /www/wwwroot/namadomain-anda
   php artisan down
   git pull origin main
   composer install --no-dev --optimize-autoloader
   php artisan migrate --force
   php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear
   php artisan config:cache && php artisan route:cache && php artisan view:cache
   chown -R www:www storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   php artisan up
   ```

---

## Script Singkat (Copy-Paste)

Anda bisa simpan sebagai script di server (misal: `update.sh`) lalu jalankan `bash update.sh`:

```bash
#!/bin/bash
cd /www/wwwroot/namadomain-anda   # SESUAIKAN PATH

php artisan down
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
chown -R www:www storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
php artisan up

echo "Update selesai."
```

Cara pakai:

```bash
nano update.sh
# paste script di atas, sesuaikan path, lalu simpan (Ctrl+O, Enter, Ctrl+X)

chmod +x update.sh
./update.sh
```

---

## Jika Ada Perubahan di File `.env`

- **Jangan** overwrite `.env` dengan file dari GitHub (biasanya `.env` tidak di-commit).
- Setelah `git pull`, jika ada variabel baru (misal dari dokumentasi atau tim), tambahkan manual di server:

  ```bash
  nano .env
  ```

  Tambah atau ubah baris yang diperlukan, simpan.

- Setelah mengubah `.env`:

  ```bash
  php artisan config:clear
  php artisan config:cache
  ```

---

## Troubleshooting

| Masalah | Solusi |
|--------|--------|
| `git pull` konflik | Backup dulu: `cp -r . ../backup-dsarana`. Lalu `git stash` atau selesaikan konflik manual, setelah itu `git pull` lagi. |
| `composer: command not found` | Install Composer di server atau gunakan path penuh, misal: `/usr/bin/composer install ...` |
| Permission denied (storage/cache) | `chown -R www:www storage bootstrap/cache` dan `chmod -R 775 storage bootstrap/cache` |
| Migration error | Cek `.env` (DB_DATABASE, DB_USERNAME, DB_PASSWORD). Backup database dulu sebelum migrate. |
| 500 setelah update | Cek `storage/logs/laravel.log`, pastikan permission storage & cache benar, dan jalankan lagi `php artisan config:cache` |

---

## Checklist Cepat

- [ ] SSH / Terminal aaPanel
- [ ] `cd` ke folder project
- [ ] `php artisan down`
- [ ] `git pull origin main`
- [ ] `composer install --no-dev --optimize-autoloader`
- [ ] `php artisan migrate --force`
- [ ] Clear & cache config, route, view
- [ ] Permission `storage` & `bootstrap/cache`
- [ ] `php artisan up`
- [ ] Cek website di browser

Setelah mengikuti langkah di atas, server Anda akan memakai kode terbaru dari GitHub. Jika mau, Anda bisa menyesuaikan path dan nama branch di petunjuk ini dengan setup Anda (misal path aaPanel atau branch `master`).
