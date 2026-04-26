'use client';

import React from 'react';
import { useParams, useRouter } from 'next/navigation';
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { services } from "@/data/services";
import { motion } from 'framer-motion';
import { CheckCircle2, ArrowLeft, Phone } from 'lucide-react';
import Link from 'next/link';
import { useLanguage } from '@/context/LanguageContext';

const ServiceDetailPage = () => {
  const { dict, language } = useLanguage();
  const params = useParams();
  const router = useRouter();
  const slug = params.slug;

  const rawService = services.find(s => s.slug === slug);
  
  if (!rawService) {
    return (
      <div className="min-h-screen flex flex-col">
        <Navbar />
        <main className="flex-grow flex items-center justify-center">
          <div className="text-center">
            <h1 className="text-4xl font-bold mb-4">{language === 'id' ? 'Layanan Tidak Ditemukan' : 'Service Not Found'}</h1>
            <Link href="/" className="text-emerald-600 font-bold hover:underline">{language === 'id' ? 'Kembali ke Beranda' : 'Back to Home'}</Link>
          </div>
        </main>
        <Footer />
      </div>
    );
  }

  const content = language === 'id' ? rawService.idContent : rawService.enContent;
  const service = {
    ...rawService,
    title: content.title,
    shortDescription: content.shortDescription,
    fullDescription: content.fullDescription,
    price: content.price,
    features: content.features,
    details: content.details,
  };

  const Icon = service.icon;

  return (
    <div className="flex flex-col min-h-screen">
      <Navbar />
      
      <main className="flex-grow pt-24 pb-16">
        {/* Hero Section for Detail */}
        <section className="relative py-20 bg-slate-900 overflow-hidden">
          <div className="absolute inset-0 opacity-20">
            <img src={service.image} alt="" className="w-full h-full object-cover blur-sm" />
          </div>
          <div className="container mx-auto px-4 md:px-6 relative z-10">
            <button 
              onClick={() => router.back()}
              className="flex items-center gap-2 text-emerald-400 font-bold mb-8 hover:text-emerald-300 transition-colors"
            >
              <ArrowLeft size={20} />
              {dict.common.back}
            </button>
            <div className="max-w-3xl">
              <div className="w-16 h-16 bg-emerald-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-emerald-600/20">
                <Icon size={32} />
              </div>
              <h1 className="text-4xl md:text-6xl font-display font-extrabold text-white mb-6">
                {service.title}
              </h1>
              <p className="text-xl text-slate-300 leading-relaxed">
                {service.shortDescription}
              </p>
            </div>
          </div>
        </section>

        {/* Content Section */}
        <section className="py-20">
          <div className="container mx-auto px-4 md:px-6">
            <div className="flex flex-col lg:flex-row gap-16">
              {/* Left Column: Info */}
              <div className="lg:w-2/3">
                <div className="mb-12">
                  <h2 className="text-3xl font-display font-bold text-slate-900 mb-6">{language === 'id' ? 'Deskripsi Layanan' : 'Service Description'}</h2>
                  <p className="text-lg text-slate-600 leading-relaxed mb-8">
                    {service.fullDescription}
                  </p>
                  
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    {service.details.map((detail, idx) => (
                      <div key={idx} className="p-8 bg-slate-50 rounded-[2rem] border border-slate-100">
                        <h4 className="font-bold text-slate-900 mb-3">{detail.title}</h4>
                        <p className="text-slate-600 leading-relaxed">{detail.content}</p>
                      </div>
                    ))}
                  </div>
                </div>

                <div>
                  <h2 className="text-3xl font-display font-bold text-slate-900 mb-8">{language === 'id' ? 'Fitur & Keunggulan' : 'Features & Benefits'}</h2>
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {service.features.map((feature, idx) => (
                      <div key={idx} className="flex items-center gap-3 p-4 bg-white border border-slate-100 rounded-2xl shadow-sm">
                        <CheckCircle2 className="text-emerald-500 shrink-0" size={20} />
                        <span className="font-medium text-slate-700">{feature}</span>
                      </div>
                    ))}
                  </div>
                </div>
              </div>

              {/* Right Column: CTA/Pricing */}
              <div className="lg:w-1/3">
                <div className="sticky top-28">
                  <div className="bg-white rounded-[2.5rem] p-8 shadow-2xl border border-slate-100 relative overflow-hidden">
                    <div className="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full -translate-y-1/2 translate-x-1/2 -z-10" />
                    
                    <p className="text-slate-500 font-bold uppercase tracking-wider text-sm mb-2">{language === 'id' ? 'Estimasi Harga' : 'Estimated Price'}</p>
                    <p className="text-3xl font-display font-bold text-emerald-600 mb-8">{service.price}</p>
                    
                    <div className="space-y-4">
                      <a 
                        href={`https://wa.me/628136892535?text=Halo%20D'Sarana%20Travel,%20saya%20ingin%20tanya%20mengenai%20${encodeURIComponent(service.title)}`}
                        target="_blank"
                        className="flex items-center justify-center gap-3 w-full bg-emerald-600 text-white p-5 rounded-2xl font-bold text-lg hover:bg-emerald-700 transition-all shadow-xl active:scale-95"
                      >
                        <Phone size={20} />
                        {dict.common.booking}
                      </a>
                      <p className="text-center text-sm text-slate-500">
                        {language === 'id' ? 'Konfirmasi instan via WhatsApp' : 'Instant confirmation via WhatsApp'}
                      </p>
                    </div>

                    <div className="mt-8 pt-8 border-t border-slate-100">
                      <h5 className="font-bold text-slate-900 mb-4 text-sm uppercase tracking-widest">{language === 'id' ? 'Kenapa Kami?' : 'Why Us?'}</h5>
                      <ul className="space-y-3">
                        {dict.hero.checks.map((check, idx) => (
                          <li key={idx} className="flex items-center gap-2 text-sm text-slate-600">
                            <CheckCircle2 size={16} className="text-emerald-500" />
                            {check}
                          </li>
                        ))}
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>

      <Footer />
    </div>
  );
};

export default ServiceDetailPage;
