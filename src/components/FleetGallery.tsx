'use client';

import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { useLanguage } from '@/context/LanguageContext';
import { useSettings } from '@/context/SettingsContext';
import { X, ChevronLeft, ChevronRight, ZoomIn } from 'lucide-react';

const FleetGallery = () => {
  const { language } = useLanguage();
  const { settings } = useSettings();
  const [selectedImage, setSelectedImage] = useState<number | null>(null);
  
  const fleetImages = [
    'armada.jpg',
    'mobil-barelang.jpg',
    'mobil-tanjak.jpg',
    'IMG-20260508-WA0017.jpg',
    'IMG-20260508-WA0066.jpg',
    'IMG-20260508-WA0074.jpg',
    'IMG-20260508-WA0077.jpg',
    'IMG-20260508-WA0079.jpg',
    'IMG-20260508-WA0080.jpg',
    'IMG-20260508-WA0083.jpg',
    'IMG-20260508-WA0085.jpg',
    'IMG-20260508-WA0089.jpg',
  ];

  const nextImage = (e: React.MouseEvent) => {
    e.stopPropagation();
    if (selectedImage !== null) {
      setSelectedImage((selectedImage + 1) % fleetImages.length);
    }
  };

  const prevImage = (e: React.MouseEvent) => {
    e.stopPropagation();
    if (selectedImage !== null) {
      setSelectedImage((selectedImage - 1 + fleetImages.length) % fleetImages.length);
    }
  };

  return (
    <section id="gallery" className="py-24 bg-white overflow-hidden relative">
      <div className="container mx-auto px-4 md:px-6">
        <div className="text-center max-w-3xl mx-auto mb-16">
          <h2 className="text-3xl md:text-5xl font-display font-bold text-slate-900 mb-6">
            {language === 'id' ? 'Galeri' : 'Our'} <span className="text-blue-600">{language === 'id' ? 'Armada Kami' : 'Fleet Gallery'}</span>
          </h2>
          <p className="text-lg text-slate-600">
            {language === 'id' 
              ? 'Lihat koleksi kendaraan kami yang selalu siap menemani perjalanan Anda di Batam.' 
              : 'Take a look at our collection of vehicles that are always ready to accompany your journey in Batam.'}
          </p>
        </div>

        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
          {fleetImages.map((img, idx) => (
            <motion.div
              key={idx}
              initial={{ opacity: 0, scale: 0.9 }}
              whileInView={{ opacity: 1, scale: 1 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: idx * 0.1 }}
              onClick={() => setSelectedImage(idx)}
              className="relative aspect-square rounded-2xl overflow-hidden group shadow-lg cursor-zoom-in"
            >
              <img
                src={`/images/fleet/${img}`}
                alt={`Fleet ${idx + 1}`}
                className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
              />
              <div className="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                 <div className="bg-white/20 backdrop-blur-md p-3 rounded-full text-white scale-50 group-hover:scale-100 transition-transform duration-300">
                   <ZoomIn size={24} />
                 </div>
              </div>
              <div className="absolute inset-0 bg-blue-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
            </motion.div>
          ))}
        </div>
        
        <div className="mt-16 text-center">
          <p className="text-slate-500 italic mb-8">
            {language === 'id' 
              ? 'Dan masih banyak armada lainnya...' 
              : 'And many more vehicles in our fleet...'}
          </p>
          <a 
            href="/#contact" 
            className="inline-flex items-center gap-2 bg-blue-600 text-white px-10 py-5 rounded-2xl font-bold text-lg hover:bg-blue-700 transition-all shadow-xl active:scale-95"
          >
            {language === 'id' ? 'Tanya Ketersediaan Armada' : 'Check Fleet Availability'}
          </a>
        </div>
      </div>

      {/* Lightbox for Fleet */}
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
              src={`/images/fleet/${fleetImages[selectedImage]}`}
              className="max-w-full max-h-full object-contain rounded-xl shadow-2xl"
              onClick={(e) => e.stopPropagation()}
            />

            <div className="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/50 text-sm font-medium">
              {selectedImage + 1} / {fleetImages.length}
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </section>
  );
};

export default FleetGallery;
