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
pm2 start npm --name "dsarana-next" -- start
pm2 save
```

## Struktur Folder

- `src/app`: Routing dan layout.
- `src/components`: Komponen UI modular.
- `public/images`: Aset gambar layanan.
