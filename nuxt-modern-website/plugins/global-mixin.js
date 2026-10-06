import Vue from 'vue'
import { mapState, mapActions } from 'vuex'
// import enFR from '../lang/translations/slug/en-fr.json'
// import en from '../lang/translations/slug/en.json'
import { LOCALES, ROUTES } from '~/config/router'

Vue.mixin({
  data: () => ({
    locales: LOCALES
  }),
  computed: {
    ...mapState('i18n', ['currentLocale'])
  },
  methods: {
    ...mapActions({
      setLocale: 'i18n/setLocale'
    }),
    getLocalizedRoute (route, locale) {
      
      locale = locale || this.$i18n.locale
      // If route parameters is a string, consider it as the route's name
      if (typeof route === 'string') {
        route = { name: route }
      }
      // Build localized route options
      const baseRoute = Object.assign({}, route, { name: `${route.name}___${locale}` })
     
      // Resolve localized route
      const resolved = this.$router.resolve(baseRoute)
      let { href } = resolved
      // Handle exception for homepage
      if (route.name === 'index') {
        href += '/'
      }
      // console.log('getLocalizedRoute')
      // console.log(this.$router)
      // console.log(baseRoute)
      // console.log(route)
      // console.log(href)
      
      // Cleanup href
      href = (href.match(/^\/\/+$/)) ? '/' : href

      return href
    },
    getRouteBaseName (route) {
      // console.log('getRouteBaseName')
      // console.log(this.$route)
      // console.log(route)

      route = route || this.$route
      if (!route.name) {

        return null
      }
      
      for (let i = LOCALES.length - 1; i >= 0; i--) {
        const regexp = new RegExp(`-${LOCALES[i].code}$`)
        // console.log(regexp)
        if (route.name.match(regexp)) {

          return route.name.replace(regexp, '')
        }
      }
    },
    getSwitchLocaleRoute (locale) {
      console.log('getSwitchLocaleRoutezzz')
      const name = this.getRouteBaseName()
      // console.log(this.$route)
      if (!name) {

        return ''
      }
      const baseRoute = Object.assign({}, this.$route, { name })

      return this.getLocalizedRoute(baseRoute, locale)
    }

  }
})
