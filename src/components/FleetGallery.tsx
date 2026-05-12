'use client';

import React from 'react';
import { motion } from 'framer-motion';
import { useLanguage } from '@/context/LanguageContext';
import { useSettings } from '@/context/SettingsContext';

const FleetGallery = () => {
  const { language } = useLanguage();
  const { settings } = useSettings();
  
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

  return (
    <section id="gallery" className="py-24 bg-white overflow-hidden">
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
              className="relative aspect-square rounded-2xl overflow-hidden group shadow-lg"
            >
              <img
                src={`/images/fleet/${img}`}
                alt={`Fleet ${idx + 1}`}
                className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
              />
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
    </section>
  );
};

export default FleetGallery;
