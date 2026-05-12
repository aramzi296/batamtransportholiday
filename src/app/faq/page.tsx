'use client';

import React, { useState } from 'react';
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { motion, AnimatePresence } from 'framer-motion';
import { HelpCircle, ChevronDown, MessageSquare } from 'lucide-react';
import { useLanguage } from '@/context/LanguageContext';
import { useSettings } from '@/context/SettingsContext';

const FAQPage = () => {
  const { dict, language } = useLanguage();
  const { settings } = useSettings();

  const faqsID = [
    {
      question: 'Bagaimana cara melakukan pemesanan rental mobil?',
      answer: 'Pemesanan dapat dilakukan dengan mudah melalui WhatsApp kami. Kami siap mendiskusikan paket yang affordable dan sesuai dengan kebutuhan perjalanan Anda di Batam.'
    },
    {
      question: 'Apakah harga sudah termasuk bahan bakar (BBM)?',
      answer: 'Ya, semua paket layanan kami sudah termasuk bahan bakar (BBM). Anda tidak perlu memikirkan biaya tambahan untuk pengisian bahan bakar, cukup duduk manis dan nikmati perjalanan Anda bersama kami.'
    },
    {
      question: 'Berapa lama durasi sewa harian?',
      answer: 'Untuk sewa harian dengan driver, durasi maksimal penggunaan adalah 12 jam atau hingga pukul 23:59 pada hari yang sama.'
    },
    {
      question: 'Apakah bisa melakukan penjemputan di Bandara atau Pelabuhan?',
      answer: 'Layanan sewa mobil kami sudah termasuk fasilitas penjemputan dan pengantaran ke pelabuhan atau bandara di Batam. Namun, jika Anda hanya membutuhkan jasa antar-jemput saja (transfer only) tanpa paket sewa harian, maka akan dikenakan biaya tersendiri yang kompetitif.'
    },
    {
      question: 'Apakah ada biaya lain yang perlu ditambahkan?',
      answer: 'Tidak ada. Biaya BBM dan parkir sudah termasuk dalam paket kami. Driver kami juga dilarang keras meminta biaya tambahan kepada tamu. Namun, perlu diperhatikan bahwa jika destinasi wisata yang dituju memerlukan biaya tiket masuk, maka biaya tersebut menjadi tanggung jawab tamu.'
    },
    {
      question: 'Bagaimana jika mobil mengalami kendala teknis saat disewa?',
      answer: 'Kami sangat mengutamakan kenyamanan Anda dan tidak ingin perjalanan Anda terganggu. Seluruh armada kami selalu dirawat secara rutin, namun jika terjadi kendala teknis di jalan, kami akan segera menyediakan kendaraan pengganti agar Anda dapat melanjutkan perjalanan dengan lancar.'
    }
  ];

  const faqsEN = [
    {
      question: 'How do I book a car rental?',
      answer: 'Bookings can be easily made through our WhatsApp. We are ready to discuss affordable packages that suit your travel needs in Batam.'
    },
    {
      question: 'Does the price include fuel (BBM)?',
      answer: 'Yes, all our service packages already include fuel (BBM). You don\'t need to worry about additional refueling costs; simply sit back and enjoy your journey with us.'
    },
    {
      question: 'How long is the daily rental duration?',
      answer: 'For daily rental with a driver, the maximum duration of use is 12 hours or until 23:59 on the same day.'
    },
    {
      question: 'Is airport or harbor pickup available?',
      answer: 'Our car rental service already includes pickup and drop-off facilities to the harbor or airport in Batam. However, if you only require transfer services (transfer only) without a daily rental package, a separate competitive fee will apply.'
    },
    {
      question: 'What if the car has technical issues during the rental?',
      answer: 'We prioritize your comfort and do not want your journey to be interrupted. While our entire fleet is regularly maintained, if any technical issues occur on the road, we will immediately provide a replacement vehicle so you can continue your journey smoothly.'
    },
    {
      question: 'Are there any other additional fees?',
      answer: 'None. Fuel and parking fees are already included in our packages. Our drivers are also strictly prohibited from asking for additional fees from guests. However, please note that if the destination requires an entry ticket fee, that is the responsibility of the guest.'
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
                <div className="inline-flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl mb-6">
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
                        className="text-blue-600 shrink-0"
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

              <div className="mt-20 p-10 bg-blue-600 rounded-[2.5rem] flex flex-col md:flex-row items-center gap-8 shadow-xl">
                <div className="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-white shrink-0">
                  <MessageSquare size={32} />
                </div>
                <div className="text-center md:text-left flex-grow">
                  <h3 className="text-2xl font-bold text-white mb-2">{dict.faq.moreQuestions}</h3>
                  <p className="text-blue-50">{dict.faq.moreQuestionsDesc}</p>
                </div>
                <a 
                  href={`https://wa.me/${settings.whatsappNumber}`} 
                  className="bg-white text-blue-700 px-8 py-4 rounded-2xl font-bold hover:bg-blue-50 transition-all shadow-lg active:scale-95"
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
