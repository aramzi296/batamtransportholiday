'use client';

import React from 'react';
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { motion } from 'framer-motion';
import { Target, Eye, Users, Award, Shield, Clock } from 'lucide-react';
import { useLanguage } from '@/context/LanguageContext';

const AboutPage = () => {
  const { dict, language } = useLanguage();
  
  const stats = dict.about.stats;

  const values = [
    {
      icon: Shield,
      title: language === 'id' ? 'Kepercayaan' : 'Trust',
      description: language === 'id' ? 'Membangun hubungan jangka panjang dengan pelanggan melalui kejujuran dan transparansi.' : 'Building long-term relationships with customers through honesty and transparency.'
    },
    {
      icon: Award,
      title: language === 'id' ? 'Kualitas' : 'Quality',
      description: language === 'id' ? 'Memberikan standar layanan tertinggi dalam setiap aspek perjalanan Anda.' : 'Providing the highest service standards in every aspect of your journey.'
    },
    {
      icon: Users,
      title: language === 'id' ? 'Kepuasan Pelanggan' : 'Customer Satisfaction',
      description: language === 'id' ? 'Fokus utama kami adalah memastikan setiap perjalanan Anda berkesan dan nyaman.' : 'Our main focus is ensuring every journey you take is memorable and comfortable.'
    }
  ];

  return (
    <div className="flex flex-col min-h-screen">
      <Navbar />
      
      <main className="flex-grow pt-20">
        {/* Header Section */}
        <section className="relative py-24 bg-slate-900 overflow-hidden">
          <div className="absolute top-0 right-0 w-1/2 h-full bg-emerald-600/10 -skew-x-12 translate-x-1/4" />
          <div className="container mx-auto px-4 md:px-6 relative z-10">
            <div className="max-w-3xl">
              <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6 }}
              >
                <span className="text-emerald-400 font-bold tracking-widest uppercase text-sm mb-4 block">{dict.about.subtitle}</span>
                <h1 className="text-4xl md:text-6xl font-display font-extrabold text-white mb-6 leading-tight">
                  {dict.about.title} <br />
                  <span className="text-emerald-500">{dict.about.titleAccent}</span>
                </h1>
                <p className="text-xl text-slate-400 leading-relaxed">
                  {dict.about.description}
                </p>
              </motion.div>
            </div>
          </div>
        </section>

        {/* Stats Section */}
        <section className="py-12 bg-emerald-600">
          <div className="container mx-auto px-4 md:px-6">
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-8">
              {stats.map((stat, index) => (
                <div key={index} className="text-center text-white">
                  <p className="text-4xl md:text-5xl font-display font-extrabold mb-2">{stat.value}</p>
                  <p className="text-emerald-100 text-sm font-medium uppercase tracking-wider">{stat.label}</p>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* Story Section */}
        <section className="py-24 bg-white">
          <div className="container mx-auto px-4 md:px-6">
            <div className="flex flex-col lg:flex-row items-center gap-16">
              <div className="flex-1">
                <div className="relative">
                  <img 
                    src="/images/transport.png" 
                    alt="Our Story" 
                    className="rounded-[3rem] shadow-2xl relative z-10"
                  />
                  <div className="absolute -bottom-6 -right-6 w-full h-full border-4 border-emerald-100 rounded-[3rem] -z-10" />
                </div>
              </div>
              <div className="flex-1">
                <h2 className="text-3xl md:text-5xl font-display font-bold text-slate-900 mb-8">
                  {dict.about.storyTitle} <br />
                  <span className="text-emerald-600">{dict.about.storyAccent}</span>
                </h2>
                <div className="space-y-6 text-lg text-slate-600 leading-relaxed">
                  <p>{dict.about.storyPara1}</p>
                  <p>{dict.about.storyPara2}</p>
                  <p>{language === 'id' ? 'Kami percaya bahwa setiap perjalanan adalah cerita yang unik. Itulah mengapa kami berkomitmen untuk memberikan lebih dari sekadar transportasi, tetapi sebuah pengalaman yang tak terlupakan.' : 'We believe that every journey is a unique story. That is why we are committed to providing more than just transportation, but an unforgettable experience.'}</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* Vision & Mission */}
        <section className="py-24 bg-slate-50">
          <div className="container mx-auto px-4 md:px-6">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-12">
              <div className="bg-white p-12 rounded-[2.5rem] shadow-xl border border-slate-100">
                <div className="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-8">
                  <Eye size={32} />
                </div>
                <h3 className="text-3xl font-display font-bold text-slate-900 mb-6">{dict.about.visionTitle}</h3>
                <p className="text-lg text-slate-600 leading-relaxed">
                  {dict.about.visionDesc}
                </p>
              </div>
              <div className="bg-white p-12 rounded-[2.5rem] shadow-xl border border-slate-100">
                <div className="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-8">
                  <Target size={32} />
                </div>
                <h3 className="text-3xl font-display font-bold text-slate-900 mb-6">{dict.about.missionTitle}</h3>
                <ul className="space-y-4 text-lg text-slate-600">
                  {dict.about.missionList.map((item, idx) => (
                    <li key={idx} className="flex gap-3">
                      <span className="text-emerald-600 font-bold">•</span>
                      {item}
                    </li>
                  ))}
                </ul>
              </div>
            </div>
          </div>
        </section>

        {/* Core Values */}
        <section className="py-24 bg-white">
          <div className="container mx-auto px-4 md:px-6 text-center">
            <h2 className="text-3xl md:text-5xl font-display font-bold text-slate-900 mb-16">
              {dict.about.valuesTitle} <span className="text-emerald-600">{dict.about.valuesAccent}</span>
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              {values.map((value, index) => (
                <div key={index} className="p-8 group hover:bg-emerald-600 transition-all duration-500 rounded-[2rem]">
                  <div className="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                    <value.icon size={32} />
                  </div>
                  <h4 className="text-xl font-bold text-slate-900 mb-4 group-hover:text-white transition-colors">{value.title}</h4>
                  <p className="text-slate-600 group-hover:text-emerald-50 transition-colors leading-relaxed">
                    {value.description}
                  </p>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* CTA Section */}
        <section className="py-24 bg-slate-900">
          <div className="container mx-auto px-4 md:px-6">
            <div className="bg-emerald-600 rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden">
              <h2 className="text-3xl md:text-5xl font-display font-bold text-white mb-8">
                {language === 'id' ? 'Ingin Mengenal Kami Lebih Dekat?' : 'Want to Get to Know Us Better?'}
              </h2>
              <p className="text-xl text-emerald-50 mb-12 max-w-2xl mx-auto">
                {language === 'id' ? 'Tim kami siap menjawab pertanyaan Anda dan membantu merencanakan perjalanan terbaik Anda di Batam.' : 'Our team is ready to answer your questions and help plan your best trip in Batam.'}
              </p>
              <a 
                href="https://wa.me/628136892535" 
                className="inline-block bg-white text-emerald-700 px-10 py-5 rounded-2xl font-bold text-lg hover:bg-emerald-50 transition-all shadow-xl"
              >
                {language === 'id' ? 'Hubungi Kami Sekarang' : 'Contact Us Now'}
              </a>
            </div>
          </div>
        </section>
      </main>

      <Footer />
    </div>
  );
};

export default AboutPage;
