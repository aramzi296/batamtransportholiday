import type { Metadata } from "next";
import { Outfit, Inter } from "next/font/google";
import "./globals.css";
import Script from "next/script";

const outfit = Outfit({
  variable: "--font-outfit",
  subsets: ["latin"],
});

const inter = Inter({
  variable: "--font-inter",
  subsets: ["latin"],
});

export const metadata: Metadata = {
  title: "Batam Transport Holiday | Rental Mobil, Bus & Paket Wisata Batam",
  description: "Layanan transportasi terpercaya di Batam. Menyediakan rental mobil, bus, paket wisata, penginapan hotel, dan tur kuliner terbaik di Kota Batam.",
  keywords: "batamtransportholiday.com, rental mobil batam, sewa bus batam, paket wisata batam, tour kuliner batam, hotel batam, batam transport holiday",
};





import { LanguageProvider } from "@/context/LanguageContext";
import { SettingsProvider } from "@/context/SettingsContext";

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en" className={`${outfit.variable} ${inter.variable} scroll-smooth`}>
      <body className="font-inter antialiased bg-white text-slate-900">
        <noscript>
          <iframe
            src="https://www.googletagmanager.com/ns.html?id=GTM-TQTFVCT7"
            height="0"
            width="0"
            style={{ display: "none", visibility: "hidden" }}
          />
        </noscript>
        <Script id="google-tag-manager" strategy="afterInteractive">
          {`
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-TQTFVCT7');
          `}
        </Script>
        <SettingsProvider>
          <LanguageProvider>
            {children}
          </LanguageProvider>
        </SettingsProvider>
      </body>
    </html>
  );
}


