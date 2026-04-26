'use client';

import React from 'react';
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { motion } from 'framer-motion';
import { Target, Eye, Users, Award, Shield, Clock } from 'lucide-react';

const AboutPage = () => {
  const stats = [
    { label: 'Tahun Pengalaman', value: '5+' },
    { label: 'Pelanggan Puas', value: '1000+' },
    { label: 'Armada Kendaraan', value: '50+' },
    { label: 'Destinasi Wisata', value: '25+' },
  ];

  const values = [
    {
      icon: Shield,
      title: 'Kepercayaan',
      description: 'Membangun hubungan jangka panjang dengan pelanggan melalui kejujuran dan transparansi.'
    },
    {
      icon: Award,
      title: 'Kualitas',
      description: 'Memberikan standar layanan tertinggi dalam setiap aspek perjalanan Anda.'
    },
    {
      icon: Users,
      title: 'Kepuasan Pelanggan',
      description: 'Fokus utama kami adalah memastikan setiap perjalanan Anda berkesan dan nyaman.'
    }
  ];

  return (
    <div className="flex flex-col min-h-screen">
      <Navbar />
      
      <main className="flex-grow pt-20">
        {/* Header Section */}
        <section className="relative py-24 bg-slate-900 overflow-hidden">
          <div className="absolute top-0 right-0 w-1/2 h-full bg-emerald-600/10 -skew-x-12 translate-x-1/4" />
          <div className="container mx-auto px-4 md:px-6 relative z-10">
            <div className="max-w-3xl">
              <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6 }}
              >
                <span className="text-emerald-400 font-bold tracking-widest uppercase text-sm mb-4 block">Tentang Kami</span>
                <h1 className="text-4xl md:text-6xl font-display font-extrabold text-white mb-6 leading-tight">
                  Dedikasi Kami untuk <br />
                  <span className="text-emerald-500">Perjalanan Anda</span>
                </h1>
                <p className="text-xl text-slate-400 leading-relaxed">
                  D'Sarana Travel hadir sebagai solusi transportasi dan wisata terdepan di Batam, menggabungkan kenyamanan, keamanan, dan keramah-tamahan lokal.
                </p>
              </motion.div>
            </div>
          </div>
        </section>

        {/* Stats Section */}
        <section className="py-12 bg-emerald-600">
          <div className="container mx-auto px-4 md:px-6">
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-8">
              {stats.map((stat, index) => (
                <div key={index} className="text-center text-white">
                  <p className="text-4xl md:text-5xl font-display font-extrabold mb-2">{stat.value}</p>
                  <p className="text-emerald-100 text-sm font-medium uppercase tracking-wider">{stat.label}</p>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* Story Section */}
        <section className="py-24 bg-white">
          <div className="container mx-auto px-4 md:px-6">
            <div className="flex flex-col lg:flex-row items-center gap-16">
              <div className="flex-1">
                <div className="relative">
                  <img 
                    src="/images/transport.png" 
                    alt="Our Story" 
                    className="rounded-[3rem] shadow-2xl relative z-10"
                  />
                  <div className="absolute -bottom-6 -right-6 w-full h-full border-4 border-emerald-100 rounded-[3rem] -z-10" />
                </div>
              </div>
              <div className="flex-1">
                <h2 className="text-3xl md:text-5xl font-display font-bold text-slate-900 mb-8">
                  Kisah Perjalanan <br />
                  <span className="text-emerald-600">D'Sarana Travel</span>
                </h2>
                <div className="space-y-6 text-lg text-slate-600 leading-relaxed">
                  <p>
                    Didirikan dengan semangat untuk memajukan pariwisata di Batam, D'Sarana Travel memulai perjalanannya sebagai penyedia rental mobil kecil yang berfokus pada kualitas layanan.
                  </p>
                  <p>
                    Seiring berjalannya waktu, kami berkembang menjadi agen perjalanan lengkap yang melayani ribuan pelanggan, mulai dari wisatawan domestik hingga mancanegara, serta instansi pemerintah dan korporasi.
                  </p>
                  <p>
                    Kami percaya bahwa setiap perjalanan adalah cerita yang unik. Itulah mengapa kami berkomitmen untuk memberikan lebih dari sekadar transportasi, tetapi sebuah pengalaman yang tak terlupakan.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* Vision & Mission */}
        <section className="py-24 bg-slate-50">
          <div className="container mx-auto px-4 md:px-6">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-12">
              <div className="bg-white p-12 rounded-[2.5rem] shadow-xl border border-slate-100">
                <div className="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-8">
                  <Eye size={32} />
                </div>
                <h3 className="text-3xl font-display font-bold text-slate-900 mb-6">Visi Kami</h3>
                <p className="text-lg text-slate-600 leading-relaxed">
                  Menjadi penyedia layanan transportasi dan pariwisata nomor satu di Batam yang dikenal karena inovasi, keandalan, dan dedikasi terhadap kepuasan pelanggan.
                </p>
              </div>
              <div className="bg-white p-12 rounded-[2.5rem] shadow-xl border border-slate-100">
                <div className="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-8">
                  <Target size={32} />
                </div>
                <h3 className="text-3xl font-display font-bold text-slate-900 mb-6">Misi Kami</h3>
                <ul className="space-y-4 text-lg text-slate-600">
                  <li className="flex gap-3">
                    <span className="text-emerald-600 font-bold">•</span>
                    Menyediakan armada kendaraan terbaru yang selalu dalam kondisi prima.
                  </li>
                  <li className="flex gap-3">
                    <span className="text-emerald-600 font-bold">•</span>
                    Mengembangkan paket wisata kreatif yang menonjolkan kekayaan budaya dan alam Batam.
                  </li>
                  <li className="flex gap-3">
                    <span className="text-emerald-600 font-bold">•</span>
                    Melatih tim driver dan staf yang profesional, sopan, dan berwawasan luas.
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </section>

        {/* Core Values */}
        <section className="py-24 bg-white">
          <div className="container mx-auto px-4 md:px-6 text-center">
            <h2 className="text-3xl md:text-5xl font-display font-bold text-slate-900 mb-16">
              Nilai-Nilai <span className="text-emerald-600">Inti Kami</span>
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              {values.map((value, index) => (
                <div key={index} className="p-8 group hover:bg-emerald-600 transition-all duration-500 rounded-[2rem]">
                  <div className="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                    <value.icon size={32} />
                  </div>
                  <h4 className="text-xl font-bold text-slate-900 mb-4 group-hover:text-white transition-colors">{value.title}</h4>
                  <p className="text-slate-600 group-hover:text-emerald-50 transition-colors leading-relaxed">
                    {value.description}
                  </p>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* CTA Section */}
        <section className="py-24 bg-slate-900">
          <div className="container mx-auto px-4 md:px-6">
            <div className="bg-emerald-600 rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden">
              <h2 className="text-3xl md:text-5xl font-display font-bold text-white mb-8">
                Ingin Mengenal Kami Lebih Dekat?
              </h2>
              <p className="text-xl text-emerald-50 mb-12 max-w-2xl mx-auto">
                Tim kami siap menjawab pertanyaan Anda dan membantu merencanakan perjalanan terbaik Anda di Batam.
              </p>
              <a 
                href="https://wa.me/628136892535" 
                className="inline-block bg-white text-emerald-700 px-10 py-5 rounded-2xl font-bold text-lg hover:bg-emerald-50 transition-all shadow-xl"
              >
                Hubungi Kami Sekarang
              </a>
            </div>
          </div>
        </section>
      </main>

      <Footer />
    </div>
  );
};

export default AboutPage;
