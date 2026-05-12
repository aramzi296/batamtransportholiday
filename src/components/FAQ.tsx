'use client';

import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { HelpCircle, ChevronDown } from 'lucide-react';
import { useLanguage } from '@/context/LanguageContext';

const FAQ = () => {
  const { dict, language } = useLanguage();

  const faqsID = [
    {
      question: 'Apa saja armada yang anda sediakan?',
      answer: 'Kami menyediakan berbagai pilihan armada yang terawat dan siap menempuh perjalanan jauh maupun dekat. Mulai dari mobil keluarga yang nyaman seperti Avanza dan Innova Reborn/Zenix, hingga kendaraan berkapasitas besar untuk grup seperti Toyota Hiace (Commuter & Premio) serta Elf Long. Seluruh unit kami selalu dalam kondisi prima dan didampingi oleh driver berpengalaman untuk menjamin keamanan serta kenyamanan Anda selama di perjalanan'
    },
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
      question: 'What types of fleet do you provide?',
      answer: 'We provide a variety of well-maintained fleet options ready for both long and short journeys. Ranging from comfortable family cars like Avanza and Innova Reborn/Zenix, to large-capacity vehicles for groups such as Toyota Hiace (Commuter & Premio) and Elf Long. All our units are always in prime condition and accompanied by experienced drivers to ensure your safety and comfort during the trip.'
    },
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
    <section id="faq" className="py-24 bg-slate-50 scroll-mt-20">
      <div className="container mx-auto px-4 md:px-6">
        <div className="max-w-3xl mx-auto">
          <div className="text-center mb-16">
            <div className="inline-flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl mb-6">
              <HelpCircle size={32} />
            </div>
            <h2 className="text-3xl md:text-5xl font-display font-bold text-slate-900 mb-6">{dict.faq.title}</h2>
            <p className="text-lg text-slate-600">
              {dict.faq.description}
            </p>
          </div>

          <div className="space-y-4">
            {faqs.map((faq, idx) => (
              <motion.div 
                key={idx}
                initial={{ opacity: 0, y: 10 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: idx * 0.1 }}
                className="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition-shadow"
              >
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
              </motion.div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
};

export default FAQ;
