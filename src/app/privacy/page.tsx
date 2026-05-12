'use client';

import React from 'react';
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { useLanguage } from "@/context/LanguageContext";
import { motion } from "framer-motion";
import { Lock } from "lucide-react";

export default function PrivacyPage() {
  const { language, dict } = useLanguage();

  return (
    <div className="flex flex-col min-h-screen">
      <Navbar />
      <main className="flex-grow pt-32 pb-24 bg-slate-50">
        <div className="container mx-auto px-4 md:px-6">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8 }}
            className="max-w-4xl mx-auto bg-white p-8 md:p-16 rounded-[3rem] shadow-xl shadow-slate-200/50"
          >
            <div className="flex items-center gap-4 mb-8 text-blue-600">
              <div className="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                <Lock size={28} />
              </div>
              <h1 className="text-3xl md:text-5xl font-display font-bold text-slate-900">
                {dict.footer.privacy}
              </h1>
            </div>

            <div className="prose prose-slate prose-lg max-w-none text-slate-600 space-y-8">
              <section>
                <h2 className="text-2xl font-bold text-slate-900 mb-4">1. {language === 'id' ? 'Informasi yang Kami Kumpulkan' : 'Information We Collect'}</h2>
                <p>
                  {language === 'id' 
                    ? 'Kami mengumpulkan informasi minimal yang diperlukan untuk proses pemesanan, seperti nama, nomor telepon, dan detail perjalanan Anda. Informasi ini dikumpulkan secara sukarela saat Anda menghubungi kami.'
                    : 'We collect minimal information necessary for the booking process, such as your name, phone number, and travel details. This information is collected voluntarily when you contact us.'}
                </p>
              </section>

              <section>
                <h2 className="text-2xl font-bold text-slate-900 mb-4">2. {language === 'id' ? 'Penggunaan Informasi' : 'Use of Information'}</h2>
                <p>
                  {language === 'id'
                    ? 'Informasi Anda hanya digunakan untuk tujuan administratif pemesanan, koordinasi perjalanan, dan peningkatan layanan kami. Kami tidak akan menjual atau menyewakan informasi pribadi Anda kepada pihak ketiga.'
                    : 'Your information is only used for booking administrative purposes, travel coordination, and improving our services. We will not sell or rent your personal information to third parties.'}
                </p>
              </section>

              <section>
                <h2 className="text-2xl font-bold text-slate-900 mb-4">3. {language === 'id' ? 'Keamanan Data' : 'Data Security'}</h2>
                <p>
                  {language === 'id'
                    ? 'Kami mengambil langkah-langkah keamanan yang wajar untuk melindungi informasi Anda dari akses yang tidak sah atau penyalahgunaan. Akses ke data pelanggan dibatasi hanya kepada staf yang memerlukannya.'
                    : 'We take reasonable security measures to protect your information from unauthorized access or misuse. Access to customer data is limited to staff who need it.'}
                </p>
              </section>

              <section>
                <h2 className="text-2xl font-bold text-slate-900 mb-4">4. {language === 'id' ? 'Kontak Kami' : 'Contact Us'}</h2>
                <p>
                  {language === 'id'
                    ? 'Jika Anda memiliki kekhawatiran tentang privasi data Anda, Anda dapat meminta kami untuk menghapus informasi Anda dari sistem kami kapan saja melalui saluran kontak resmi kami.'
                    : 'If you have concerns about your data privacy, you can ask us to delete your information from our system at any time through our official contact channels.'}
                </p>
              </section>

              <div className="mt-16 p-8 bg-blue-50 rounded-3xl border border-blue-100">
                <p className="text-sm italic text-slate-500">
                  {language === 'id' 
                    ? 'Terakhir diperbarui: 11 Mei 2026. Kebijakan privasi ini dapat berubah sewaktu-waktu untuk menyesuaikan dengan regulasi yang berlaku.' 
                    : 'Last updated: May 11, 2026. This privacy policy may change at any time to comply with applicable regulations.'}
                </p>
              </div>
            </div>
          </motion.div>
        </div>
      </main>
      <Footer />
    </div>
  );
}
