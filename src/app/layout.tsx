import type { Metadata } from "next";
import { Outfit, Inter } from "next/font/google";
import "./globals.css";

const outfit = Outfit({
  variable: "--font-outfit",
  subsets: ["latin"],
});

const inter = Inter({
  variable: "--font-inter",
  subsets: ["latin"],
});

export const metadata: Metadata = {
  title: "Batam D'Sarana Travel | Rental Mobil, Bus & Paket Wisata Batam",
  description: "Layanan transportasi terpercaya di Batam. Menyediakan rental mobil, bus, paket wisata, penginapan hotel, dan tur kuliner terbaik di Kota Batam.",
  keywords: "dsarana.com, rental mobil batam, sewa bus batam, paket wisata batam, tour kuliner batam, hotel batam, d sarana travel",
};





export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="id" className={`${outfit.variable} ${inter.variable} scroll-smooth`}>
      <body className="font-inter antialiased bg-white text-slate-900">
        {children}
      </body>
    </html>
  );
}

