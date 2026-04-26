'use client';

import React from 'react';
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { motion } from 'framer-motion';
import { FileText, ShieldAlert, CheckCircle2 } from 'lucide-react';

const TermsPage = () => {
  const sections = [
    {
      title: '1. Ketentuan Umum',
      content: 'Dengan menggunakan layanan D\'Sarana Travel, Anda dianggap telah membaca, memahami, dan menyetujui seluruh Syarat & Ketentuan ini. Layanan kami mencakup penyewaan kendaraan, paket wisata, dan pengaturan akomodasi di wilayah Batam.'
    },
    {
      title: '2. Persyaratan Penyewa Kendaraan',
      content: 'Penyewa wajib memiliki SIM yang masih berlaku (SIM A untuk mobil penumpang). Penyewa bertanggung jawab penuh atas kendaraan selama masa sewa dan wajib mematuhi peraturan lalu lintas yang berlaku di Indonesia.'
    },
    {
      title: '3. Pemesanan dan Pembayaran',
      content: 'Pemesanan dianggap sah setelah adanya konfirmasi dari tim kami dan pembayaran uang muka (DP) sesuai kesepakatan. Pelunasan dilakukan paling lambat pada saat serah terima kendaraan atau dimulainya layanan.'
    },
    {
      title: '4. Kebijakan Pembatalan',
      content: 'Pembatalan yang dilakukan kurang dari 24 jam sebelum jadwal layanan dapat dikenakan biaya pembatalan sebesar 50% dari uang muka. Pembatalan pada hari H akan mengakibatkan hangusnya uang muka.'
    },
    {
      title: '5. Tanggung Jawab dan Asuransi',
      content: 'Seluruh armada kami dilengkapi dengan asuransi standar. Namun, kerusakan akibat kelalaian berat penyewa (seperti berkendara dalam pengaruh alkohol atau tanpa SIM) menjadi tanggung jawab penuh penyewa.'
    }
  ];

  return (
    <div className="flex flex-col min-h-screen">
      <Navbar />
      
      <main className="flex-grow pt-20">
        <section className="py-24 bg-slate-50">
          <div className="container mx-auto px-4 md:px-6">
            <motion.div 
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              className="max-w-4xl mx-auto"
            >
              <div className="flex items-center gap-4 mb-8 text-emerald-600">
                <FileText size={32} />
                <h1 className="text-4xl md:text-5xl font-display font-bold text-slate-900">Syarat & Ketentuan</h1>
              </div>
              
              <div className="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-slate-100">
                <p className="text-slate-500 mb-10 italic">Terakhir diperbarui: 26 April 2026</p>
                
                <div className="space-y-12">
                  {sections.map((section, idx) => (
                    <div key={idx} className="border-b border-slate-100 last:border-0 pb-10 last:pb-0">
                      <h2 className="text-2xl font-bold text-slate-900 mb-4">{section.title}</h2>
                      <p className="text-lg text-slate-600 leading-relaxed">
                        {section.content}
                      </p>
                    </div>
                  ))}
                </div>

                <div className="mt-16 p-8 bg-emerald-50 rounded-3xl border border-emerald-100">
                  <div className="flex gap-4">
                    <ShieldAlert className="text-emerald-600 shrink-0" size={24} />
                    <div>
                      <h4 className="font-bold text-slate-900 mb-2">Penting untuk Diketahui</h4>
                      <p className="text-slate-600">
                        Syarat dan ketentuan ini dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya. Kami menyarankan Anda untuk memeriksa halaman ini secara berkala.
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </motion.div>
          </div>
        </section>
      </main>

      <Footer />
    </div>
  );
};

export default TermsPage;
