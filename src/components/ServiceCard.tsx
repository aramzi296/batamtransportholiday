'use client';

import React from 'react';
import { motion } from 'framer-motion';
import { LucideIcon } from 'lucide-react';
import Link from 'next/link';

interface ServiceCardProps {
  id: string;
  title: string;
  description: string;
  icon: LucideIcon;
  image: string;
  href?: string;
  delay?: number;
}


import { useLanguage } from '@/context/LanguageContext';

const ServiceCard = ({ id, title, description, icon: Icon, image, href, delay = 0 }: ServiceCardProps) => {
  const { dict } = useLanguage();

  return (
    <motion.div
      id={id}
      initial={{ opacity: 0, y: 20 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true }}
      transition={{ duration: 0.5, delay }}
      className="group relative bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500 scroll-mt-32"
    >
      <div className="aspect-[16/10] overflow-hidden">
        <img 
          src={image} 
          alt={title} 
          className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
        />
        <div className="absolute top-4 left-4 bg-white/90 backdrop-blur-sm p-3 rounded-2xl shadow-sm">
          <Icon className="text-blue-600" size={24} />
        </div>
      </div>
      
      <div className="p-8">
        <h3 className="text-2xl font-display font-bold text-slate-900 mb-3 group-hover:text-blue-600 transition-colors">
          {title}
        </h3>
        <p className="text-slate-600 leading-relaxed mb-6">
          {description}
        </p>
        <Link 
          href={href || "#"} 
          className="inline-flex items-center gap-2 font-bold text-blue-600 group-hover:gap-4 transition-all"
        >
          {dict.common.learnMore}
          <div className="w-6 h-[2px] bg-blue-600 rounded-full" />
        </Link>
      </div>

    </motion.div>
  );
};

export default ServiceCard;
