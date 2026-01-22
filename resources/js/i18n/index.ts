import { createI18n } from 'vue-i18n';

import en from './locales/en.json';
import bn from './locales/bn.json';

export type AppLocale = 'en' | 'bn';

const STORAGE_KEY = 'rg_locale';

export function getInitialLocale(): AppLocale {
    if (typeof window === 'undefined') return 'en';
    const saved = window.localStorage.getItem(STORAGE_KEY);
    if (saved === 'bn' || saved === 'en') return saved;
    const browser = window.navigator.language?.toLowerCase() || '';
    return browser.startsWith('bn') ? 'bn' : 'en';
}

export function setAppLocale(next: AppLocale) {
    if (typeof document !== 'undefined') {
        document.documentElement.lang = next;
    }
    if (typeof window !== 'undefined') {
        window.localStorage.setItem(STORAGE_KEY, next);
    }
}

export const i18n = createI18n({
    legacy: false,
    locale: getInitialLocale(),
    fallbackLocale: 'en',
    messages: {
        en,
        bn,
    },
});

