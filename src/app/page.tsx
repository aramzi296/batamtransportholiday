'use client';

import Navbar from "@/components/Navbar";
import Hero from "@/components/Hero";
import ServiceCard from "@/components/ServiceCard";
import Footer from "@/components/Footer";
import { services } from "@/data/services";
import { Shield, Clock, Award, Users } from "lucide-react";
import { useLanguage } from "@/context/LanguageContext";

export default function Home() {
  const { language, dict } = useLanguage();

  const displayServices = services.map((s, idx) => {
    const content = language === 'id' ? s.idContent : s.enContent;
    return {
      ...s,
      title: content.title,
      description: content.shortDescription,
      href: `/services/${s.slug}`,
      delay: (idx + 1) * 0.1
    };
  });

  const features = [
    {
      icon: Award,
      title: dict.hero.checks[0],
      description: dict.hero.checks[0] === "Armada Terawat" ? "Armada kendaraan terawat dan layanan standar bintang 5." : "Well-maintained vehicle fleet and 5-star standard service."
    },
    {
      icon: Shield,
      title: dict.hero.checks[1],
      description: dict.hero.checks[1] === "Harga Kompetitif" ? "Asuransi lengkap dan driver berpengalaman di bidangnya." : "Full insurance and experienced drivers in their field."
    },
    {
      icon: Clock,
      title: dict.hero.checks[2],
      description: dict.hero.checks[2] === "Layanan 24/7" ? "Customer service kami siap membantu Anda kapan saja." : "Our customer service is ready to help you anytime."
    },
    {
      icon: Users,
      title: dict.home.whyUsAccent.includes("Partner") ? "Partner Terpercaya" : "Trusted Partner",
      description: dict.hero.checks[2] === "Layanan 24/7" ? "Telah melayani berbagai pelanggan sejak tahun 2025." : "Has served various customers since 2025."
    }
  ];

  return (
    <div className="flex flex-col min-h-screen">
      <Navbar />
      <main>
        <Hero />
        
        {/* Services Section */}
        <section id="services" className="py-24 bg-white">
          <div className="container mx-auto px-4 md:px-6">
            <div className="text-center max-w-3xl mx-auto mb-16">
              <h2 className="text-3xl md:text-5xl font-display font-bold text-slate-900 mb-6">
                {dict.home.servicesTitle} <span className="text-emerald-600">{dict.home.servicesAccent}</span>
              </h2>
              <p className="text-lg text-slate-600">
                {dict.home.servicesDesc}
              </p>
            </div>
            
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
              {displayServices.map((service) => (
                <ServiceCard key={service.id} {...service} />
              ))}
            </div>
          </div>
        </section>

        {/* Why Us Section */}
        <section id="about" className="py-24 bg-slate-50 relative overflow-hidden">
          <div className="absolute top-0 right-0 w-1/3 h-full bg-emerald-50/50 -skew-x-12 translate-x-1/2 -z-10" />
          
          <div className="container mx-auto px-4 md:px-6">
            <div className="flex flex-col lg:flex-row items-center gap-16">
              <div className="flex-1">
                <h2 className="text-3xl md:text-5xl font-display font-bold text-slate-900 mb-8">
                  {dict.home.whyUsTitle} <br />
                  <span className="text-emerald-600">{dict.home.whyUsAccent}</span>
                </h2>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-8">
                  {features.map((feature, idx) => (
                    <div key={idx} className="flex gap-4">
                      <div className="w-12 h-12 shrink-0 bg-white rounded-xl shadow-sm flex items-center justify-center text-emerald-600">
                        <feature.icon size={24} />
                      </div>
                      <div>
                        <h4 className="font-bold text-slate-900 mb-1">{feature.title}</h4>
                        <p className="text-sm text-slate-600 leading-relaxed">{feature.description}</p>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
              
              <div className="flex-1 relative">
                <div className="relative rounded-[2.5rem] overflow-hidden shadow-2xl">
                  <img 
                    src="/images/tour.png" 
                    alt="Travel experience" 
                    className="w-full h-full object-cover"
                  />
                  <div className="absolute inset-0 bg-emerald-900/20" />
                </div>
                {/* Stats badge */}
                <div className="absolute -bottom-10 -left-10 glass p-8 rounded-3xl shadow-xl hidden md:block">
                  <p className="text-4xl font-display font-bold text-emerald-600 mb-1">100+</p>
                  <p className="text-sm font-bold text-slate-500 uppercase tracking-wider">{dict.home.customersSatisfied}</p>
                </div>

              </div>
            </div>
          </div>
        </section>

        {/* CTA Section */}
        <section className="py-24">
          <div className="container mx-auto px-4 md:px-6">
            <div className="bg-emerald-600 rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl">
              <div className="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                <div className="absolute -top-24 -left-24 w-96 h-96 border-[40px] border-white rounded-full" />
                <div className="absolute -bottom-24 -right-24 w-96 h-96 border-[40px] border-white rounded-full" />
              </div>
              
              <h2 className="text-3xl md:text-5xl font-display font-bold text-white mb-8 relative z-10">
                {dict.common.readyToStart}
              </h2>
              <p className="text-xl text-emerald-50 mb-12 max-w-2xl mx-auto relative z-10">
                {dict.common.getInTouch}
              </p>
              <div className="flex flex-wrap justify-center gap-6 relative z-10">
                <a 
                  href="https://wa.me/628136892535" 
                  className="bg-white text-emerald-700 px-10 py-5 rounded-2xl font-bold text-lg hover:bg-emerald-50 transition-all shadow-xl active:scale-95"
                >
                  {dict.common.whatsappUs}
                </a>
                <a 
                  href="mailto:info@batamtransportholiday.com" 
                  className="bg-emerald-700 text-white border border-emerald-500 px-10 py-5 rounded-2xl font-bold text-lg hover:bg-emerald-800 transition-all shadow-xl active:scale-95"
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

