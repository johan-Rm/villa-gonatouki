  // import _colors from './assets/scss/color.scss'
// import colors from 'vuetify/es5/util/colors'

const { generateDefaultRoutes, generateRoutes } = require('./plugins/router')
const axios = require('axios')
var _routes = []
const { I18N, ROUTES, DEFAULT_LOCALE, LOCALES } = require('./config/router')

export default {
  /*
  ** Nuxt rendering mode
  ** See https://nuxtjs.org/api/configuration-mode
  */
  // mode: 'universal',
  /*
  ** Nuxt target
  ** See https://nuxtjs.org/api/configuration-target
  */
  target: 'server',
  /*
  ** Transition and Loading
  */
  loading: {
    color: '#f4511e',
    height: '3px'
  },
  pageTransition: {
      name: 'page',
      mode: 'out-in',
      beforeLeave (el) {
          // console.log('Before leave...')
      }
  },
  version: process.env.npm_package_version,
  /*
  ** Headers of the page
  ** See https://nuxtjs.org/api/configuration-head
  */
  head: {
    title: process.env.npm_package_name || '',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      // { "http-equiv": 'ScreenOrientation', content: 'autoRotate:disabled' },
      { hid: 'description', name: 'description', content: process.env.npm_package_description || '' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: process.env.URL_CDN + '/favicon.ico' },
      { rel: 'preconnect', href: process.env.URL_API },
      { rel: 'dns-prefetch', href: "http://fonts.googleapis.com/" },
      // { rel: 'stylesheet', href: 'https://twofold.fuelthemes.net/wp-content/themes/twofold-wp/assets/css/font-awesome.min.css?ver=4.7.0' }
    ],
    script: [

      // { src: 'https://twofold.fuelthemes.net/wp-includes/js/jquery/jquery.js?ver=1.12.4-wp' },
      // { src: 'https://twofold.fuelthemes.net/wp-content/themes/twofold-wp/assets/js/vendor.min.js?ver=3.4.2', body: true },
      // { src: 'https://twofold.fuelthemes.net/wp-includes/js/underscore.min.js?ver=1.8.3', body: true },
      // { src: 'https://twofold.fuelthemes.net/wp-content/themes/twofold-wp/assets/js/app.min.js?ver=3.4.2', body: true },
      // { src: '/js/custom_app.js', body: true, defer: true },
      // { src: '/js/app.js', body: true, defer: true }

      { src: '/js/jquery.js' },
      { src: '/js/vendor.min.js?ver=3.4.2', body: true },
      { src: '/js/underscore.min.js?ver=1.8.3', body: true },
      { src: '/js/app.js', body: true, defer: true },

      { src: '/js/custom_app.js', body: true, defer: true },

    ]
  },
  webfontloader: {
    custom: {
      families: [
          // 'Poppins:n3,n3i,n4,n4i,n5,n5i,n6,n6i,n7,n8,n8i,n9'
          // , 'Roboto+Condensed:n3,n3i,n4,n4i,n7,n7i'
          // , 'Crete+Round:n4,n4i'
          // ,
          'Anonymous+Pro',
          'Rajdhani'
      ],
      urls: [
        // 'https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,500i,600,600i,700,800,800i,900&display=swap',
        // 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i&display=swap',
        // 'https://fonts.googleapis.com/css?family=Crete+Round:400,400i&amp;subset=latin-ext&display=swap',
        'https://fonts.googleapis.com/css2?family=Anonymous+Pro&display=swap',
        'https://fonts.googleapis.com/css2?family=Rajdhani&display=swap',
      ]
    }
  },

  /*
  ** Global CSS
  */
  css: [
    
    '~/assets/css/fontawesome/css/font-awesome.min.css',
    '~/assets/css/fontawesome/css/font-awesome-brands.min.css',
    '~/assets/css/fontawesome/css/font-awesome-regular.min.css',
    '~/assets/css/fontawesome/css/font-awesome-solid.min.css',
    '~/assets/scss/reset_css_vuetify.scss',
    '~/assets/scss/main.scss',
    '~/assets/css/app.css',
    // '~/assets/css/thb-app-inline-css.css',
    '~/assets/scss/custom.scss',
    // '~/assets/css/vuetify.min.css',
  ],

  router: {
    mode: 'history',
    middleware: [  'store' ],
    extendRoutes (routes, resolve) {
        // const newRoutes = generateDefaultRoutes(routes)
        // routes.splice(0, routes.length)
        // routes.unshift(...newRoutes)
    },
    linkActiveClass: 'active-link'
  },

  /*
  ** Plugins to load before mounting the App
  ** https://nuxtjs.org/guide/plugins
  */
  plugins: [
    // { src: '~/plugins/global-mixin.js' },
    { src: '~/plugins/global-functions.js' },
    { src: '~/plugins/filters.js' },
    { src: '~/plugins/vue-awesome-swiper.js', mode: 'client' },
    { src: '~/plugins/vue-hotel-datepicker.js', mode: 'client' },
    { src: '~/plugins/vue-blur.js', mode: 'client' },
    // { src: '~/plugins/vue-flip-toolkit.js', mode: 'client' },
    // { src: '~/plugins/vue-filter-pluralize.js', mode: 'client' },
    { src: '~/plugins/nuxt-video-player.js', mode: 'client' },
    // { src: '~/plugins/vue-phone-number-input.js', mode: 'client' }
    // '~/plugins/vue-lazysizes.client.js'
  ],


  /*
  ** Auto import components
  ** See https://nuxtjs.org/api/configuration-components
  */
  components: true,
  /*
  ** Nuxt.js dev-modules
  */
  buildModules: [
    // Doc: https://github.com/nuxt-community/eslint-module
    /**
    * Eslint-module a posé des problèmes lorsque
    * je souhaitais charger les librairires externes
    **/
    // '@nuxtjs/eslint-module',
    '@nuxtjs/color-mode',
    '@aceforth/nuxt-optimized-images',
    // "@nuxtjs/vuetify",
  ],

  // https://github.com/juliomrqz/nuxt-optimized-images
  optimizedImages: {
    inlineImageLimit: 1000,
    imagesName: ({ isDev }) => isDev ? '[path][name][hash:optimized].[ext]' : 'img/[contenthash:7].[ext]',
    responsiveImagesName: ({ isDev }) => isDev ? '[path][name]--[width][hash:optimized].[ext]' : 'img/[contenthash:7]-[width].[ext]',
    handleImages: ['jpeg', 'png', 'svg', 'webp', 'gif'],
    optimizeImages: true,
    optimizeImagesInDev: false,
    defaultImageLoader: 'img-loader',
    mozjpeg: {
      quality: 80,
    },
    optipng: {
      optimizationLevel: 3,
    },
    pngquant: false,
    gifsicle: {
      interlaced: true,
      optimizationLevel: 3,
    },
    svgo: {
      // enable/disable svgo plugins here
    },
    webp: {
      preset: 'default',
      quality: 75,
    },
  },

  watchers: {
      webpack: {
        ignored: /node_modules/
      }
  },

  /*
  ** Nuxt.js modules
  */
  modules: [
    // Doc: https://axios.nuxtjs.org/usage
    '@nuxtjs/axios',
    'nuxt-webfontloader',
    // Doc: https://i18n.nuxtjs.org/basic-usage.html
    'nuxt-i18n',
    '@nuxtjs/dotenv',
    '@nuxtjs/vuetify',
    '@nuxtjs/device',
    ['@nuxtjs/dayjs', {
        locales: ['fr', 'en'],
        defaultLocale: 'fr'
    }],
    '@nuxtjs/device',
    'vuejs-google-maps/nuxt'
    // 'nuxt-responsive-loader'
  ],

  googleMaps: {
    apiKey: process.env.GOOGLE_MAPS_API_KEY || '', libraries: [/* rest of libraries */]
  },
  
  // https://github.com/geeogi/nuxt-responsive-loader
  // Specify your options as a responsiveLoader object
  // responsiveLoader: {
  //   name: 'img/[hash:7]-[width].[ext]',
  //   quality: 65 // choose a lower value if you want to reduce filesize further
  // },

  vuetify: {
    // treeShake: true
    //   loaderOptions: { registerStylesSSR: true }
    // },
    // defaultAssets: false,
  //   customVariables: ['~/assets/variables.scss'],
  //   theme: {
  //     dark: true,
  //     themes: {
  //       dark: {
  //         primary: colors.blue.darken2,
  //         accent: colors.grey.darken3,
  //         secondary: colors.amber.darken3,
  //         info: colors.teal.lighten1,
  //         warning: colors.amber.base,
  //         error: colors.deepOrange.accent4,
  //         success: colors.green.accent3
  //       }
  //     }
  //   }
  },

  /*
  ** I18n
  */
  i18n: {
    strategy: 'prefix_except_default',
    defaultLocale: DEFAULT_LOCALE,
    locales: LOCALES,
    parsePages: false,   // Disable babel parsing
    pages: ROUTES,
    vueI18n: {
      fallbackLocale: DEFAULT_LOCALE,
      messages: {
        en: I18N.en,
        fr: I18N.fr,
        es: I18N.es
      }
    }
  },


  /*
  ** Axios module configuration
  ** See https://axios.nuxtjs.org/options
  */
  axios: {
      baseURL: process.env.URL_API,
      proxyHeaders: false,
      credentials: false
      // proxy: true
  },
  /*
  ** Build configuration
  ** See https://nuxtjs.org/api/configuration-build/
  */
  build: {
    extractCSS: true,
    // loaders: {
    //   vueStyle: { manualInject: true }
    // },
    // splitChunks: {
    //   pages: false
    // },
    cssSourceMap: true,
    maxChunkSize: 300000,
    // publicPath: process.env.CDN_URL,
    // vendor: [ 'vuetify' ],
    // plugins: [
    //     new webpack.ProvidePlugin({
    //         $: 'jquery',
    //         jQuery: 'jquery',
    //         'window.jQuery': 'jquery'
    //     })
    // ],
    /*
    // ** Vous pouvez étendre la configuration webpack ici
   */
    extend (config, { isDev, isClient, loaders: { vue } }) {
      // Extend only webpack config for client-bundle
      if (isClient) {
        config.devtool = 'source-map'
      }

      

      // if (isClient) {
      //   vue.transformAssetUrls.img = ['data-src', 'src']
      //   vue.transformAssetUrls.source = ['data-srcset', 'srcset']
      // }

      // config.module.rules.unshift({
      //   test: /\.(png|jpe?g|gif)$/,
      //   use: {
      //     loader: 'responsive-loader',
      //     options: {
      //       // disable: isDev,
      //       placeholder: true,
      //       quality: 85,
      //       placeholderSize: 30,
      //       name: 'img/[name].[hash:hex:7].[width].[ext]',
      //       adapter: require('responsive-loader/sharp'),
      //       // sizes: [320, 640, 960, 1200, 1800, 2400],
      //     }
      //   }
      // })

    }
   // extend(config, ctx) {
      // Exécuter ESLint lors de la sauvegarde
      // if (ctx.isDev && ctx.isClient) {
        // config.module.rules.push({
        //   enforce: "pre",
        //   test: /\.(js|vue)$/,
        //   loader: "eslint-loader",
        //   exclude: /(node_modules)/,
        //   options: {
        //     fix: true
        //   }
        // })
        // config.module.rules.push({
        //     test: /\.(js|jsx)$/i,
        //     loader: 'file-loader',
        //     options: {
        //       name: '[path][name].[ext]'
        //     },
        //     // exclude: /TweenLite.min.js/
        // })
      // }
    // }
  },
  generate: {
    dir: 'dist_tmp',
    cache: {
      ignore: [
        // When something changed in the docs folder, do not re-build via webpack
        'docs',
        'dist',
        'log',
        'lang',
        'synchronization'
      ]
    },
    interval: 1500,
    concurrence: 50,
    crawler: false,
    routes: function (callback) {
        let params = { isActive : 'true', pagination: false }
        // let _routes = []

        const routeIndex = process.argv.indexOf('--full_website')
        if (routeIndex != -1) {
            const dynamicRoutes = {
                // 'index': {
                //     routeName: ''
                // },
                'web_pages': {
                    routeName: 'web_pages'
                },
                'articles': {
                    routeName: 'full_articles'
                },
                // 'accommodation_types': {
                //     routeName: 'accommodation_types'
                // },
                'tags': {
                    routeName: 'tags'
                },
                // 'accommodations': {
                //     routeName: 'full_accommodations'
                // }
            }

            /**
            * VERSION AVEC jJSON FILE
            **/
            // import('./data/web_pages.json').then((result) => {
            //   const web_pages = { data: result }
            //   _routes.concat(generateRoutes(_routes, dynamicRoutes['web_pages'].routeName, web_pages))

            //   callback(null, _routes)
            // }).catch(callback)
            callback(null, _routes)


            /**
            * VERSION AVEC API
            **/

            // axios.all([
            //     axios.get(process.env.URL_API +  '/' + dynamicRoutes['web_pages'].routeName, { params })
            //     ,axios.get(process.env.URL_API +  '/' + dynamicRoutes['articles'].routeName, { params })
            //     ,axios.get(process.env.URL_API +  '/' + dynamicRoutes['tags'].routeName, { params })
            //     // ,axios.get(process.env.URL_API +  '/' + dynamicRoutes['accommodation_types'].routeName, { params })
            //     // ,axios.get(process.env.URL_API +  '/' + dynamicRoutes['accommodations'].routeName, { params })
            // ])
            // .then(axios.spread(
            //     function (
            //         web_pages
            //         , articles
            //         , tags
            //         , accommodation_types
            //         , accommodations
            // ) {

            //         _routes.concat(generateRoutes(_routes, dynamicRoutes['web_pages'].routeName, web_pages))
            //         _routes.concat(generateRoutes(_routes, dynamicRoutes['articles'].routeName, articles))
            //         // _routes.concat(generateRoutes(_routes, dynamicRoutes['accommodation_types'].routeName, accommodation_types))
            //         _routes.concat(generateRoutes(_routes, dynamicRoutes['tags'].routeName, tags))
            //         // _routes.concat(generateRoutes(_routes, dynamicRoutes['accommodations'].routeName, accommodations))

            //     callback(null, _routes)

            // })).catch(callback)

        } else {
          const routeIndex = process.argv.indexOf('--route')
          if (routeIndex != -1) {
              const route = process.argv[routeIndex + 1] // ok
              var url = '/' + route

              const pathIndex = process.argv.indexOf('--path')
              if (pathIndex != -1) {
                  const path = process.argv[pathIndex + 1]
                  var url = path
              }

              // console.log(process.env.URL_API + url)
              axios.get(process.env.URL_API + url, { params }).then((result) => {
                  _routes = generateRoutes(_routes, route, result)
                  callback(null, _routes)
              }).catch(callback)
          }
        }
      }
    }
}
