'use client';

import React, { useState, useEffect } from 'react';
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { useSettings } from "@/context/SettingsContext";
import { motion } from "framer-motion";
import { Save, Phone, CheckCircle, AlertCircle } from "lucide-react";

export default function AdminSettings() {
  const { settings, updateSettings } = useSettings();
  const [waNumber, setWaNumber] = useState(settings.whatsappNumber);
  const [waDisplay, setWaDisplay] = useState(settings.whatsappDisplay);
  const [tiktokUrl, setTiktokUrl] = useState(settings.tiktokUrl);
  const [status, setStatus] = useState<'idle' | 'saving' | 'success' | 'error'>('idle');

  useEffect(() => {
    setWaNumber(settings.whatsappNumber);
    setWaDisplay(settings.whatsappDisplay);
    setTiktokUrl(settings.tiktokUrl);
  }, [settings]);

  const handleSave = async (e: React.FormEvent) => {
    e.preventDefault();
    setStatus('saving');
    
    const success = await updateSettings({
      whatsappNumber: waNumber,
      whatsappDisplay: waDisplay,
      tiktokUrl: tiktokUrl
    });

    if (success) {
      setStatus('success');
      setTimeout(() => setStatus('idle'), 3000);
    } else {
      setStatus('error');
      setTimeout(() => setStatus('idle'), 3000);
    }
  };

  return (
    <div className="flex flex-col min-h-screen">
      <Navbar />
      <main className="flex-grow pt-32 pb-24 bg-slate-50">
        <div className="container mx-auto px-4 md:px-6">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            className="max-w-2xl mx-auto bg-white p-8 md:p-12 rounded-[2.5rem] shadow-xl border border-slate-100"
          >
            <div className="flex items-center gap-4 mb-10">
              <div className="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center">
                <Save size={24} />
              </div>
              <div>
                <h1 className="text-3xl font-display font-bold text-slate-900">Pengaturan Website</h1>
                <p className="text-slate-500 text-sm">Kelola informasi kontak global</p>
              </div>
            </div>

            <form onSubmit={handleSave} className="space-y-8">
              <div className="space-y-6">
                <div>
                  <label className="block text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                    <Phone size={16} className="text-blue-500" />
                    Nomor WhatsApp (Format: 628123456789)
                  </label>
                  <input
                    type="text"
                    value={waNumber}
                    onChange={(e) => setWaNumber(e.target.value)}
                    className="w-full bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all text-lg font-medium"
                    placeholder="Contoh: 628136892535"
                    required
                  />
                  <p className="mt-2 text-xs text-slate-400 italic">* Tanpa tanda +, spasi, atau strip. Awali dengan kode negara (62).</p>
                </div>

                <div>
                  <label className="block text-sm font-bold text-slate-700 mb-3">
                    Tampilan Nomor (Untuk Teks di Layar)
                  </label>
                  <input
                    type="text"
                    value={waDisplay}
                    onChange={(e) => setWaDisplay(e.target.value)}
                    className="w-full bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all text-lg font-medium"
                    placeholder="Contoh: +62 813-6892-535"
                    required
                  />
                </div>

                <div>
                  <label className="block text-sm font-bold text-slate-700 mb-3">
                    URL TikTok
                  </label>
                  <input
                    type="url"
                    value={tiktokUrl}
                    onChange={(e) => setTiktokUrl(e.target.value)}
                    className="w-full bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all text-lg font-medium"
                    placeholder="Contoh: https://www.tiktok.com/@user"
                    required
                  />
                </div>
              </div>

              <button
                type="submit"
                disabled={status === 'saving'}
                className={`w-full py-5 rounded-2xl font-bold text-lg flex items-center justify-center gap-3 transition-all active:scale-[0.98] ${
                  status === 'success' 
                    ? 'bg-emerald-500 text-white' 
                    : status === 'error'
                    ? 'bg-rose-500 text-white'
                    : 'bg-blue-600 text-white hover:bg-blue-700 shadow-xl shadow-blue-200'
                }`}
              >
                {status === 'saving' ? (
                  <div className="w-6 h-6 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                ) : status === 'success' ? (
                  <>
                    <CheckCircle size={24} />
                    Berhasil Disimpan
                  </>
                ) : status === 'error' ? (
                  <>
                    <AlertCircle size={24} />
                    Gagal Menyimpan
                  </>
                ) : (
                  <>
                    <Save size={24} />
                    Simpan Perubahan
                  </>
                )}
              </button>
            </form>
          </motion.div>
        </div>
      </main>
      <Footer />
    </div>
  );
}
