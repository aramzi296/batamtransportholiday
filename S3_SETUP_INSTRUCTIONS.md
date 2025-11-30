# Instruksi Setup S3 Storage (Cloudflare R2)

## 1. Update File .env

Tambahkan konfigurasi berikut ke file `.env` Anda:

```env
# S3 Storage Configuration (Cloudflare R2)
AWS_ACCESS_KEY_ID=a38e00c1f02eb7d75747d2dcd31f45fd
AWS_SECRET_ACCESS_KEY=4c7c58403abc316bfcae0a1b143749902215a8fa46199ea37c6e851d26bac868
AWS_DEFAULT_REGION=auto
AWS_BUCKET=dsarana
AWS_ENDPOINT=https://4cfba700e437e75f28ea333d97f0909a.r2.cloudflarestorage.com
AWS_USE_PATH_STYLE_ENDPOINT=false
```

**Catatan**: Konfigurasi default sudah diset di `config/filesystems.php`, jadi jika tidak ada di `.env`, akan menggunakan nilai default.

## 2. File yang Sudah Diupdate

### Config
- `config/filesystems.php` - Konfigurasi S3 disk sudah diupdate

### Models
- `app/Models/VehicleImage.php` - Accessor untuk URL gambar menggunakan S3
- `app/Models/User.php` - Accessor untuk foto profil menggunakan S3
- `app/Models/Testimonial.php` - Accessor untuk foto testimonial menggunakan S3

### Controllers
- `app/Http/Controllers/Member/MemberDashboardController.php`
  - `profileUpdate()` - Upload foto profil ke S3
  - `vehiclesStore()` - Upload gambar kendaraan ke S3
  - `vehiclesUpdate()` - Update gambar kendaraan ke S3
  - `vehiclesDestroy()` - Hapus gambar dari S3

- `app/Http/Controllers/Admin/AdminVehicleController.php`
  - `update()` - Update gambar kendaraan ke S3

- `app/Http/Controllers/Admin/AdminTestimonialController.php`
  - `store()` - Upload foto testimonial ke S3
  - `update()` - Update foto testimonial ke S3
  - `destroy()` - Hapus foto dari S3

## 3. Struktur Folder di S3

Gambar akan disimpan dengan struktur berikut di S3 bucket:
- `profiles/` - Foto profil user
- `vehicles/` - Foto kendaraan
- `testimonials/` - Foto testimonial

## 4. Fallback untuk Data Lama

Semua accessor dan controller sudah dilengkapi dengan fallback untuk data lama yang masih menggunakan local storage. Sistem akan:
1. Cek di S3 terlebih dahulu
2. Jika tidak ada, cek di local storage (untuk data lama)
3. Jika tidak ada, gunakan placeholder/default image

## 5. Testing

Setelah mengupdate `.env`, test upload gambar:
1. Upload foto profil member
2. Upload foto kendaraan
3. Upload foto testimonial

Pastikan semua gambar dapat diakses dengan benar dari S3.

## 6. Catatan

- Konfigurasi menggunakan Cloudflare R2 (S3-compatible storage)
- Pastikan bucket `dsarana` sudah dibuat di Cloudflare R2
- Pastikan endpoint URL sudah benar
- Region menggunakan `auto` untuk Cloudflare R2
- `use_path_style_endpoint` diset ke `false` untuk Cloudflare R2
- Semua gambar baru akan otomatis diupload ke R2
- Gambar lama masih bisa diakses dari local storage (fallback)

