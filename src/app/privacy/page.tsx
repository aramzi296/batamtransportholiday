'use client';

import React from 'react';
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { motion } from 'framer-motion';
import { ShieldCheck, Lock, EyeOff, UserCheck } from 'lucide-react';

const PrivacyPage = () => {
  const sections = [
    {
      icon: UserCheck,
      title: 'Informasi yang Kami Kumpulkan',
      content: 'Kami mengumpulkan informasi pribadi seperti nama, nomor telepon, alamat email, dan identitas (SIM/KTP) hanya untuk keperluan pemesanan layanan dan verifikasi keamanan.'
    },
    {
      icon: Lock,
      title: 'Keamanan Data',
      content: 'Kami menerapkan standar keamanan data yang ketat untuk melindungi informasi pribadi Anda dari akses yang tidak sah, pengubahan, atau pengungkapan.'
    },
    {
      icon: EyeOff,
      title: 'Penggunaan Informasi',
      content: 'Data Anda digunakan semata-mata untuk memproses pemesanan, memberikan layanan pelanggan, dan mengirimkan informasi terkait layanan kami. Kami tidak menjual data Anda kepada pihak ketiga.'
    },
    {
      icon: ShieldCheck,
      title: 'Hak-Hak Anda',
      content: 'Anda memiliki hak untuk meminta akses ke data pribadi Anda, melakukan pembaruan, atau meminta penghapusan data yang kami simpan setelah masa layanan berakhir.'
    }
  ];

  return (
    <div className="flex flex-col min-h-screen">
      <Navbar />
      
      <main className="flex-grow pt-20">
        <section className="py-24 bg-white">
          <div className="container mx-auto px-4 md:px-6">
            <motion.div 
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              className="max-w-4xl mx-auto"
            >
              <div className="text-center mb-16">
                <div className="inline-flex items-center justify-center w-20 h-20 bg-emerald-100 text-emerald-600 rounded-3xl mb-6">
                  <ShieldCheck size={40} />
                </div>
                <h1 className="text-4xl md:text-5xl font-display font-bold text-slate-900 mb-6">Kebijakan Privasi</h1>
                <p className="text-xl text-slate-600">
                  Komitmen kami dalam menjaga dan melindungi privasi data setiap pelanggan.
                </p>
              </div>
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                {sections.map((section, idx) => (
                  <div key={idx} className="bg-slate-50 p-10 rounded-[2rem] border border-slate-100 hover:shadow-lg transition-shadow">
                    <section.icon className="text-emerald-600 mb-6" size={32} />
                    <h2 className="text-2xl font-bold text-slate-900 mb-4">{section.title}</h2>
                    <p className="text-slate-600 leading-relaxed">
                      {section.content}
                    </p>
                  </div>
                ))}
              </div>

              <div className="mt-16 p-12 bg-slate-900 rounded-[3rem] text-center text-white relative overflow-hidden">
                <div className="absolute top-0 right-0 w-64 h-64 bg-emerald-600/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl" />
                <h3 className="text-2xl font-bold mb-4">Hubungi Tim Keamanan Kami</h3>
                <p className="text-slate-400 mb-8 max-w-xl mx-auto">
                  Jika Anda memiliki pertanyaan mengenai cara kami menangani data Anda, jangan ragu untuk menghubungi kami.
                </p>
                <a 
                  href="mailto:privacy@dsarana.com" 
                  className="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-xl font-bold transition-all"
                >
                  Kirim Email
                </a>
              </div>
            </motion.div>
          </div>
        </section>
      </main>

      <Footer />
    </div>
  );
};

export default PrivacyPage;
