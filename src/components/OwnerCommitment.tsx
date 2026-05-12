'use client';

import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Quote, X } from 'lucide-react';
import { useLanguage } from '@/context/LanguageContext';

const OwnerCommitment = () => {
  const { dict } = useLanguage();
  const [isImageOpen, setIsImageOpen] = useState(false);

  return (
    <section className="py-24 bg-blue-600 relative overflow-hidden">
      {/* Decorative Elements */}
      <div className="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
        <Quote size={400} className="absolute -top-20 -left-20 text-white rotate-12" />
      </div>

      <div className="container mx-auto px-4 md:px-6 relative z-10">
        <div className="max-w-5xl mx-auto bg-white rounded-[3rem] overflow-hidden shadow-2xl flex flex-col md:flex-row items-stretch">
          <div 
            className="md:w-2/5 relative min-h-[400px] cursor-zoom-in group"
            onClick={() => setIsImageOpen(true)}
          >
            <img
              src="/images/owner1.jpg"
              alt="Owner Batam Transport Holiday"
              className="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-blue-900/40 to-transparent group-hover:from-blue-900/60 transition-colors" />
            <div className="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
              <div className="bg-white/20 backdrop-blur-md p-3 rounded-full text-white">
                <Quote size={24} className="rotate-180" />
              </div>
            </div>
          </div>

          <div className="md:w-3/5 p-10 md:p-16 flex flex-col justify-center">
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.8 }}
            >
              <div className="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-8">
                <Quote size={24} />
              </div>

              <h2 className="text-3xl font-display font-bold text-slate-900 mb-6">
                {dict.about.ownerTitle}
              </h2>

              <p className="text-xl text-slate-600 leading-relaxed italic mb-10">
                "{dict.about.ownerCommitment}"
              </p>

              <div>
                <p className="text-2xl font-display font-bold text-slate-900">{dict.about.ownerName}</p>
                <p className="text-blue-600 font-bold uppercase tracking-widest text-sm">Owner & Founder</p>
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
                src="/images/owner1.jpg"
                alt="Owner Full View"
                className="max-w-full max-h-[90vh] w-auto h-auto object-contain rounded-2xl shadow-2xl"
              />
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
    </section>
  );
};

export default OwnerCommitment;
