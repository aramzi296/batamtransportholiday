<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Bagaimana cara melakukan booking kendaraan?',
                'answer' => '<p>Untuk melakukan booking kendaraan, Anda dapat:</p><ol><li>Pilih kendaraan yang diinginkan dari halaman <strong>Armada</strong></li><li>Klik tombol <strong>Booking Sekarang</strong></li><li>Isi form booking dengan lengkap (tanggal mulai, durasi, dan data penyewa)</li><li>Konfirmasi booking dan tunggu konfirmasi dari admin</li></ol><p>Setelah booking dikonfirmasi, Anda akan menerima email konfirmasi dengan detail booking.</p>',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'Apa saja syarat dan ketentuan untuk menyewa kendaraan?',
                'answer' => '<p>Syarat dan ketentuan menyewa kendaraan:</p><ul><li>Memiliki <strong>SIM aktif</strong> sesuai jenis kendaraan</li><li>Usia minimal <strong>21 tahun</strong></li><li>Menyediakan <strong>KTP asli</strong> dan fotokopi</li><li>Membayar <strong>DP minimal 50%</strong> dari total biaya</li><li>Mengisi form sewa dengan data yang valid</li><li>Menyetujui syarat dan ketentuan yang berlaku</li></ul>',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Berapa lama proses konfirmasi booking?',
                'answer' => '<p>Proses konfirmasi booking biasanya memakan waktu <strong>1-2 jam</strong> pada hari kerja (Senin-Jumat, 08:00-17:00 WIB).</p><p>Untuk booking di luar jam kerja atau hari libur, konfirmasi akan diproses pada hari kerja berikutnya.</p><p>Anda akan menerima notifikasi via email atau WhatsApp setelah booking dikonfirmasi.</p>',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'Apakah tersedia layanan dengan sopir?',
                'answer' => '<p>Ya, kami menyediakan layanan dengan sopir profesional. Sopir kami:</p><ul><li>Berpengalaman dan memiliki <strong>SIM aktif</strong></li><li>Menguasai rute di wilayah operasional</li><li>Ramah dan profesional dalam melayani</li><li>Dapat membantu sebagai <strong>pemandu wisata</strong> jika diperlukan</li></ul><p>Biaya sopir dapat dilihat pada detail harga setiap kendaraan. Silakan pilih opsi <strong>Dengan Sopir</strong> saat booking.</p>',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana sistem pembayaran sewa kendaraan?',
                'answer' => '<p>Sistem pembayaran sewa kendaraan:</p><ol><li><strong>Down Payment (DP)</strong>: Minimal 50% dari total biaya saat booking dikonfirmasi</li><li><strong>Pelunasan</strong>: Sisa pembayaran dilakukan saat pengambilan kendaraan</li><li><strong>Metode pembayaran</strong>: Transfer bank, tunai, atau e-wallet</li><li><strong>Deposit</strong>: Diperlukan deposit untuk jaminan (akan dikembalikan setelah kendaraan dikembalikan dalam kondisi baik)</li></ol>',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'question' => 'Apakah kendaraan sudah termasuk bahan bakar?',
                'answer' => '<p>Kendaraan diserahkan dengan kondisi <strong>tangki penuh</strong>. Saat pengembalian, kendaraan harus dikembalikan dengan kondisi tangki penuh juga.</p><p>Jika kendaraan dikembalikan dengan bahan bakar kurang dari saat pengambilan, akan dikenakan biaya tambahan sesuai dengan selisih bahan bakar yang kurang.</p><p>Biaya bahan bakar selama sewa menjadi <strong>tanggung jawab penyewa</strong>.</p>',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana jika terjadi kerusakan pada kendaraan selama sewa?',
                'answer' => '<p>Jika terjadi kerusakan pada kendaraan selama sewa:</p><ul><li><strong>Kerusakan ringan</strong>: Biaya perbaikan ditanggung penyewa sesuai dengan biaya perbaikan di bengkel resmi</li><li><strong>Kerusakan berat</strong>: Akan dilakukan asesmen terlebih dahulu untuk menentukan biaya perbaikan</li><li><strong>Asuransi</strong>: Kami menyediakan opsi asuransi untuk melindungi dari risiko kerusakan (opsional, dengan biaya tambahan)</li><li><strong>Laporan polisi</strong>: Untuk kecelakaan, wajib membuat laporan polisi</li></ul>',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'question' => 'Bisakah melakukan booking untuk jangka waktu panjang (bulanan)?',
                'answer' => '<p>Ya, kami melayani sewa kendaraan untuk jangka waktu panjang dengan harga khusus:</p><ul><li><strong>Sewa harian</strong>: Harga standar per hari</li><li><strong>Sewa mingguan</strong>: Diskon 10% dari total harga harian</li><li><strong>Sewa bulanan</strong>: Diskon 20% dari total harga harian</li></ul><p>Untuk sewa jangka panjang, silakan hubungi customer service kami untuk mendapatkan penawaran harga terbaik. Kami juga menyediakan paket khusus untuk kebutuhan korporat.</p>',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'question' => 'Apakah bisa membatalkan atau mengubah booking?',
                'answer' => '<p>Pembatalan atau perubahan booking dapat dilakukan dengan ketentuan:</p><ul><li><strong>Pembatalan 3 hari sebelum tanggal sewa</strong>: DP dikembalikan 100%</li><li><strong>Pembatalan 1-2 hari sebelum tanggal sewa</strong>: DP dikembalikan 50%</li><li><strong>Pembatalan di hari H</strong>: DP tidak dapat dikembalikan</li><li><strong>Perubahan tanggal</strong>: Dapat dilakukan maksimal 2 hari sebelum tanggal sewa (tergantung ketersediaan kendaraan)</li></ul><p>Untuk pembatalan atau perubahan, silakan hubungi customer service kami.</p>',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'question' => 'Dimana lokasi pengambilan dan pengembalian kendaraan?',
                'answer' => '<p>Lokasi pengambilan dan pengembalian kendaraan:</p><ul><li><strong>Kantor pusat</strong>: Jl. [Alamat Lengkap] - Buka setiap hari 08:00-17:00 WIB</li><li><strong>Delivery service</strong>: Kami menyediakan layanan antar jemput kendaraan dengan biaya tambahan (tergantung jarak)</li><li><strong>Bandara</strong>: Dapat diatur pengambilan/pengembalian di bandara dengan biaya tambahan</li></ul><p>Untuk informasi lebih detail mengenai lokasi dan layanan delivery, silakan hubungi customer service kami di <strong>+62 XXX XXXX XXXX</strong> atau email ke <strong>info@dsarana.com</strong>.</p>',
                'sort_order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }

        $this->command->info('FAQ seeder berhasil dijalankan!');
    }
}







