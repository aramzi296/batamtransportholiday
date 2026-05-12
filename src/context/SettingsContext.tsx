'use client';

import React, { createContext, useContext, useState, useEffect } from 'react';
import initialSettings from '@/data/settings.json';

interface Settings {
  whatsappNumber: string;
  whatsappDisplay: string;
  tiktokUrl: string;
}

interface SettingsContextType {
  settings: Settings;
  updateSettings: (newSettings: Settings) => Promise<boolean>;
}

const SettingsContext = createContext<SettingsContextType | undefined>(undefined);

export const SettingsProvider = ({ children }: { children: React.ReactNode }) => {
  const [settings, setSettings] = useState<Settings>(initialSettings);

  // Sync with local state if needed or just use the initial from JSON
  // In a real app, you might fetch this from an API on mount
  useEffect(() => {
    fetch('/api/settings')
      .then(res => res.json())
      .then(data => {
        if (data.whatsappNumber) {
          setSettings(data);
        }
      })
      .catch(err => console.error('Failed to load settings:', err));
  }, []);

  const updateSettings = async (newSettings: Settings) => {
    try {
      const res = await fetch('/api/settings', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(newSettings),
      });
      
      if (res.ok) {
        setSettings(newSettings);
        return true;
      }
      return false;
    } catch (error) {
      console.error('Error updating settings:', error);
      return false;
    }
  };

  return (
    <SettingsContext.Provider value={{ settings, updateSettings }}>
      {children}
    </SettingsContext.Provider>
  );
};

export const useSettings = () => {
  const context = useContext(SettingsContext);
  if (context === undefined) {
    throw new Error('useSettings must be used within a SettingsProvider');
  }
  return context;
};
