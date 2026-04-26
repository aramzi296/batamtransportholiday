'use client';

import React, { useState } from 'react';
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { motion, AnimatePresence } from 'framer-motion';
import { HelpCircle, ChevronDown, MessageSquare } from 'lucide-react';
import { useLanguage } from '@/context/LanguageContext';

const FAQPage = () => {
  const { dict, language } = useLanguage();

  const faqsID = [
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

  const faqsEN = [
    {
      question: 'How do I book a car rental?',
      answer: 'You can book through our WhatsApp at +62 813-6892-535. Simply inform us of the desired fleet type, rental date, and whether you need a driver or self-drive.'
    },
    {
      question: 'Does the price include fuel (BBM)?',
      answer: 'It depends on the package you choose. We provide options for car rental only, car + driver, and a complete package (Car + Driver + Fuel). Price details will be confirmed upon booking.'
    },
    {
      question: 'How long is the daily rental duration?',
      answer: 'For daily rental with a driver, the maximum duration is 12 hours or until 23:59 on the same day. For self-drive rental, duration is calculated 24 hours per day.'
    },
    {
      question: 'Is airport or harbor pickup available?',
      answer: 'Yes, we provide free airport/seaport transfer services for car rental bookings of at least 3 days. For rentals of less than 3 days, an affordable additional fee will apply.'
    },
    {
      question: 'What if the car has technical issues during the rental?',
      answer: 'Our entire fleet is serviced regularly. However, if technical issues occur, immediately contact our 24/7 assistance team. We will send a mechanic team or provide a replacement car as soon as possible.'
    }
  ];

  const faqs = language === 'id' ? faqsID : faqsEN;
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
                <h1 className="text-4xl md:text-5xl font-display font-bold text-slate-900 mb-6">{dict.faq.title}</h1>
                <p className="text-lg text-slate-600">
                  {dict.faq.description}
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
                  <h3 className="text-2xl font-bold text-white mb-2">{dict.faq.moreQuestions}</h3>
                  <p className="text-emerald-50">{dict.faq.moreQuestionsDesc}</p>
                </div>
                <a 
                  href="https://wa.me/628136892535" 
                  className="bg-white text-emerald-700 px-8 py-4 rounded-2xl font-bold hover:bg-emerald-50 transition-all shadow-lg active:scale-95"
                >
                  {dict.faq.askNow}
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
