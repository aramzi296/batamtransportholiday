'use client';

import React, { useRef, useEffect, useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { useLanguage } from '@/context/LanguageContext';
import { X, ChevronLeft, ChevronRight, ZoomIn } from 'lucide-react';

const HappyGuestGallery = () => {
  const { language } = useLanguage();
  const carouselRef = useRef<HTMLDivElement>(null);
  const [selectedImage, setSelectedImage] = useState<number | null>(null);

  // List of images from public/images/happy-guest
  const guestImages = [
    'IMG-20260508-WA0008.jpg',
    'IMG-20260508-WA0009.jpg',
    'IMG-20260508-WA0010.jpg',
    'IMG-20260508-WA0011.jpg',
    'IMG-20260508-WA0012.jpg',
    'IMG-20260508-WA0015.jpg',
    'IMG-20260508-WA0016.jpg',
    'IMG-20260508-WA0018.jpg',
    'IMG-20260508-WA0019.jpg',
    'IMG-20260508-WA0020.jpg',
    'IMG-20260508-WA0021.jpg',
    'IMG-20260508-WA0022.jpg',
    'IMG-20260508-WA0023.jpg',
    'IMG-20260508-WA0024.jpg',
    'IMG-20260508-WA0025.jpg',
    'IMG-20260508-WA0026.jpg',
    'IMG-20260508-WA0027.jpg',
    'IMG-20260508-WA0028.jpg',
    'IMG-20260508-WA0029.jpg',
    'IMG-20260508-WA0030.jpg',
    'IMG-20260508-WA0031.jpg',
    'IMG-20260508-WA0032.jpg',
    'IMG-20260508-WA0033.jpg',
    'IMG-20260508-WA0034.jpg',
    'IMG-20260508-WA0035.jpg',
    'IMG-20260508-WA0036.jpg',
    'IMG-20260508-WA0037.jpg',
    'IMG-20260508-WA0038.jpg',
    'IMG-20260508-WA0039.jpg',
    'IMG-20260508-WA0041.jpg',
    'IMG-20260508-WA0042.jpg',
    'IMG-20260508-WA0043.jpg',
    'IMG-20260508-WA0044.jpg',
    'IMG-20260508-WA0045.jpg',
    'IMG-20260508-WA0046.jpg',
    'IMG-20260508-WA0047.jpg',
    'IMG-20260508-WA0048.jpg',
    'IMG-20260508-WA0049.jpg',
    'IMG-20260508-WA0050.jpg',
    'IMG-20260508-WA0051.jpg',
    'IMG-20260508-WA0052.jpg',
    'IMG-20260508-WA0053.jpg',
    'IMG-20260508-WA0054.jpg',
    'IMG-20260508-WA0055.jpg',
    'IMG-20260508-WA0056.jpg',
    'IMG-20260508-WA0057.jpg',
    'IMG-20260508-WA0058.jpg',
    'IMG-20260508-WA0059.jpg',
    'IMG-20260508-WA0060.jpg',
  ];

  const [canScrollLeft, setCanScrollLeft] = useState(false);
  const [canScrollRight, setCanScrollRight] = useState(true);

  const scroll = (direction: 'left' | 'right') => {
    if (carouselRef.current) {
      const { scrollLeft, clientWidth } = carouselRef.current;
      const scrollTo = direction === 'left' ? scrollLeft - clientWidth : scrollLeft + clientWidth;
      carouselRef.current.scrollTo({ left: scrollTo, behavior: 'smooth' });
    }
  };

  const handleScroll = () => {
    if (carouselRef.current) {
      const { scrollLeft, scrollWidth, clientWidth } = carouselRef.current;
      setCanScrollLeft(scrollLeft > 0);
      setCanScrollRight(scrollLeft < scrollWidth - clientWidth - 10);
    }
  };

  const nextImage = (e: React.MouseEvent) => {
    e.stopPropagation();
    if (selectedImage !== null) {
      setSelectedImage((selectedImage + 1) % guestImages.length);
    }
  };

  const prevImage = (e: React.MouseEvent) => {
    e.stopPropagation();
    if (selectedImage !== null) {
      setSelectedImage((selectedImage - 1 + guestImages.length) % guestImages.length);
    }
  };

  useEffect(() => {
    const el = carouselRef.current;
    if (el) {
      el.addEventListener('scroll', handleScroll);
      handleScroll();
      return () => el.removeEventListener('scroll', handleScroll);
    }
  }, []);

  return (
    <section className="py-24 bg-slate-50 overflow-hidden relative">
      <div className="container mx-auto px-4 md:px-6">
        <div className="text-center max-w-3xl mx-auto mb-16">
          <motion.h2 
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="text-3xl md:text-5xl font-display font-bold text-slate-900 mb-6"
          >
            Happy <span className="text-blue-600">Guest</span>
          </motion.h2>
          <motion.p 
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ delay: 0.1 }}
            className="text-lg text-slate-600"
          >
            {language === 'id' 
              ? 'Koleksi momen bahagia para pelanggan setia kami selama berwisata di Batam.' 
              : 'Collection of happy moments from our loyal customers while traveling in Batam.'}
          </motion.p>
        </div>

        <div className="relative group/carousel">
          <div 
            ref={carouselRef} 
            className="flex flex-nowrap gap-4 md:gap-6 overflow-x-auto no-scrollbar scroll-smooth snap-x snap-mandatory"
            style={{ scrollbarWidth: 'none', msOverflowStyle: 'none' }}
          >
            {guestImages.map((img, idx) => (
              <motion.div 
                key={idx} 
                initial={{ opacity: 0, scale: 0.9 }}
                whileInView={{ opacity: 1, scale: 1 }}
                viewport={{ once: true }}
                transition={{ delay: (idx % 4) * 0.1 }}
                onClick={() => setSelectedImage(idx)}
                className="relative flex-none shrink-0 w-[calc((100%-16px)/2)] md:w-[calc((100%-3*24px)/4)] aspect-[3/4] rounded-2xl md:rounded-[2rem] overflow-hidden bg-slate-200 shadow-xl group cursor-zoom-in snap-start"
              >
                <img 
                  src={`/images/happy-guest/${img}`} 
                  alt={`Happy Guest ${idx + 1}`}
                  className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                  loading="lazy"
                />
                <div className="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center">
                   <div className="bg-white/20 backdrop-blur-md p-3 rounded-full text-white scale-50 group-hover:scale-100 transition-transform duration-500">
                     <ZoomIn size={24} />
                   </div>
                </div>
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-6">
                  <p className="text-white font-medium text-sm translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                    Batam Transport Holiday
                  </p>
                </div>
              </motion.div>
            ))}
          </div>

          <div className="mt-12 flex justify-center gap-6">
            <button 
              onClick={() => scroll('left')}
              disabled={!canScrollLeft}
              className={`w-14 h-14 rounded-full border border-slate-200 flex items-center justify-center transition-all ${canScrollLeft ? 'bg-white text-slate-900 hover:bg-blue-600 hover:text-white shadow-lg scale-110' : 'bg-slate-100 text-slate-300 cursor-not-allowed'}`}
            >
              <ChevronLeft size={28} />
            </button>
            <button 
              onClick={() => scroll('right')}
              disabled={!canScrollRight}
              className={`w-14 h-14 rounded-full border border-slate-200 flex items-center justify-center transition-all ${canScrollRight ? 'bg-white text-slate-900 hover:bg-blue-600 hover:text-white shadow-lg scale-110' : 'bg-slate-100 text-slate-300 cursor-not-allowed'}`}
            >
              <ChevronRight size={28} />
            </button>
          </div>
        </div>
      </div>

      <AnimatePresence>
        {selectedImage !== null && (
          <motion.div 
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 z-[9999] bg-black/95 flex items-center justify-center p-4 md:p-12"
            onClick={() => setSelectedImage(null)}
          >
            <button 
              className="absolute top-6 right-6 text-white/70 hover:text-white transition-colors z-10"
              onClick={() => setSelectedImage(null)}
            >
              <X size={40} />
            </button>

            <button 
              className="absolute left-4 md:left-10 top-1/2 -translate-y-1/2 w-12 h-12 md:w-16 md:h-16 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all"
              onClick={prevImage}
            >
              <ChevronLeft size={32} />
            </button>

            <button 
              className="absolute right-4 md:right-10 top-1/2 -translate-y-1/2 w-12 h-12 md:w-16 md:h-16 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all"
              onClick={nextImage}
            >
              <ChevronRight size={32} />
            </button>
            
            <motion.img 
              key={selectedImage}
              initial={{ scale: 0.8, opacity: 0 }}
              animate={{ scale: 1, opacity: 1 }}
              exit={{ scale: 0.8, opacity: 0 }}
              src={`/images/happy-guest/${guestImages[selectedImage]}`}
              className="max-w-full max-h-full object-contain rounded-xl shadow-2xl"
              onClick={(e) => e.stopPropagation()}
            />

            <div className="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/50 text-sm font-medium">
              {selectedImage + 1} / {guestImages.length}
            </div>
          </motion.div>
        )}
      </AnimatePresence>
        
      <div className="absolute top-1/2 -left-4 w-24 h-24 bg-blue-600/5 blur-3xl rounded-full -translate-y-1/2 pointer-events-none" />
      <div className="absolute top-1/2 -right-4 w-24 h-24 bg-blue-600/5 blur-3xl rounded-full -translate-y-1/2 pointer-events-none" />
    </section>
  );
};

export default HappyGuestGallery;
