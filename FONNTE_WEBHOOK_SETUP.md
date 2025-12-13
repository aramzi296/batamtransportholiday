# Setup Fonnte Webhook - Panduan Troubleshooting

## Konfigurasi

### 1. Tambahkan ke file `.env`:

```env
FONNTE_API_URL=https://api.fonnte.com/send
FONNTE_TOKEN=YOUR_FONNTE_TOKEN_HERE
```

**PENTING**: Ganti `YOUR_FONNTE_TOKEN_HERE` dengan token Fonnte Anda yang sebenarnya.

### 2. Set Webhook URL di Fonnte Dashboard

URL webhook Anda:
```
https://yourdomain.com/api/fonnte/webhook
```

Atau untuk development:
```
http://yourdomain.com/api/fonnte/webhook
```

## Testing

### 1. Test Service (tanpa webhook)

Akses endpoint test:
```
GET /api/fonnte/test?phone=628117007201&message=Test%20Message
```

Atau dengan curl:
```bash
curl "https://yourdomain.com/api/fonnte/test?phone=628117007201&message=Test%20Message"
```

### 2. Test Webhook

Kirim pesan "test" ke nomor WhatsApp yang terhubung dengan Fonnte.

## Troubleshooting

### Tidak ada reply sama sekali

1. **Cek Log Laravel**
   ```bash
   tail -f storage/logs/laravel.log
   ```
   
   Cari log dengan keyword:
   - `Fonnte webhook received`
   - `Fonnte webhook raw request`
   - `FonnteService sendMessage`
   - `Fonnte API Error`

2. **Cek Token**
   - Pastikan `FONNTE_TOKEN` sudah di-set di `.env`
   - Pastikan token benar (copy-paste dari dashboard Fonnte)
   - Clear config cache: `php artisan config:clear`

3. **Cek Webhook URL**
   - Pastikan URL webhook di Fonnte dashboard benar
   - Pastikan URL bisa diakses dari internet (bukan localhost)
   - Test dengan curl:
     ```bash
     curl -X POST https://yourdomain.com/api/fonnte/webhook \
       -H "Content-Type: application/json" \
       -d '{"sender":"628117007201","message":"test"}'
     ```

4. **Cek CSRF**
   - Route sudah di-exclude dari CSRF di `bootstrap/app.php`
   - Pastikan tidak ada middleware lain yang memblokir

5. **Cek Response dari Fonnte API**
   - Lihat log untuk `Fonnte API response`
   - Cek `http_code` - harus 200
   - Cek `response` untuk error message dari Fonnte

### Webhook diterima tapi tidak ada reply

1. **Cek Log untuk Error**
   - Lihat log `Failed to send Fonnte reply`
   - Cek error message dan http_code

2. **Cek Token Valid**
   - Test dengan endpoint `/api/fonnte/test`
   - Jika test gagal, token mungkin salah atau expired

3. **Cek Format Nomor**
   - Nomor harus format internasional (62xxxxxxxxxx)
   - Tanpa + di depan
   - Tanpa spasi atau dash

### Webhook tidak diterima

1. **Cek Server Logs**
   - Cek web server error log (Apache/Nginx)
   - Cek apakah request sampai ke server

2. **Cek Firewall**
   - Pastikan port 80/443 terbuka
   - Pastikan server bisa menerima POST request

3. **Cek SSL Certificate**
   - Jika menggunakan HTTPS, pastikan certificate valid
   - Fonnte mungkin tidak mengirim ke URL dengan SSL error

4. **Test Manual**
   ```bash
   curl -X POST https://yourdomain.com/api/fonnte/webhook \
     -H "Content-Type: application/json" \
     -d '{"device":"test","sender":"628117007201","message":"test"}'
   ```

## Log yang Harus Muncul

Jika webhook bekerja dengan benar, Anda akan melihat log seperti ini:

```
[INFO] Fonnte webhook raw request
[INFO] Fonnte webhook received
[INFO] Fonnte reply prepared
[INFO] FonnteService sendMessage called
[INFO] Fonnte API request
[INFO] Fonnte API response
[INFO] Fonnte reply sent successfully
```

Jika ada masalah, cari log dengan level `ERROR` atau `WARNING`.

## Common Issues

### Issue: "Fonnte token not configured"
**Solution**: Set `FONNTE_TOKEN` di `.env` dan jalankan `php artisan config:clear`

### Issue: "HTTP 401 Unauthorized"
**Solution**: Token salah atau expired. Cek token di Fonnte dashboard

### Issue: "HTTP 400 Bad Request"
**Solution**: Format data salah. Cek log untuk melihat data yang diterima

### Issue: "Missing required fields: sender or message"
**Solution**: Fonnte tidak mengirim data dengan format yang diharapkan. Cek log `raw_body` untuk melihat format sebenarnya

## Support

Jika masih bermasalah:
1. Cek semua log di `storage/logs/laravel.log`
2. Test dengan endpoint `/api/fonnte/test` terlebih dahulu
3. Pastikan token dan URL webhook sudah benar
4. Hubungi support Fonnte jika masalah di sisi API mereka

