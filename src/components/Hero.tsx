'use client';

import React from 'react';
import { motion } from 'framer-motion';
import { ArrowRight, CheckCircle2 } from 'lucide-react';

import { useLanguage } from '@/context/LanguageContext';

const Hero = () => {
  const { dict } = useLanguage();

  return (
    <section className="relative min-h-[90vh] flex items-center pt-20 overflow-hidden hero-gradient">
      {/* Decorative Elements */}
      <div className="absolute top-1/4 -right-20 w-96 h-96 bg-emerald-100 rounded-full blur-3xl opacity-50 -z-10" />
      <div className="absolute bottom-1/4 -left-20 w-96 h-96 bg-teal-100 rounded-full blur-3xl opacity-50 -z-10" />
      
      <div className="container mx-auto px-4 md:px-6">
        <div className="flex flex-col lg:flex-row items-center gap-12">
          <div className="flex-1 text-center lg:text-left">
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5 }}
            >
              <span className="inline-block px-4 py-1.5 mb-6 text-sm font-bold tracking-wider text-emerald-700 uppercase bg-emerald-100 rounded-full">
                {dict.hero.badge}
              </span>
              <h1 className="text-4xl md:text-6xl lg:text-7xl font-display font-extrabold text-slate-900 leading-[1.1] mb-6">
                {dict.hero.title} <br />
                <span className="gradient-text">{dict.hero.titleAccent}</span>
              </h1>
              <p className="text-lg md:text-xl text-slate-600 mb-8 max-w-2xl mx-auto lg:mx-0">
                {dict.hero.description}
              </p>

              <div className="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-10">
                <a 
                  href="/#services" 
                  className="flex items-center justify-center gap-2 bg-emerald-600 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:bg-emerald-700 transition-all hover:shadow-xl active:scale-95 group"
                >
                  {dict.hero.cta}
                  <ArrowRight className="group-hover:translate-x-1 transition-transform" />
                </a>
                <a 
                  href="https://wa.me/628136892535" 
                  className="flex items-center justify-center gap-2 bg-white text-slate-900 border border-slate-200 px-8 py-4 rounded-2xl font-bold text-lg hover:bg-slate-50 transition-all shadow-sm active:scale-95"
                >
                  {dict.hero.ctaConsult}
                </a>
              </div>

              <div className="flex flex-wrap justify-center lg:justify-start gap-6 text-sm font-medium text-slate-500">
                {dict.hero.checks.map((check, idx) => (
                  <div key={idx} className="flex items-center gap-2">
                    <CheckCircle2 size={18} className="text-emerald-500" />
                    <span>{check}</span>
                  </div>
                ))}
              </div>
            </motion.div>
          </div>

          <div className="flex-1 relative w-full max-w-2xl">
            <motion.div
              initial={{ opacity: 0, scale: 0.9 }}
              animate={{ opacity: 1, scale: 1 }}
              transition={{ duration: 0.7, delay: 0.2 }}
              className="relative rounded-3xl overflow-hidden shadow-2xl aspect-[4/3] group"
            >
              <img 
                src="/images/transport.png" 
                alt="Luxury Travel Batam" 
                className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent" />
              <div className="absolute bottom-6 left-6 right-6 p-6 glass rounded-2xl">
                <div className="flex items-center justify-between">
                  <div>
                    <p className="text-xs font-bold text-emerald-600 uppercase mb-1">Featured Service</p>
                    <p className="text-xl font-display font-bold text-slate-900">{dict.navbar.transportation}</p>
                  </div>
                  <div className="text-right">
                    <p className="text-xs text-slate-500">{dict.about.stats[0].value === "5+" ? "Mulai dari" : "Starts from"}</p>
                    <p className="text-lg font-bold text-emerald-600">Rp 350rb<span className="text-xs text-slate-400">/{dict.about.stats[0].value === "5+" ? "hari" : "day"}</span></p>
                  </div>
                </div>
              </div>
            </motion.div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default Hero;
