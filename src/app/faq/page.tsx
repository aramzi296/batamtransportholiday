'use client';

import React, { useState } from 'react';
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { motion, AnimatePresence } from 'framer-motion';
import { HelpCircle, ChevronDown, MessageSquare } from 'lucide-react';

const FAQPage = () => {
  const faqs = [
    {
      question: 'Bagaimana cara melakukan pemesanan rental mobil?',
      answer: 'Anda dapat melakukan pemesanan melalui WhatsApp kami di nomor +62 813-6892-535. Cukup informasikan jenis armada yang diinginkan, tanggal sewa, dan apakah Anda membutuhkan driver atau lepas kunci.'
    },
    {
      question: 'Apakah harga sudah termasuk bahan bakar (BBM)?',
      answer: 'Tergantung paket yang Anda pilih. Kami menyediakan opsi sewa mobil saja, sewa mobil + driver, serta paket lengkap (Mobil + Driver + BBM). Detail harga akan dikonfirmasi saat pemesanan.'
    },
    {
      question: 'Berapa lama durasi sewa harian?',
      answer: 'Untuk sewa harian dengan driver, durasi maksimal adalah 12 jam atau hingga pukul 23:59 pada hari yang sama. Untuk sewa lepas kunci, durasi dihitung 24 jam per hari.'
    },
    {
      question: 'Apakah bisa melakukan penjemputan di Bandara atau Pelabuhan?',
      answer: 'Ya, kami melayani jasa antar jemput (airport/seaport transfer) secara gratis untuk pemesanan sewa mobil minimal 3 hari. Untuk sewa kurang dari 3 hari, akan dikenakan biaya tambahan yang terjangkau.'
    },
    {
      question: 'Bagaimana jika mobil mengalami kendala teknis saat disewa?',
      answer: 'Seluruh armada kami rutin diservis. Namun, jika terjadi kendala teknis, segera hubungi tim bantuan 24/7 kami. Kami akan mengirimkan tim mekanik atau menyediakan mobil pengganti sesegera mungkin.'
    }
  ];

  const [activeIndex, setActiveIndex] = useState<number | null>(0);

  return (
    <div className="flex flex-col min-h-screen">
      <Navbar />
      
      <main className="flex-grow pt-20">
        <section className="py-24 bg-slate-50">
          <div className="container mx-auto px-4 md:px-6">
            <div className="max-w-3xl mx-auto">
              <div className="text-center mb-16">
                <div className="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl mb-6">
                  <HelpCircle size={32} />
                </div>
                <h1 className="text-4xl md:text-5xl font-display font-bold text-slate-900 mb-6">Pertanyaan Umum</h1>
                <p className="text-lg text-slate-600">
                  Temukan jawaban cepat untuk pertanyaan yang sering diajukan pelanggan kami.
                </p>
              </div>

              <div className="space-y-4">
                {faqs.map((faq, idx) => (
                  <div key={idx} className="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <button 
                      onClick={() => setActiveIndex(activeIndex === idx ? null : idx)}
                      className="w-full flex items-center justify-between p-6 md:p-8 text-left focus:outline-none"
                    >
                      <span className="text-xl font-bold text-slate-900 pr-8">{faq.question}</span>
                      <motion.div
                        animate={{ rotate: activeIndex === idx ? 180 : 0 }}
                        transition={{ duration: 0.3 }}
                        className="text-emerald-600 shrink-0"
                      >
                        <ChevronDown size={24} />
                      </motion.div>
                    </button>
                    
                    <AnimatePresence>
                      {activeIndex === idx && (
                        <motion.div
                          initial={{ height: 0, opacity: 0 }}
                          animate={{ height: 'auto', opacity: 1 }}
                          exit={{ height: 0, opacity: 0 }}
                          transition={{ duration: 0.3 }}
                        >
                          <div className="px-6 md:px-8 pb-8 text-lg text-slate-600 leading-relaxed border-t border-slate-50 pt-4">
                            {faq.answer}
                          </div>
                        </motion.div>
                      )}
                    </AnimatePresence>
                  </div>
                ))}
              </div>

              <div className="mt-20 p-10 bg-emerald-600 rounded-[2.5rem] flex flex-col md:flex-row items-center gap-8 shadow-xl">
                <div className="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-white shrink-0">
                  <MessageSquare size={32} />
                </div>
                <div className="text-center md:text-left flex-grow">
                  <h3 className="text-2xl font-bold text-white mb-2">Punya pertanyaan lain?</h3>
                  <p className="text-emerald-50">Tim kami siap membantu Anda 24 jam setiap hari.</p>
                </div>
                <a 
                  href="https://wa.me/628136892535" 
                  className="bg-white text-emerald-700 px-8 py-4 rounded-2xl font-bold hover:bg-emerald-50 transition-all shadow-lg active:scale-95"
                >
                  Tanya Sekarang
                </a>
              </div>
            </div>
          </div>
        </section>
      </main>

      <Footer />
    </div>
  );
};

export default FAQPage;
