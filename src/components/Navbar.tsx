'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { Menu, X, Phone } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';

const Navbar = () => {
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
    { name: 'Beranda', href: '#' },
    { name: 'Transportasi', href: '#transportation' },
    { name: 'Paket Wisata', href: '#tour' },
    { name: 'Hotel', href: '#hotel' },
    { name: 'Kuliner', href: '#kuliner' },
    { name: 'Tentang Kami', href: '#about' },
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
              D'SARANA
            </span>
            <span className="hidden sm:block text-xs font-medium uppercase tracking-widest text-slate-500">
              Travel & Leisure
            </span>
          </Link>

          {/* Desktop Nav */}
          <div className="hidden md:flex items-center gap-8">
            {navLinks.map((link) => (
              <Link 
                key={link.name} 
                href={link.href}
                className="text-sm font-medium text-slate-700 hover:text-emerald-600 transition-colors"
              >
                {link.name}
              </Link>
            ))}
            <a 
              href="https://wa.me/628136892535" 
              target="_blank" 
              className="flex items-center gap-2 bg-emerald-600 text-white px-5 py-2.5 rounded-full text-sm font-semibold hover:bg-emerald-700 transition-all hover:shadow-lg active:scale-95"
            >
              <Phone size={16} />
              Booking Sekarang
            </a>
          </div>

          {/* Mobile Toggle */}
          <button 
            className="md:hidden text-slate-900"
            onClick={() => setIsOpen(!isOpen)}
          >
            {isOpen ? <X size={28} /> : <Menu size={28} />}
          </button>
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
                Hubungi Kami
              </a>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </nav>
  );
};

export default Navbar;
