'use client';

import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { useLanguage } from '@/context/LanguageContext';
import { Star, Quote, ChevronLeft, ChevronRight } from 'lucide-react';

const Testimonials = () => {
  const { language } = useLanguage();
  const [currentIndex, setCurrentIndex] = useState(0);

  const testimonialsID = [
    {
      name: "Ahmad Faisal",
      country: "Malaysia",
      image: "ahmad-faisal-malaysia.webp",
      text: "Layanan sangat memuaskan! Supir ramah dan sangat paham jalan di Batam. Liburan keluarga jadi jauh lebih mudah."
    },
    {
      name: "Jane & Zul",
      country: "Malaysia",
      image: "jane-and-zul-malaysia.webp",
      text: "Harga sewa sangat terjangkau dengan kualitas mobil yang bersih dan nyaman. Pasti akan pakai Batam Transport Holiday lagi!"
    },
    {
      name: "Kak Lena",
      country: "Malaysia",
      image: "kak-lena-malaysia.webp",
      text: "Terima kasih untuk pelayanannya. Perjalanan bisnis saya di Batam lancar tanpa kendala. Sangat direkomendasikan!"
    },
    {
      name: "Kevin Lim",
      country: "Singapore",
      image: "kevin-lim-singapore.webp",
      text: "Sangat profesional. Penjemputan tepat waktu di pelabuhan dan mobil dalam kondisi prima. Pengalaman yang luar biasa."
    },
    {
      name: "Lee Wei",
      country: "Singapore",
      image: "lee-wei-singapore.webp",
      text: "Paket wisatanya luar biasa! Kami diajak ke tempat-tempat makan enak dan destinasi wisata terbaik di Batam. Terima kasih!"
    },
    {
      name: "Mr. Tan",
      country: "Singapore",
      image: "mr.tan-singapore.webp",
      text: "Respon customer service sangat cepat dan informatif. Supir juga bisa berbahasa Inggris dengan baik, sangat membantu kami."
    },
    {
      name: "Sarah Tan",
      country: "Singapore",
      image: "sarah-tan-singapore.webp",
      text: "Mobil yang disediakan sangat nyaman dan wangi. Anak-anak saya sangat senang selama perjalanan keliling kota."
    }
  ];

  const testimonialsEN = [
    {
      name: "Ahmad Faisal",
      country: "Malaysia",
      image: "ahmad-faisal-malaysia.webp",
      text: "Very satisfying service! The driver was friendly and knew his way around Batam. Made our family holiday so much easier."
    },
    {
      name: "Jane & Zul",
      country: "Malaysia",
      image: "jane-and-zul-malaysia.webp",
      text: "Very affordable rental prices with clean and comfortable cars. Will definitely use Batam Transport Holiday again!"
    },
    {
      name: "Kak Lena",
      country: "Malaysia",
      image: "kak-lena-malaysia.webp",
      text: "Thank you for the excellent service. My business trip in Batam went smoothly without any issues. Highly recommended!"
    },
    {
      name: "Kevin Lim",
      country: "Singapore",
      image: "kevin-lim-singapore.webp",
      text: "Very professional. On-time pick up at the ferry terminal and the car was in prime condition. An amazing experience."
    },
    {
      name: "Lee Wei",
      country: "Singapore",
      image: "lee-wei-singapore.webp",
      text: "The tour package was amazing! We were taken to great food spots and the best tourist destinations in Batam. Thank you!"
    },
    {
      name: "Mr. Tan",
      country: "Singapore",
      image: "mr.tan-singapore.webp",
      text: "Customer service response was very fast and informative. The driver could also speak English well, which was very helpful to us."
    },
    {
      name: "Sarah Tan",
      country: "Singapore",
      image: "sarah-tan-singapore.webp",
      text: "The provided car was very comfortable and smelled great. My children were very happy during the city tour."
    }
  ];

  const testimonials = language === 'id' ? testimonialsID : testimonialsEN;

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentIndex((prevIndex) => (prevIndex + 1) % testimonials.length);
    }, 5000); // 5 seconds auto-play

    return () => clearInterval(timer);
  }, [testimonials.length]);

  const handleNext = () => {
    setCurrentIndex((prevIndex) => (prevIndex + 1) % testimonials.length);
  };

  const handlePrev = () => {
    setCurrentIndex((prevIndex) => (prevIndex - 1 + testimonials.length) % testimonials.length);
  };

  return (
    <section className="py-24 bg-white relative overflow-hidden">
      <div className="container mx-auto px-4 md:px-6">
        <div className="text-center max-w-3xl mx-auto mb-16">
          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="inline-flex items-center justify-center w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl mb-6"
          >
            <Quote size={32} />
          </motion.div>
          <motion.h2 
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="text-3xl md:text-5xl font-display font-bold text-slate-900 mb-6"
          >
            {language === 'id' ? 'Apa Kata' : 'What Our'} <span className="text-blue-600">{language === 'id' ? 'Mereka?' : 'Clients Say'}</span>
          </motion.h2>
          <motion.p 
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ delay: 0.1 }}
            className="text-lg text-slate-600"
          >
            {language === 'id' 
              ? 'Pengalaman nyata dari tamu-tamu kami yang telah mempercayakan perjalanan mereka di Batam bersama kami.' 
              : 'Real experiences from our guests who have trusted their journey in Batam with us.'}
          </motion.p>
        </div>

        <div className="relative max-w-4xl mx-auto">
          <div className="overflow-hidden relative px-4 md:px-16">
            <AnimatePresence mode="wait">
              <motion.div
                key={currentIndex}
                initial={{ opacity: 0, x: 50 }}
                animate={{ opacity: 1, x: 0 }}
                exit={{ opacity: 0, x: -50 }}
                transition={{ duration: 0.4 }}
                className="bg-slate-50 rounded-3xl p-8 md:p-12 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center gap-8 md:gap-12"
              >
                <div className="w-32 h-32 md:w-48 md:h-48 shrink-0 relative rounded-full overflow-hidden border-4 border-white shadow-xl">
                  <img 
                    src={`/images/testimoni/${testimonials[currentIndex].image}`} 
                    alt={testimonials[currentIndex].name}
                    className="w-full h-full object-cover"
                  />
                </div>
                
                <div className="flex-1 text-center md:text-left">
                  <div className="flex justify-center md:justify-start gap-1 mb-6 text-amber-400">
                    {[...Array(5)].map((_, i) => (
                      <Star key={i} size={24} fill="currentColor" />
                    ))}
                  </div>
                  <blockquote className="text-xl md:text-2xl font-medium text-slate-800 mb-8 italic">
                    "{testimonials[currentIndex].text}"
                  </blockquote>
                  <div>
                    <div className="font-bold text-slate-900 text-lg">{testimonials[currentIndex].name}</div>
                    <div className="text-blue-600">{testimonials[currentIndex].country}</div>
                  </div>
                </div>
              </motion.div>
            </AnimatePresence>
          </div>

          <button 
            onClick={handlePrev}
            className="absolute left-0 top-1/2 -translate-y-1/2 w-12 h-12 bg-white rounded-full shadow-lg border border-slate-100 flex items-center justify-center text-slate-600 hover:text-blue-600 hover:scale-110 transition-all z-10 hidden md:flex"
          >
            <ChevronLeft size={24} />
          </button>
          
          <button 
            onClick={handleNext}
            className="absolute right-0 top-1/2 -translate-y-1/2 w-12 h-12 bg-white rounded-full shadow-lg border border-slate-100 flex items-center justify-center text-slate-600 hover:text-blue-600 hover:scale-110 transition-all z-10 hidden md:flex"
          >
            <ChevronRight size={24} />
          </button>

          <div className="flex justify-center gap-3 mt-8">
            {testimonials.map((_, idx) => (
              <button
                key={idx}
                onClick={() => setCurrentIndex(idx)}
                className={`w-3 h-3 rounded-full transition-all ${idx === currentIndex ? 'bg-blue-600 w-8' : 'bg-slate-300 hover:bg-blue-400'}`}
                aria-label={`Go to slide ${idx + 1}`}
              />
            ))}
          </div>
        </div>
      </div>
    </section>
  );
};

export default Testimonials;
