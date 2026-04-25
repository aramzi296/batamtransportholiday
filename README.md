# D'Sarana Travel - Next.js Platform

Layanan transportasi dan wisata terpercaya di Batam, kini menggunakan platform Next.js.

## Fitur
- **Next.js 15+** dengan App Router
- **Tailwind CSS v4** untuk styling premium
- **Framer Motion** untuk animasi modern
- **Lucide React** untuk ikonografi
- Lini Bisnis: Transportasi, Paket Wisata, Hotel, dan Kuliner.

## Cara Menjalankan
1. Pastikan Node.js terinstall.
2. Jalankan `npm install`.
3. Jalankan `npm run dev` untuk mode development.
4. Buka [http://localhost:3000](http://localhost:3000).

## Deployment di VPS
Untuk menjalankan aplikasi di server produksi (VPS), gunakan langkah berikut:

```bash
# 1. Clone repositori pada branch nextjs
git clone -b nextjs https://github.com/aramzi296/dsarana.git

# 2. Masuk ke folder
cd dsarana

# 3. Install dependencies
npm install

# 4. Build aplikasi
npm run build

# 5. Jalankan menggunakan PM2
npm install -g pm2
PORT=3001 pm2 start npm --name "dsarana-next" -- start
pm2 save

```

## Deployment di aaPanel
Jika Anda menggunakan **aaPanel**, Anda bisa menggunakan fitur **Node.js Project Manager**:

1. **Install Node.js Version Manager** dari App Store di aaPanel.
2. **Clone** repositori Anda ke dalam folder situs (misal: `/www/wwwroot/dsarana.com`).
3. **Jalankan Build**: Sebelum menambahkan proyek, buka terminal di folder tersebut dan jalankan:
   ```bash
   npm run build
   ```
4. Buka **Node.js Project Manager** > **Add Project**.
5. Atur konfigurasi berikut:
   - **Project Path**: Pilih folder `/www/wwwroot/dsarana.com`.
   - **Project Name**: `dsarana-next`.
   - **Run opt**: Pilih **`start [next start]`** (PENTING: Jangan pilih `dev`).
   - **Run Command**: `npm start`.
   - **Port**: `3001`.

   - **Boot**: Centang (agar otomatis jalan saat VPS restart).


5. Klik **OK** dan pastikan status proyek adalah **Running**.
6. Klik **Mapping** pada list proyek:
   - Masukkan domain Anda (misal: `dsarana.com`).
   - aaPanel akan otomatis membuat Nginx Reverse Proxy dari port 80 ke port 3001.
7. **Setup SSL (HTTPS)**:
   - Pergi ke menu **Website** di aaPanel.
   - Klik nama domain Anda > **SSL**.
   - Pilih **Let's Encrypt** dan klik **Apply**.

## Cara Update Kode di VPS
Jika ada perubahan kode di GitHub, gunakan perintah ini di terminal VPS:

```bash
git fetch origin nextjs
git reset --hard origin/nextjs
npm run build
# Lalu Restart di aaPanel Node.js Project Manager
```




## Struktur Folder

- `src/app`: Routing dan layout.
- `src/components`: Komponen UI modular.
- `public/images`: Aset gambar layanan.
