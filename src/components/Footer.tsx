'use client';

import React from 'react';
import { Mail, Phone, MapPin, Globe, Share2, ExternalLink } from 'lucide-react';


import { useLanguage } from '@/context/LanguageContext';

const Footer = () => {
  const { dict } = useLanguage();

  return (
    <footer className="bg-slate-900 text-slate-300 pt-20 pb-10">
      <div className="container mx-auto px-4 md:px-6">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
          <div>
            <h2 className="text-2xl font-display font-bold text-white mb-6">BATAM TRANSPORT HOLIDAY</h2>
            <p className="text-slate-400 mb-8 leading-relaxed">
              {dict.footer.tagline}
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
            <h3 className="text-white font-bold mb-6 uppercase tracking-wider text-sm">{dict.footer.ourServices}</h3>
            <ul className="space-y-4">
              <li><a href="/#transportation" className="hover:text-emerald-500 transition-colors">{dict.navbar.transportation}</a></li>
              <li><a href="/#tour" className="hover:text-emerald-500 transition-colors">{dict.navbar.tour}</a></li>
              <li><a href="/#hotel" className="hover:text-emerald-500 transition-colors">{dict.navbar.hotel}</a></li>
              <li><a href="/#kuliner" className="hover:text-emerald-500 transition-colors">{dict.navbar.kuliner}</a></li>
            </ul>
          </div>

          <div>
            <h3 className="text-white font-bold mb-6 uppercase tracking-wider text-sm">{dict.footer.company}</h3>
            <ul className="space-y-4">
              <li><a href="/about" className="hover:text-emerald-500 transition-colors">{dict.navbar.about}</a></li>
              <li><a href="/terms" className="hover:text-emerald-500 transition-colors">{dict.footer.terms}</a></li>
              <li><a href="/privacy" className="hover:text-emerald-500 transition-colors">{dict.footer.privacy}</a></li>
              <li><a href="/faq" className="hover:text-emerald-500 transition-colors">{dict.footer.faq}</a></li>
            </ul>
          </div>

          <div>
            <h3 className="text-white font-bold mb-6 uppercase tracking-wider text-sm">{dict.footer.contact}</h3>
            <ul className="space-y-6">
              <li className="flex gap-3">
                <MapPin className="text-emerald-500 shrink-0" size={20} />
                <span>Mall Top 100 Tembesi Blok H3 No. 1, Batam, Indonesia</span>
              </li>
              <li className="flex items-center gap-3">
                <Phone className="text-emerald-500 shrink-0" size={20} />
                <span>+62 813-6892-535</span>
              </li>
              <li className="flex items-center gap-3">
                <Mail className="text-emerald-500 shrink-0" size={20} />
                <span>info@batamtransportholiday.com</span>
              </li>
            </ul>
          </div>
        </div>

        <div className="pt-8 border-t border-slate-800 text-center text-sm text-slate-500">
          <p>© {new Date().getFullYear()} Batam Transport Holiday. {dict.common.allRightsReserved}</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
