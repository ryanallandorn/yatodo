// resources/js/i18n.js
import { register, init, getLocaleFromNavigator, waitLocale } from 'svelte-i18n';

export function setupI18n() {
  const initialLocale = document.documentElement.lang || getLocaleFromNavigator();

  // Register locales dynamically
  ['en', 'es'].forEach(locale => {
    register(locale, () => import(`@lang/${locale}.json`));
  });

  // Initialize svelte-i18n with the initial locale
  init({
    fallbackLocale: 'en',
    initialLocale,
  });

  // Return a promise that resolves when the locale is ready
  return waitLocale();
}
