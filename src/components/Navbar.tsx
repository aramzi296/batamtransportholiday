'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { useLanguage } from '@/context/LanguageContext';
import { motion, AnimatePresence } from 'framer-motion';
import { Menu, X, Phone, Globe } from 'lucide-react';

const Navbar = () => {
  const { language, setLanguage, dict } = useLanguage();
  const [isOpen, setIsOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 20);
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const navLinks = [
    { name: dict.navbar.home, href: '/' },
    { name: dict.navbar.transportation, href: '/#transportation' },
    { name: dict.navbar.tour, href: '/#tour' },
    { name: dict.navbar.hotel, href: '/#hotel' },
    { name: dict.navbar.kuliner, href: '/#kuliner' },
    { name: dict.navbar.about, href: '/about' },
  ];

  return (
    <nav 
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
        scrolled ? 'glass py-3 shadow-sm' : 'bg-transparent py-5'
      }`}
    >
      <div className="container mx-auto px-4 md:px-6">
        <div className="flex items-center justify-between">
          <Link href="/" className="flex items-center gap-2">
            <span className={`text-2xl font-display font-bold ${scrolled ? 'text-emerald-700' : 'text-emerald-600'}`}>
              BATAM TRANSPORT
            </span>
            <span className="hidden sm:block text-xs font-medium uppercase tracking-widest text-slate-500">
              HOLIDAY
            </span>
          </Link>

          {/* Desktop Nav */}
          <div className="hidden md:flex items-center gap-6 lg:gap-8">
            {navLinks.map((link) => (
              <Link 
                key={link.name} 
                href={link.href}
                className="text-sm font-medium text-slate-700 hover:text-emerald-600 transition-colors"
              >
                {link.name}
              </Link>
            ))}
            
            <div className="flex items-center gap-4 border-l border-slate-200 pl-6">
              <button 
                onClick={() => setLanguage(language === 'id' ? 'en' : 'id')}
                className="flex items-center gap-1.5 text-sm font-bold text-slate-600 hover:text-emerald-600 transition-colors"
              >
                <Globe size={16} />
                {language.toUpperCase()}
              </button>
              
              <a 
                href="https://wa.me/628136892535" 
                target="_blank" 
                className="flex items-center gap-2 bg-emerald-600 text-white px-5 py-2.5 rounded-full text-sm font-semibold hover:bg-emerald-700 transition-all hover:shadow-lg active:scale-95"
              >
                <Phone size={16} />
                {dict.common.booking}
              </a>
            </div>
          </div>

          {/* Mobile Toggle */}
          <div className="flex items-center gap-4 md:hidden">
            <button 
              onClick={() => setLanguage(language === 'id' ? 'en' : 'id')}
              className="flex items-center gap-1 text-xs font-bold text-slate-600"
            >
              <Globe size={16} />
              {language.toUpperCase()}
            </button>
            <button 
              className="text-slate-900"
              onClick={() => setIsOpen(!isOpen)}
            >
              {isOpen ? <X size={28} /> : <Menu size={28} />}
            </button>
          </div>
        </div>
      </div>

      {/* Mobile Menu */}
      <AnimatePresence>
        {isOpen && (
          <motion.div 
            initial={{ opacity: 0, height: 0 }}
            animate={{ opacity: 1, height: 'auto' }}
            exit={{ opacity: 0, height: 0 }}
            className="md:hidden bg-white border-t border-slate-100 overflow-hidden"
          >
            <div className="flex flex-col p-4 gap-4">
              {navLinks.map((link) => (
                <Link 
                  key={link.name} 
                  href={link.href}
                  onClick={() => setIsOpen(false)}
                  className="text-lg font-medium text-slate-700 border-b border-slate-50 pb-2"
                >
                  {link.name}
                </Link>
              ))}
              <a 
                href="https://wa.me/628136892535" 
                className="flex items-center justify-center gap-2 bg-emerald-600 text-white p-4 rounded-xl font-bold"
              >
                <Phone size={20} />
                {dict.common.contactUs}
              </a>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </nav>
  );
};

export default Navbar;
