import { Car, Map, Hotel, UtensilsCrossed } from "lucide-react";

export const services = [
  {
    id: "transportation",
    slug: "transportation",
    icon: Car,
    image: "/images/transport.png",
    idContent: {
      title: "Transportasi & Rental",
      shortDescription: "Sewa mobil harian, bulanan, atau bus pariwisata dengan armada terbaru.",
      fullDescription: "Kami menyediakan berbagai pilihan kendaraan mulai dari city car, SUV, hingga bus pariwisata. Seluruh armada kami dirawat secara rutin untuk memastikan kenyamanan dan keamanan perjalanan Anda di Batam.",
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
    enContent: {
      title: "Transportation & Rental",
      shortDescription: "Daily, monthly car rental, or tourist bus with the latest fleet.",
      fullDescription: "We provide various vehicle options ranging from city cars, SUVs, to tourist buses. Our entire fleet is routinely maintained to ensure the comfort and safety of your trip in Batam.",
      price: "Starts from Rp 350.000 / day",
      features: [
        "Latest & well-maintained fleet",
        "Self-drive or with Driver options",
        "Airport Shuttle Service",
        "All Risk Insurance",
        "24-Hour Emergency Assistance"
      ],
      details: [
        {
          title: "Our Fleet",
          content: "Available Avanza, Innova Reborn, Fortuner, up to Hiace and 45-seat Tourist Bus."
        },
        {
          title: "Self-Drive Rental",
          content: "Easy requirements with fast verification process for tourists and business people."
        },
        {
          title: "Driver Service",
          content: "Professional drivers who are friendly, non-smoking, and master the routes in all of Batam."
        }
      ]
    }
  },
  {
    id: "tour",
    slug: "tour",
    icon: Map,
    image: "/images/tour.png",
    idContent: {
      title: "Paket Wisata Batam",
      shortDescription: "Jelajahi keindahan Kota Batam dengan paket city tour yang fleksibel.",
      fullDescription: "Nikmati pengalaman berwisata yang tak terlupakan dengan paket City Tour Batam kami. Kami akan membawa Anda mengunjungi landmark ikonik seperti Jembatan Barelang, Welcome to Batam, hingga pusat perbelanjaan ternama.",
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
    enContent: {
      title: "Batam Tour Packages",
      shortDescription: "Explore the beauty of Batam City with flexible city tour packages.",
      fullDescription: "Enjoy an unforgettable travel experience with our Batam City Tour package. We will take you to visit iconic landmarks such as Barelang Bridge, Welcome to Batam, to famous shopping centers.",
      price: "Starts from Rp 500.000 / package",
      features: [
        "Flexible Itinerary",
        "Driver Functions as Guide",
        "Includes Fuel & Parking",
        "Free Photo Documentation",
        "Pickup at Hotel/Port"
      ],
      details: [
        {
          title: "Main Destinations",
          content: "Barelang Bridge, Mega Wisata Ocarina, Sultan Mahmud Riayat Syah Great Mosque, and others."
        },
        {
          title: "Group Packages",
          content: "We serve school groups, agencies, to family gatherings with special prices."
        }
      ]
    }
  },
  {
    id: "hotel",
    slug: "hotel",
    icon: Hotel,
    image: "/images/hotel.png",
    idContent: {
      title: "Akomodasi Hotel",
      shortDescription: "Pemesanan hotel bintang 3 hingga 5 dengan harga spesial.",
      fullDescription: "Kerja sama kami dengan berbagai hotel ternama di Batam memungkinkan Anda mendapatkan harga yang lebih kompetitif dibandingkan aplikasi pemesanan lainnya. Kami memastikan Anda mendapatkan kamar terbaik di lokasi yang strategis.",
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
    enContent: {
      title: "Hotel Accommodation",
      shortDescription: "3 to 5-star hotel bookings with special prices.",
      fullDescription: "Our cooperation with various famous hotels in Batam allows you to get more competitive prices compared to other booking applications. We ensure you get the best room in a strategic location.",
      price: "Special Corporate Rates",
      features: [
        "Rates Below Online Prices",
        "Strategic Hotel Options",
        "Fast Check-in Process",
        "Free Upgrade (Subject to Availability)",
        "24-Hour Concierge Service"
      ],
      details: [
        {
          title: "Hotel Partners",
          content: "Partnering with Marriott, Radisson, Best Western, and various other unique boutique hotels."
        }
      ]
    }
  },
  {
    id: "kuliner",
    slug: "kuliner",
    icon: UtensilsCrossed,
    image: "/images/kuliner.png",
    idContent: {
      title: "Tur Kuliner Batam",
      shortDescription: "Nikmati sensasi seafood khas Batam dan kuliner lokal legendaris.",
      fullDescription: "Batam terkenal dengan hidangan lautnya yang segar. Tur kuliner kami akan mengajak Anda ke restoran seafood terbaik (Gong-gong, Kepiting, Udang) serta mencicipi kuliner khas seperti Mie Lendir dan Soup Ikan Batam.",
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
    },
    enContent: {
      title: "Batam Culinary Tour",
      shortDescription: "Enjoy the sensation of Batam's typical seafood and legendary local cuisine.",
      fullDescription: "Batam is famous for its fresh seafood. Our culinary tour will take you to the best seafood restaurants (Gong-gong, Crab, Shrimp) and taste typical culinary delights such as Mie Lendir and Batam Fish Soup.",
      price: "Starts from Rp 250.000 / person",
      features: [
        "Visit to Famous Seafood Restaurants",
        "Tasting Local Market Snacks",
        "Transportation Included",
        "Experienced Culinary Guide",
        "Guaranteed Halal Options"
      ],
      details: [
        {
          title: "Featured Menu",
          content: "Barelang Seafood, Mie Lendir, Batam Fish Soup, and Otak-otak from Riau Islands."
        }
      ]
    }
  }
];
