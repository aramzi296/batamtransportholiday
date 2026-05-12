'use client';

import Navbar from "@/components/Navbar";
import Hero from "@/components/Hero";
import AboutUs from "@/components/AboutUs";
import OwnerCommitment from "@/components/OwnerCommitment";
import FleetGallery from "@/components/FleetGallery";
import FAQ from "@/components/FAQ";
import Legality from "@/components/Legality";
import Footer from "@/components/Footer";
import { Phone, Mail, MapPin } from "lucide-react";
import { useLanguage } from "@/context/LanguageContext";
import { useSettings } from "@/context/SettingsContext";
import { motion } from "framer-motion";

export default function Home() {
  const { language, dict } = useLanguage();
  const { settings } = useSettings();


  return (
    <div className="flex flex-col min-h-screen">
      <Navbar />
      <main id="home">
        <Hero />
        

        {/* About Us Section (Story, Vision, Mission) */}
        <AboutUs />

        {/* Owner's Commitment Section */}
        <OwnerCommitment />

        {/* Fleet Gallery Section */}
        <FleetGallery />

        {/* FAQ Section */}
        <FAQ />

        {/* Legality Section */}
        <Legality />

        {/* Contact & Location Section */}
        <section id="contact" className="py-24 bg-slate-900 text-white scroll-mt-20">
          <div className="container mx-auto px-4 md:px-6">
            <div className="flex flex-col lg:flex-row gap-16">
              <div className="flex-1">
                <h2 className="text-3xl md:text-5xl font-display font-bold mb-8">
                  {language === 'id' ? 'Hubungi' : 'Contact'} <span className="text-blue-400">{language === 'id' ? 'Kami' : 'Us'}</span>
                </h2>
                <p className="text-slate-400 text-lg mb-12">
                  {dict.common.getInTouch}
                </p>
                
                <div className="space-y-8">
                  <div className="flex items-start gap-4">
                    <div className="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-400 shrink-0">
                      <MapPin size={24} />
                    </div>
                    <div>
                      <h4 className="font-bold mb-1">{dict.legality.address}</h4>
                      <p className="text-slate-400">{dict.legality.valueAddress}</p>
                    </div>
                  </div>
                  
                  <div className="flex items-start gap-4">
                    <div className="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-400 shrink-0">
                      <Phone size={24} />
                    </div>
                    <div>
                      <h4 className="font-bold mb-1">WhatsApp</h4>
                      <p className="text-slate-400">{settings.whatsappDisplay}</p>
                    </div>
                  </div>
                  
                  <div className="flex items-start gap-4">
                    <div className="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-400 shrink-0">
                      <Mail size={24} />
                    </div>
                    <div>
                      <h4 className="font-bold mb-1">Email</h4>
                      <p className="text-slate-400">info@batamtransportholiday.com</p>
                    </div>
                  </div>
                </div>
              </div>
              
              <div className="flex-1">
                <div className="bg-white/5 backdrop-blur-sm p-8 md:p-12 rounded-[2.5rem] border border-white/10">
                  <h3 className="text-2xl font-bold mb-8">{language === 'id' ? 'Kirim Pesan' : 'Send a Message'}</h3>
                  <form className="space-y-6" onSubmit={(e) => e.preventDefault()}>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div>
                        <label className="block text-sm font-medium text-slate-400 mb-2">{language === 'id' ? 'Nama Lengkap' : 'Full Name'}</label>
                        <input type="text" className="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-colors" />
                      </div>
                      <div>
                        <label className="block text-sm font-medium text-slate-400 mb-2">{language === 'id' ? 'Nomor WhatsApp' : 'WhatsApp Number'}</label>
                        <input type="text" className="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-colors" />
                      </div>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-slate-400 mb-2">{language === 'id' ? 'Layanan yang Diminati' : 'Interested Service'}</label>
                      <select className="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-colors appearance-none">
                        <option className="bg-slate-900">{language === 'id' ? 'Sewa Mobil/Bus' : 'Car/Bus Rental'}</option>
                        <option className="bg-slate-900">{language === 'id' ? 'Paket Wisata' : 'Tour Package'}</option>
                        <option className="bg-slate-900">{language === 'id' ? 'Lainnya' : 'Others'}</option>
                      </select>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-slate-400 mb-2">{language === 'id' ? 'Pesan' : 'Message'}</label>
                      <textarea rows={4} className="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-colors"></textarea>
                    </div>
                    <button className="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg active:scale-[0.98]">
                      {language === 'id' ? 'Kirim Sekarang' : 'Send Now'}
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* Final CTA Section */}
        <section className="py-24 bg-white">
          <div className="container mx-auto px-4 md:px-6">
            <div className="bg-blue-600 rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl">
              <div className="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                <div className="absolute -top-24 -left-24 w-96 h-96 border-[40px] border-white rounded-full" />
                <div className="absolute -bottom-24 -right-24 w-96 h-96 border-[40px] border-white rounded-full" />
              </div>
              
              <h2 className="text-3xl md:text-5xl font-display font-bold text-white mb-8 relative z-10">
                {dict.common.readyToStart}
              </h2>
              <p className="text-xl text-blue-50 mb-12 max-w-2xl mx-auto relative z-10">
                {dict.common.getInTouch}
              </p>
              <div className="flex flex-wrap justify-center gap-6 relative z-10">
                <a 
                  href={`https://wa.me/${settings.whatsappNumber}`} 
                  className="bg-white text-blue-700 px-10 py-5 rounded-2xl font-bold text-lg hover:bg-blue-50 transition-all shadow-xl active:scale-95"
                >
                  {dict.common.whatsappUs}
                </a>
                <a 
                  href="mailto:info@batamtransportholiday.com" 
                  className="bg-blue-700 text-white border border-blue-500 px-10 py-5 rounded-2xl font-bold text-lg hover:bg-blue-800 transition-all shadow-xl active:scale-95"
                >
                  {dict.common.emailUs}
                </a>
              </div>
            </div>
          </div>
        </section>
      </main>
      <Footer />
    </div>
  );
}



