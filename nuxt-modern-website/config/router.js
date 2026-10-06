// i18n messages
const en = require('../lang/en.json')
const fr = require('../lang/fr.json')
const es = require('../lang/es.json')

// const en = {}
// const fr = {}
// const es = {}

// i18n config
const LOCALES = [
    { code: 'fr', iso: 'fr-FR', name: 'FR' }
    ,{ code: 'en', iso: 'en-US', name: 'EN' }
    ,{ code: 'es', iso: 'en-ES', name: 'ES' }
]
const DEFAULT_LOCALE = 'fr'
const I18N = {
  en,
  fr,
  es
}

// Define custom paths for localized routes
// If a route/locale is omitted, defaults to Nuxt's generated path
const ROUTES = {
    index: {
       fr: '/',
       en: '/',
       es: '/',
    },
    contact: {
       fr: '/contact',
       en: '/contact',
       es: '/contactar',
    },
    about: {
      fr: '/la-villa',
      en: '/the-villa',
      es: '/la-villa',
    },
    rooms: {
      fr: '/les-chambres',
      en: '/the-rooms',
      es: '/las-habitaciones',
    },
    services: {
      fr: '/les-services',
      en: '/services',
      es: '/los-servicios',
    },
    activities: {
      fr: '/les-activites',
      en: '/activities',
      es: '/las-actividades',
    },
    privatization: {
      fr: '/privatisation',
      en: '/privatization',
      es: '/privatizacion',
    },
    // 'page': {
    //    fr: '/:page',
    //    en: '/:page',
    //    entities: { page: 'webPage' }
    // },
    // 'news': {
    //     fr: '/actualite',
    //     en: '/news',
    //     entities: { }
    // },
    // 'news-category': {
    //     fr: '/actualite/:category',
    //     en: '/news/:category',
    //     entities: { category: 'tag' }
    // },
    // 'news-category-slug': {
    //     fr: '/actualite/:category/:slug',
    //     en: '/news/:category/:slug',
    //     entities: { category: 'tag', slug: 'article' }
    // }
}

module.exports = {
  LOCALES,
  DEFAULT_LOCALE,
  I18N,
  ROUTES
}
