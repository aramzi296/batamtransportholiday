'use client';

import React from 'react';
import { Mail, Phone, MapPin, Globe, Share2, ExternalLink } from 'lucide-react';


import { useLanguage } from '@/context/LanguageContext';
import { useSettings } from '@/context/SettingsContext';

const Footer = () => {
  const { dict, language } = useLanguage();
  const { settings } = useSettings();

  return (
    <footer className="bg-slate-900 text-slate-300 pt-20 pb-10">
      <div className="container mx-auto px-4 md:px-6">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
          <div>
            <div className="flex items-center gap-3 mb-6">
              {/* <img 
                src="/images/logo.png" 
                alt="Logo" 
                className="w-10 h-10 object-contain rounded-lg brightness-0 invert"
              /> */}
              <h2 className="text-2xl font-display font-bold text-white">BatamTransportHoliday<span className="text-slate-500">.com</span></h2>
            </div>
            <p className="text-slate-400 mb-8 leading-relaxed">
              {dict.footer.tagline}
            </p>
            <div className="flex items-center gap-4">
              <a href={settings.tiktokUrl} target="_blank" className="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                <svg viewBox="0 0 24 24" fill="currentColor" className="w-5 h-5">
                  <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.17-2.89-.6-4.13-1.47V18c0 1.94-.66 3.82-1.88 5.32A8.91 8.91 0 0 1 9 23.5c-1.79 0-3.5-.53-4.96-1.51A8.96 8.96 0 0 1 1 15c0-1.8 1.14-3.5 2.6-4.5 1.49-1.01 3.38-1.5 5.2-1.39.08.01.15.02.2.03v4.05c-.03-.01-.07-.02-.1-.03-1.22-.12-2.52.17-3.41 1.05-.9.88-1.18 2.23-.74 3.4.45 1.17 1.57 2.04 2.81 2.21 1.25.17 2.6-.14 3.48-1.07.86-.91 1.16-2.22 1.16-3.47V.02z"/>
                </svg>
              </a>
              <a href="#" className="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                <Globe size={20} />
              </a>
            </div>
          </div>

          <div>
            <h3 className="text-white font-bold mb-6 uppercase tracking-wider text-sm">{dict.footer.ourServices}</h3>
            <ul className="space-y-4">
              <li><a href="/#gallery" className="hover:text-blue-500 transition-colors">{language === 'id' ? 'Galeri Armada' : 'Fleet Gallery'}</a></li>            </ul>
          </div>

          <div>
            <h3 className="text-white font-bold mb-6 uppercase tracking-wider text-sm">{dict.footer.company}</h3>
            <ul className="space-y-4">
              <li><a href="/#about" className="hover:text-blue-500 transition-colors">{dict.navbar.about}</a></li>
              <li><a href="/#faq" className="hover:text-blue-500 transition-colors">{dict.footer.faq}</a></li>
              <li><a href="/terms" className="hover:text-blue-500 transition-colors">{dict.footer.terms}</a></li>
              <li><a href="/privacy" className="hover:text-blue-500 transition-colors">{dict.footer.privacy}</a></li>
              <li><a href="/#contact" className="hover:text-blue-500 transition-colors">{dict.footer.contact}</a></li>
            </ul>
          </div>

          <div>
            <h3 className="text-white font-bold mb-6 uppercase tracking-wider text-sm">{dict.footer.contact}</h3>
            <ul className="space-y-6">
              <li className="flex gap-3">
                <MapPin className="text-blue-500 shrink-0" size={20} />
                <span>Mall Top 100 Tembesi Blok H3 No. 1, Batam, Indonesia</span>
              </li>
              <li className="flex items-center gap-3">
                <Phone className="text-blue-500 shrink-0" size={20} />
                <span>{settings.whatsappDisplay}</span>
              </li>
              <li className="flex items-center gap-3">
                <Mail className="text-blue-500 shrink-0" size={20} />
                <span>info@batamtransportholiday.com</span>
              </li>
            </ul>
          </div>
        </div>

        <div className="pt-8 border-t border-slate-800 text-center text-sm text-slate-500">
          <p>© {new Date().getFullYear()} BatamTransportHoliday<span className="text-slate-600">.com</span>. {dict.common.allRightsReserved}</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
