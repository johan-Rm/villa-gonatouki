import en from '../lang/en.json'
import es from '../lang/es.json'

const has = require('lodash.has')
const axios = require('axios')
const { ROUTES, DEFAULT_LOCALE, LOCALES } = require('../config/router')

/**
 * Generate localized route using Nuxt's generated routes and i18n config
 * @param  {Array}  baseRoutes  Nuxt's default routes based on pages/ directory
 * @param  {Array}  locales     Locales to use for route generation, should be
 *                              used when recursively generating children routes,
 *                              defaults to app's configured LOCALES
 * @return {Array}              Localized routes to be used in Nuxt config
 */
function generateDefaultRoutes (baseRoutes, locales = []) {
  const newRoutes = []
  locales = locales.length ? locales : LOCALES
  baseRoutes.forEach((baseRoute) => {
    locales.forEach((locale) => {
      const { component } = baseRoute
      let { path, name, children } = baseRoute
      if (children) {
        children = generateRoutes(children, [locale])
      }
      const { code } = locale
      if (has(ROUTES, `${name}.${code}`)) {
        path = ROUTES[name][code]
      }
      if (code !== DEFAULT_LOCALE) {
        // Add leading / if needed (ie. children routes)
        if (path.match(/^\//) === null) {
          path = `/${path}`
        }
        // Prefix path with locale code if not default locale
        path = `/${code}${path}`
      }
      const route = { path, component }
      if (name) {
        name += `-${code}`
        route.name = name
      }
      if (children) {
        route.children = children
      }

      newRoutes.push(route)
    })
  })
  return newRoutes
};

function generateRoutes (_routes, route, result) {

  if(result.data['hydra:totalItems'] == 0) {
      throw new Error('No result')
  } else {
      var pages = 1
      var results = result.data['hydra:member']
      var totalItems = result.data['hydra:totalItems']
      if('index' !== route) {
          if(totalItems > results.length) {
              pages = totalItems / results.length
          }
      }

      switch(route) {
          case "web_pages": {
              result.data['hydra:member'].map((data) => {

                  _routes.push({
                      route: '/' + data.slug
                      , payload: {
                          locale: 'fr',
                          webPage: data
                      }
                  })
                  let slug = en.hasOwnProperty('webPage')? en['webPage'][data.slug]: data.slug
                  _routes.push({
                      route: '/en/' + slug
                      , payload: {
                          locale: 'en',
                          webPage: data
                      }
                  })
                  let slugEs = es.hasOwnProperty('webPage')? es['webPage'][data.slug]: data.slug
                  _routes.push({
                      route: '/es/' + slugEs
                      , payload: {
                          locale: 'es',
                          webPage: data
                      }
                  })
              })
              break
          }
          case "full_articles": {
              result.data['hydra:member'].map((data) => {
                  _routes.push({
                      route: '/actualite/' + data.category.slug + '/' + data.slug
                      , payload: {
                          locale: 'fr',
                          article: data
                      }
                  })
                  let slugCategory = en.hasOwnProperty('tag')? en['tag'][data.category.slug]: data.category.slug
                  let slug = en.hasOwnProperty('article')? en['article'][data.slug]: data.slug
                  _routes.push({
                      route: '/en/news/' + slugCategory + '/' + slug
                      , payload: {
                          locale: 'en',
                          article: data
                      }
                  })
              })
              break
          }
          case "full_accommodations": {
              result.data['hydra:member'].map((data) => {

                  /**
                  * Ces routes dépendent de méta données donc susceptile
                  * de réceptionner des erreurs
                  */
                  if('location' == data.nature.slug) {
                      _routes.push({
                          route: '/location/' + data.type.slug + '/' + data.slug
                          , payload: {
                              locale: 'fr',
                              accommodation: data,
                              type : data.type
                          }
                      })

                      let slug = en.hasOwnProperty('accommodation')? en['accommodation'][data.slug]: data.slug
                      _routes.push({
                          route: '/en/renting/' + data.type.slug + '/' + slug
                          , payload: {
                              locale: 'en',
                              accommodation: data,
                              type : data.type
                          }
                      })
                  }

                  if('vente' == data.nature.slug) {
                      _routes.push({
                          route: '/vente/' + data.type.slug + '/' + data.slug
                          , payload: {
                              locale: 'fr',
                              accommodation: data,
                              type : data.type
                          }
                      })
                      let slug = en.hasOwnProperty('accommodation')? en['accommodation'][data.slug]: data.slug
                      _routes.push({
                          route: '/en/selling/' + data.type.slug + '/' + slug
                          , payload: {
                              locale: 'en',
                              accommodation: data,
                              type : data.type
                          }
                      })
                  }

              })
              break
          }
          case "tags": {
              result.data['hydra:member'].map((data) => {
                  _routes.push({
                      route: '/actualite/' + data.slug
                      , payload: {
                          locale: 'fr',
                          tag: data
                      }
                  })
                  let slug = en.hasOwnProperty('tag')? en['tag'][data.slug]: data.slug
                  _routes.push({
                      route: '/en/news/' + slug
                      , payload: {
                          locale: 'en',
                          tag: data
                      }
                  })
              })
              break
          }

          case "accommodation_types": {
              result.data['hydra:member'].map((data) => {
                  _routes.push({
                      route: '/location/' + data.slug
                      , payload: {
                          locale: 'fr',
                          type: data
                      }
                  })
                  _routes.push({
                      route: '/vente/' + data.slug
                      , payload: {
                          locale: 'fr',
                          type: data
                      }
                  })

                  var slug = en.hasOwnProperty('accommodationType')? en['accommodationType'][data.slug]: data.slug
                  _routes.push({
                      route: '/en/renting/' + slug
                      , payload: {
                          locale: 'en',
                          type: data
                      }
                  })
                  var slug = en.hasOwnProperty('accommodationType')? en['accommodationType'][data.slug]: data.slug
                  _routes.push({
                      route: '/en/selling/' + slug
                      , payload: {
                          locale: 'en',
                          type: data
                      }
                  })
              })
              break
          }
      }
  }

  return _routes
};

/**
 * Make a copy of a route
 * @param  {Object} route Route to be cloned
 * @return {Objet}        Route copy
 */
function cloneRoute (route) {
  const clonedRoute = Object.assign({}, route)
  if (route.meta) {
    clonedRoute.meta = Object.assign({}, route.meta)
  }
  if (route.params) {
    clonedRoute.params = Object.assign({}, route.params)
  }
  if (route.query) {
    clonedRoute.query = Object.assign({}, route.query)
  }
  return clonedRoute
};

module.exports = {
  generateDefaultRoutes,
  generateRoutes,
  cloneRoute
}
