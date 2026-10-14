'use client';

import React, { createContext, useContext, useSyncExternalStore } from 'react';
import { id, Dictionary } from '../dictionaries/id';
import { en } from '../dictionaries/en';

type Language = 'id' | 'en';
const LANGUAGE_CHANGE_EVENT = 'batam-language-change';

const getLanguageSnapshot = (): Language => {
  const savedLanguage = window.localStorage.getItem('language');
  return savedLanguage === 'id' ? 'id' : 'en';
};

const getServerLanguageSnapshot = (): Language => 'en';

const subscribeToLanguageChanges = (onChange: () => void) => {
  window.addEventListener('storage', onChange);
  window.addEventListener(LANGUAGE_CHANGE_EVENT, onChange);
  return () => {
    window.removeEventListener('storage', onChange);
    window.removeEventListener(LANGUAGE_CHANGE_EVENT, onChange);
  };
};

interface LanguageContextType {
  language: Language;
  setLanguage: (lang: Language) => void;
  dict: Dictionary;
}

const LanguageContext = createContext<LanguageContextType | undefined>(undefined);

export const LanguageProvider = ({ children }: { children: React.ReactNode }) => {
  const language = useSyncExternalStore(
    subscribeToLanguageChanges,
    getLanguageSnapshot,
    getServerLanguageSnapshot,
  );

  const handleSetLanguage = (lang: Language) => {
    localStorage.setItem('language', lang);
    window.dispatchEvent(new Event(LANGUAGE_CHANGE_EVENT));
  };

  const dict = language === 'id' ? id : en;

  return (
    <LanguageContext.Provider value={{ language, setLanguage: handleSetLanguage, dict }}>
      {children}
    </LanguageContext.Provider>
  );
};

export const useLanguage = () => {
  const context = useContext(LanguageContext);
  if (context === undefined) {
    throw new Error('useLanguage must be used within a LanguageProvider');
  }
  return context;
};
