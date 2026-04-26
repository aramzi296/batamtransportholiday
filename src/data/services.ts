import { Car, Map, Hotel, UtensilsCrossed, Shield, Clock, Award, Users, CheckCircle2 } from "lucide-react";

export const services = [
  {
    id: "transportation",
    slug: "transportation",
    title: "Transportasi & Rental",
    shortDescription: "Sewa mobil harian, bulanan, atau bus pariwisata dengan armada terbaru.",
    fullDescription: "Kami menyediakan berbagai pilihan kendaraan mulai dari city car, SUV, hingga bus pariwisata. Seluruh armada kami dirawat secara rutin untuk memastikan kenyamanan dan keamanan perjalanan Anda di Batam.",
    icon: Car,
    image: "/images/transport.png",
    price: "Mulai dari Rp 350.000 / hari",
    features: [
      "Armada terbaru & terawat",
      "Pilihan Lepas Kunci atau Dengan Driver",
      "Layanan Antar Jemput Bandara",
      "Asuransi All Risk",
      "Bantuan Darurat 24 Jam"
    ],
    details: [
      {
        title: "Armada Kami",
        content: "Tersedia Avanza, Innova Reborn, Fortuner, hingga Hiace dan Bus Pariwisata 45 seat."
      },
      {
        title: "Sewa Lepas Kunci",
        content: "Syarat mudah dengan proses verifikasi cepat untuk wisatawan dan pebisnis."
      },
      {
        title: "Layanan Driver",
        content: "Driver profesional yang ramah, tidak merokok, dan menguasai rute jalan di seluruh Batam."
      }
    ]
  },
  {
    id: "tour",
    slug: "tour",
    title: "Paket Wisata Batam",
    shortDescription: "Jelajahi keindahan Kota Batam dengan paket city tour yang fleksibel.",
    fullDescription: "Nikmati pengalaman berwisata yang tak terlupakan dengan paket City Tour Batam kami. Kami akan membawa Anda mengunjungi landmark ikonik seperti Jembatan Barelang, Welcome to Batam, hingga pusat perbelanjaan ternama.",
    icon: Map,
    image: "/images/tour.png",
    price: "Mulai dari Rp 500.000 / paket",
    features: [
      "Itinerary Fleksibel",
      "Driver Berfungsi Sebagai Guide",
      "Sudah Termasuk BBM & Parkir",
      "Dokumentasi Foto Gratis",
      "Penjemputan di Hotel/Pelabuhan"
    ],
    details: [
      {
        title: "Destinasi Utama",
        content: "Jembatan Barelang, Mega Wisata Ocarina, Masjid Raya Sultan Mahmud Riayat Syah, dan lainnya."
      },
      {
        title: "Paket Grup",
        content: "Kami melayani rombongan sekolah, instansi, hingga family gathering dengan harga khusus."
      }
    ]
  },
  {
    id: "hotel",
    slug: "hotel",
    title: "Akomodasi Hotel",
    shortDescription: "Pemesanan hotel bintang 3 hingga 5 dengan harga spesial.",
    fullDescription: "Kerja sama kami dengan berbagai hotel ternama di Batam memungkinkan Anda mendapatkan harga yang lebih kompetitif dibandingkan aplikasi pemesanan lainnya. Kami memastikan Anda mendapatkan kamar terbaik di lokasi yang strategis.",
    icon: Hotel,
    image: "/images/hotel.png",
    price: "Harga Spesial Korporat",
    features: [
      "Harga di Bawah Rate Online",
      "Pilihan Hotel Strategis",
      "Proses Check-in Cepat",
      "Free Upgrade (Tergantung Ketersediaan)",
      "Layanan Concierge 24 Jam"
    ],
    details: [
      {
        title: "Partner Hotel",
        content: "Bekerja sama dengan Marriott, Radisson, Best Western, dan berbagai hotel butik unik lainnya."
      }
    ]
  },
  {
    id: "kuliner",
    slug: "kuliner",
    title: "Tur Kuliner Batam",
    shortDescription: "Nikmati sensasi seafood khas Batam dan kuliner lokal legendaris.",
    fullDescription: "Batam terkenal dengan hidangan lautnya yang segar. Tur kuliner kami akan mengajak Anda ke restoran seafood terbaik (Gong-gong, Kepiting, Udang) serta mencicipi kuliner khas seperti Mie Lendir dan Soup Ikan Batam.",
    icon: UtensilsCrossed,
    image: "/images/kuliner.png",
    price: "Mulai dari Rp 250.000 / orang",
    features: [
      "Kunjungan ke Restoran Seafood Ternama",
      "Icip-icip Jajanan Pasar Lokal",
      "Sudah Termasuk Transportasi",
      "Pemandu Kuliner Berpengalaman",
      "Opsi Menu Halal Terjamin"
    ],
    details: [
      {
        title: "Menu Andalan",
        content: "Seafood Barelang, Mie Lendir, Soup Ikan Batam, dan Otak-otak khas Kepulauan Riau."
      }
    ]
  }
];
