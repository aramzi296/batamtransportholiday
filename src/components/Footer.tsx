'use client';

import React from 'react';
import { Mail, Phone, MapPin, Globe, Share2, ExternalLink } from 'lucide-react';


const Footer = () => {
  return (
    <footer className="bg-slate-900 text-slate-300 pt-20 pb-10">
      <div className="container mx-auto px-4 md:px-6">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
          <div>
            <h2 className="text-2xl font-display font-bold text-white mb-6">D'SARANA</h2>
            <p className="text-slate-400 mb-8 leading-relaxed">
              Layanan rental mobil dan bus terpercaya di Batam dengan kualitas terbaik dan harga terjangkau. Partner perjalanan Anda sejak 2018.
            </p>
            <div className="flex items-center gap-4">
              <a href="#" className="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all">
                <Globe size={20} />
              </a>
              <a href="#" className="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all">
                <Share2 size={20} />
              </a>
              <a href="#" className="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all">
                <ExternalLink size={20} />
              </a>
            </div>

          </div>

          <div>
            <h3 className="text-white font-bold mb-6 uppercase tracking-wider text-sm">Layanan Kami</h3>
            <ul className="space-y-4">
              <li><a href="#transportation" className="hover:text-emerald-500 transition-colors">Transportasi & Rental</a></li>
              <li><a href="#tour" className="hover:text-emerald-500 transition-colors">Paket Wisata (City Tour)</a></li>
              <li><a href="#hotel" className="hover:text-emerald-500 transition-colors">Akomodasi Hotel</a></li>
              <li><a href="#kuliner" className="hover:text-emerald-500 transition-colors">Tur Kuliner Batam</a></li>
            </ul>
          </div>

          <div>
            <h3 className="text-white font-bold mb-6 uppercase tracking-wider text-sm">Perusahaan</h3>
            <ul className="space-y-4">
              <li><a href="#about" className="hover:text-emerald-500 transition-colors">Tentang Kami</a></li>
              <li><a href="#" className="hover:text-emerald-500 transition-colors">Syarat & Ketentuan</a></li>
              <li><a href="#" className="hover:text-emerald-500 transition-colors">Kebijakan Privasi</a></li>
              <li><a href="#" className="hover:text-emerald-500 transition-colors">FAQ</a></li>
            </ul>
          </div>

          <div>
            <h3 className="text-white font-bold mb-6 uppercase tracking-wider text-sm">Kontak Kami</h3>
            <ul className="space-y-6">
              <li className="flex gap-3">
                <MapPin className="text-emerald-500 shrink-0" size={20} />
                <span>Mall Top 100 Tembesi Blok H3 No. 1, Batam, Indonesia</span>
              </li>
              <li className="flex items-center gap-3">
                <Phone className="text-emerald-500 shrink-0" size={20} />
                <span>+62 821-7086-0825</span>
              </li>
              <li className="flex items-center gap-3">
                <Mail className="text-emerald-500 shrink-0" size={20} />
                <span>info@dsaranatravel.com</span>
              </li>
            </ul>
          </div>
        </div>

        <div className="pt-8 border-t border-slate-800 text-center text-sm text-slate-500">
          <p>© {new Date().getFullYear()} Batam D'Sarana Travel. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
