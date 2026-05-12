'use client';

import React from 'react';
import { motion } from 'framer-motion';
import { useLanguage } from '@/context/LanguageContext';

const AboutUs = () => {
  const { dict, language } = useLanguage();

  return (
    <section id="about" className="py-24 bg-white scroll-mt-20">
      <div className="container mx-auto px-4 md:px-6">
        <div className="flex flex-col lg:flex-row items-center gap-16 mb-24">
          <div className="flex-1">
            <motion.div
              initial={{ opacity: 0, x: -30 }}
              whileInView={{ opacity: 1, x: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.8 }}
              className="relative"
            >
              <img
                src="/images/barelang.png"
                alt="Our Story"
                className="rounded-[3rem] shadow-2xl relative z-10 w-full"
              />
              <div className="absolute -bottom-6 -right-6 w-full h-full border-4 border-blue-100 rounded-[3rem] -z-10" />
            </motion.div>
          </div>
          <div className="flex-1">
            <motion.div
              initial={{ opacity: 0, x: 30 }}
              whileInView={{ opacity: 1, x: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.8 }}
            >
              <span className="text-blue-600 font-bold tracking-widest uppercase text-sm mb-4 block">{dict.about.subtitle}</span>
              <h2 className="text-3xl md:text-5xl font-display font-bold text-slate-900 mb-8">
                {dict.about.storyTitle} <br />
                <span className="text-blue-600">{dict.about.storyAccent}</span>
              </h2>
              <div className="space-y-6 text-lg text-slate-600 leading-relaxed">
                <p>{dict.about.storyPara1}</p>
                <p>{dict.about.storyPara2}</p>
                <p>
                  {language === 'id' 
                    ? 'Kami percaya bahwa armada yang tepat adalah kunci dari perjalanan yang sukses. Itulah mengapa kami berkomitmen untuk menyediakan pilihan transportasi terbaik dengan standar layanan profesional.' 
                    : 'We believe that the right fleet is the key to a successful journey. That is why we are committed to providing the best transportation options with professional service standards.'}
                </p>
              </div>
            </motion.div>
          </div>
        </div>

      </div>
    </section>
  );
};

export default AboutUs;
