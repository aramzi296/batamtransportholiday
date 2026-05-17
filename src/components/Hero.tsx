'use client';

import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { ArrowRight, CheckCircle2, Star, Users, MapPin, X } from 'lucide-react';

import { useLanguage } from '@/context/LanguageContext';
import { useSettings } from '@/context/SettingsContext';

const Hero = () => {
  const { dict, language } = useLanguage();
  const { settings } = useSettings();
  const [isImageOpen, setIsImageOpen] = useState(false);

  return (
    <section className="relative min-h-screen flex items-center pt-20 overflow-hidden bg-slate-50">
      {/* Background Pattern */}
      <div className="absolute inset-0 z-0 opacity-[0.03] pointer-events-none"
        style={{ backgroundImage: 'radial-gradient(#10b981 1px, transparent 1px)', backgroundSize: '40px 40px' }} />

      {/* Animated Blobs */}
      <div className="absolute top-1/4 -right-20 w-[500px] h-[500px] bg-blue-200/30 rounded-full blur-[100px] animate-pulse -z-10" />
      <div className="absolute bottom-1/4 -left-20 w-[500px] h-[500px] bg-cyan-200/20 rounded-full blur-[100px] animate-pulse -z-10" />

      <div className="container mx-auto px-4 md:px-6 relative z-10">
        <div className="flex flex-col lg:flex-row items-center gap-16">
          <div className="flex-1 text-center lg:text-left">
            <motion.div
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8 }}
            >
              <div className="inline-flex items-center gap-2 px-4 py-2 mb-8 text-sm font-bold tracking-wider text-blue-700 uppercase bg-blue-100/80 backdrop-blur-sm rounded-full border border-blue-200/50 shadow-sm">
                <Star size={16} className="fill-blue-700" />
                {dict.hero.badge}
              </div>
              <h1 className="text-5xl md:text-7xl lg:text-8xl font-display font-extrabold text-slate-900 leading-[1.05] mb-8">
                {dict.hero.title} <br />
                <span className="gradient-text">{dict.hero.titleAccent}</span>
              </h1>
              <p className="text-xl md:text-2xl text-slate-600 mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                {dict.hero.description}
              </p>

              <div className="flex flex-col sm:flex-row gap-5 justify-center lg:justify-start mb-12">
                <a
                  href="#faq"
                  className="flex items-center justify-center gap-3 bg-blue-600 text-white px-10 py-5 rounded-[2rem] font-bold text-xl hover:bg-blue-700 transition-all hover:shadow-2xl hover:shadow-blue-200 active:scale-95 group"
                >
                  {dict.hero.cta}
                  <ArrowRight className="group-hover:translate-x-2 transition-transform" />
                </a>
                <a
                  href={`https://wa.me/${settings.whatsappNumber}`}
                  className="flex items-center justify-center gap-3 bg-white text-slate-900 border border-slate-200 px-10 py-5 rounded-[2rem] font-bold text-xl hover:bg-slate-50 transition-all shadow-xl shadow-slate-200/50 active:scale-95"
                >
                  {dict.hero.ctaConsult}
                </a>
              </div>

              <div className="flex flex-wrap justify-center lg:justify-start gap-8 text-base font-semibold text-slate-500">
                {dict.hero.checks.map((check, idx) => (
                  <div key={idx} className="flex items-center gap-3">
                    <div className="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center shrink-0">
                      <CheckCircle2 size={16} className="text-blue-600" />
                    </div>
                    <span>{check}</span>
                  </div>
                ))}
              </div>
            </motion.div>
          </div>

          <div className="flex-1 relative w-full max-w-2xl">
            <motion.div
              initial={{ opacity: 0, scale: 0.8, rotate: -2 }}
              animate={{ opacity: 1, scale: 1, rotate: 0 }}
              transition={{ duration: 1, delay: 0.2 }}
              className="relative rounded-[3rem] overflow-hidden shadow-[0_32px_64px_-16px_rgba(0,0,0,0.2)] aspect-[4/5] md:aspect-square group cursor-zoom-in"
              onClick={() => setIsImageOpen(true)}
            >
              <img
                src="/images/fleet/mobil-barelang.jpg"
                // src="/images/fleet/armada.jpg"
                //src="/images/fleet/mobil-tanjak.jpg"
                alt="Luxury Travel Batam"
                className="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent" />

              {/* Floating Cards */}
              <motion.div
                initial={{ opacity: 0, x: 20 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ delay: 1, duration: 0.5 }}
                className="absolute top-10 -right-8 glass p-6 rounded-[2rem] shadow-2xl hidden md:block border-white/40"
              >
                <div className="flex items-center gap-4">
                  <div className="w-12 h-12 bg-blue-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                    <Users size={24} />
                  </div>
                  <div>
                    <p className="text-2xl font-bold text-slate-900">1000+</p>
                    <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">{language === 'id' ? 'Pelanggan Puas' : 'Happy Clients'}</p>
                  </div>
                </div>
              </motion.div>

              <motion.div
                initial={{ opacity: 0, x: -20 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ delay: 1.2, duration: 0.5 }}
                className="absolute bottom-10 -left-8 glass p-6 rounded-[2rem] shadow-2xl hidden md:block border-white/40"
              >
                <div className="flex items-center gap-4">
                  <div className="w-12 h-12 bg-amber-400 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-100">
                    <MapPin size={24} />
                  </div>
                  <div>
                    <p className="text-2xl font-bold text-slate-900">25+</p>
                    <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">{language === 'id' ? 'Destinasi Wisata' : 'Destinations'}</p>
                  </div>
                </div>
              </motion.div>

              <div className="absolute bottom-8 left-1/2 -translate-x-1/2 w-[85%] glass p-6 rounded-[2.5rem] border-white/40">
                <div className="flex items-center justify-between">
                  <div>
                    <p className="text-xs font-bold text-blue-600 uppercase mb-1 tracking-widest">Premium Choice</p>
                    <p className="text-2xl font-display font-bold text-slate-900">{dict.navbar.transportation}</p>
                  </div>
                  <div className="text-right">
                    <p className="text-xs text-slate-500 font-bold uppercase">{language === 'id' ? 'Mulai' : 'From'}</p>
                    <p className="text-2xl font-bold text-blue-600">Rp 350k</p>
                  </div>
                </div>
              </div>
            </motion.div>
          </div>
        </div>
      </div>

      {/* Full Image Modal */}
      <AnimatePresence>
        {isImageOpen && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={() => setIsImageOpen(false)}
            className="fixed inset-0 z-[100] bg-slate-900/90 backdrop-blur-xl flex items-center justify-center p-4 md:p-10 cursor-zoom-out"
          >
            <motion.button
              initial={{ scale: 0, rotate: -90 }}
              animate={{ scale: 1, rotate: 0 }}
              exit={{ scale: 0, rotate: 90 }}
              className="absolute top-6 right-6 text-white/50 hover:text-white transition-colors"
              onClick={() => setIsImageOpen(false)}
            >
              <X size={40} />
            </motion.button>

            <motion.div
              initial={{ scale: 0.9, opacity: 0, y: 20 }}
              animate={{ scale: 1, opacity: 1, y: 0 }}
              exit={{ scale: 0.9, opacity: 0, y: 20 }}
              transition={{ type: "spring", damping: 25, stiffness: 300 }}
              className="relative max-w-7xl max-h-[90vh] flex items-center justify-center"
              onClick={(e) => e.stopPropagation()}
            >
              <img
                src="/images/fleet/mobil-barelang.jpg"
                alt="Batam Transport Full View"
                className="max-w-full max-h-[90vh] w-auto h-auto object-contain rounded-2xl shadow-2xl"
              />
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
    </section>
  );
};

export default Hero;
