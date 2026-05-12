'use client';

import React from 'react';
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { useLanguage } from "@/context/LanguageContext";
import { motion } from "framer-motion";
import { ShieldCheck } from "lucide-react";

export default function TermsPage() {
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
                <ShieldCheck size={28} />
              </div>
              <h1 className="text-3xl md:text-5xl font-display font-bold text-slate-900">
                {dict.footer.terms}
              </h1>
            </div>

            <div className="prose prose-slate prose-lg max-w-none text-slate-600 space-y-8">
              <section>
                <h2 className="text-2xl font-bold text-slate-900 mb-4">1. {language === 'id' ? 'Ketentuan Umum' : 'General Terms'}</h2>
                <p>
                  {language === 'id' 
                    ? 'Dengan mengakses dan menggunakan layanan Batam Transport Holiday, Anda setuju untuk terikat oleh syarat dan ketentuan ini. Kami berhak mengubah syarat ini kapan saja tanpa pemberitahuan sebelumnya.'
                    : 'By accessing and using Batam Transport Holiday services, you agree to be bound by these terms and conditions. We reserve the right to change these terms at any time without prior notice.'}
                </p>
              </section>

              <section>
                <h2 className="text-2xl font-bold text-slate-900 mb-4">2. {language === 'id' ? 'Pemesanan & Pembayaran' : 'Booking & Payment'}</h2>
                <div className="space-y-4">
                  <p>
                    {language === 'id'
                      ? 'Untuk semua jenis armada, tidak diperlukan pembayaran uang muka (DP) di awal untuk pemesanan standar. Pembayaran penuh dilakukan pada hari pertama layanan digunakan.'
                      : 'For all types of fleets, no booking payment or downpayment is required upfront for standard bookings. Full payment is made on the day the service is used.'}
                  </p>
                  <p>
                    {language === 'id'
                      ? 'Namun, jika durasi pemesanan lebih dari dua hari, pelanggan diwajibkan membayar uang muka (downpayment) sebesar 25% dari total biaya pemesanan sebagai jaminan reservasi.'
                      : 'However, if the booking duration exceeds two days, customers are required to pay a downpayment of 25% of the total booking cost as a reservation guarantee.'}
                  </p>
                  <p>
                    {language === 'id'
                      ? 'Apabila tamu memerlukan bantuan reservasi di tempat lain seperti hotel atau restoran, tim kami dengan senang hati akan membantu proses tersebut. Namun, kebijakan pembayaran untuk reservasi pihak ketiga ini akan mengikuti kebijakan masing-masing hotel atau restoran yang bersangkutan.'
                      : 'If guests require assistance with reservations elsewhere such as hotels or restaurants, our team will be happy to assist. However, the payment policy for these third-party reservations will follow the respective policies of the hotels or restaurants involved.'}
                  </p>
                </div>
              </section>

              <section>
                <h2 className="text-2xl font-bold text-slate-900 mb-4">3. {language === 'id' ? 'Pembatalan' : 'Cancellation'}</h2>
                <p>
                  {language === 'id'
                    ? 'Pembatalan pemesanan dapat dilakukan kapan saja sebelum hari keberangkatan. Namun, khusus untuk pemesanan yang telah melakukan pembayaran uang muka (downpayment), pembatalan akan dikenakan pemotongan terhadap dana downpayment yang telah diserahkan.'
                    : 'Booking cancellation can be made at any time before the departure date. However, specifically for bookings where a downpayment has been made, cancellation will be subject to a deduction from the submitted downpayment funds.'}
                </p>
              </section>

              <section>
                <h2 className="text-2xl font-bold text-slate-900 mb-4">4. {language === 'id' ? 'Tanggung Jawab Pengguna' : 'User Responsibility'}</h2>
                <p>
                  {language === 'id'
                    ? 'Pengguna jasa bertanggung jawab atas keamanan barang bawaan pribadi. Batam Transport Holiday tidak bertanggung jawab atas kehilangan barang yang disebabkan oleh kelalaian pengguna.'
                    : 'Service users are responsible for the security of personal belongings. Batam Transport Holiday is not responsible for loss of items caused by user negligence.'}
                </p>
              </section>

              <div className="mt-16 p-8 bg-blue-50 rounded-3xl border border-blue-100">
                <p className="text-sm italic text-slate-500">
                  {language === 'id' 
                    ? 'Terakhir diperbarui: 11 Mei 2026. Jika Anda memiliki pertanyaan mengenai syarat dan ketentuan ini, silakan hubungi tim dukungan kami.' 
                    : 'Last updated: May 11, 2026. If you have any questions regarding these terms and conditions, please contact our support team.'}
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
