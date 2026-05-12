'use client';

import React from 'react';
import { motion } from 'framer-motion';
import { Shield, Award, FileText, Scale, MapPin } from 'lucide-react';
import { useLanguage } from '@/context/LanguageContext';

const Legality = () => {
  const { dict } = useLanguage();

  const legalityItems = [
    { label: dict.legality.companyName, value: dict.legality.valueName, icon: Award },
    { label: dict.legality.nib, value: dict.legality.valueNib, icon: Shield },
    { label: dict.legality.npwp, value: dict.legality.valueNpwp, icon: FileText },
    { label: dict.legality.sk, value: dict.legality.valueSk, icon: Scale },
  ];

  return (
    <section className="py-24 bg-slate-900 relative overflow-hidden">
      {/* Decorative Blobs */}
      <div className="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
        <div className="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-500 rounded-full blur-[120px]" />
        <div className="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-500 rounded-full blur-[120px]" />
      </div>
      
      <div className="container mx-auto px-4 md:px-6 relative z-10">
        <div className="max-w-4xl mx-auto">
          <div className="text-center mb-16">
            <h2 className="text-3xl md:text-5xl font-display font-bold text-white mb-6">
              {dict.legality.title}
            </h2>
            <div className="w-24 h-1.5 bg-blue-500 mx-auto rounded-full" />
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {legalityItems.map((item, idx) => (
              <motion.div 
                key={idx}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: idx * 0.1 }}
                className="bg-white/5 backdrop-blur-md border border-white/10 p-8 rounded-3xl flex items-start gap-5 hover:bg-white/10 transition-colors"
              >
                <div className="w-12 h-12 bg-blue-500/20 text-blue-400 rounded-xl flex items-center justify-center shrink-0">
                  <item.icon size={24} />
                </div>
                <div>
                  <p className="text-blue-400 text-xs font-bold uppercase tracking-widest mb-1">{item.label}</p>
                  <p className="text-white text-lg font-medium">{item.value}</p>
                </div>
              </motion.div>
            ))}
          </div>

          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ delay: 0.4 }}
            className="mt-8 bg-white/5 backdrop-blur-md border border-white/10 p-8 rounded-3xl flex items-start gap-5 hover:bg-white/10 transition-colors"
          >
            <div className="w-12 h-12 bg-blue-500/20 text-blue-400 rounded-xl flex items-center justify-center shrink-0">
              <MapPin size={24} />
            </div>
            <div>
              <p className="text-blue-400 text-xs font-bold uppercase tracking-widest mb-1">{dict.legality.address}</p>
              <p className="text-white text-lg font-medium">{dict.legality.valueAddress}</p>
            </div>
          </motion.div>
        </div>
      </div>
    </section>
  );
};

export default Legality;
