<template>
<div class="page-wraper" :class="classContainerSideDatesOpen">
  <v-app>
    <TheHeader/>
    <TheNavigationMenu/>
    <div class="menu_overlay"></div>
    <!-- <div v-show="isContainerSideDatesOpen" class="page_overlay"></div> -->
    <div id="wrapper">
        <Nuxt/>
        <!-- <Nuxt v-if="render"/> -->
          <!-- <div v-else class="loading_render">
              <div class="message">
                  <span>Nous preparons votre chambre ...</span>
              </div>
          </div> -->
    </div>
    <LazyTheContainerSide v-if="isContainerSideDatesOpen" :params="params.dates"/>
    <LazyTheContainerSide v-if="isContainerSideBookingOpen" :params="params.booking"/>
  </v-app>
</div>
</template>

<script>
import { mapState } from 'vuex'
import TheHeader from '~/components/TheHeader'
import TheNavigationMenu from '~/components/TheNavigationMenu'
import TheContainerSide from '~/components/TheContainerSide'
export default {
  components : {
    TheNavigationMenu,
    TheHeader,
    TheContainerSide
  },
  data() {
    return {
      params: {
        booking: {
          template: "booking"
        },
        dates: {
          template: "dates"
        }
      }
    }
  },
  head() {

    return {
      htmlAttrs: {
        lang: this.$i18n.defaultLocale
      },
      bodyAttrs: {
        class: this.$store.state.organizations.config.bodyClass
      },
      // title:  `${ this.$store.state.pages.item.metaTitle } | ${ this.$store.state.organizations.item.name }`,
      // __dangerouslyDisableSanitizers: ['script'],
      script: [
      //   { innerHTML: JSON.stringify(this.structuredData), type: 'application/ld+json' }
        // {
        //   src: '/js/custom.js',
        //   body: true,
        //   async: true,
        //   defer: true
        // },
        // {
        //   src: '/js/app.js',
        //   body: true,
        //   async: true,
        //   defer: true
        // }
      ],
      meta: [
          { charset: 'utf-8' },
          { name: 'viewport', content: 'width=device-width, initial-scale=1' },
          // {
          //     hid: 'description'
          //     , name: 'description'
          //     , content: this.$store.state.pages.item.metaDescription
          // },
          // {
          //   hid: `og:title`,
          //   property: 'og:title',
          //   content: this.$store.state.pages.item.metaTitle
          // },
          // {
          //   hid: `og:description`,
          //   property: 'og:description',
          //   content: this.$store.state.pages.item.metaDescription
          // },
          // {
          //   hid: `og:url`,
          //   property: 'og:url',
          //   content: process.env.WEB_HOST + this.$route.fullPath
          // },
          // {
          //   hid: `og:type`,
          //   property: 'og:type',
          //   content: 'WebPage'
          // },
          // {
          //   hid: `og:locale`,
          //   property: 'og:locale',
          //   content: this.$store.state.i18n.currentLocale
          // },
          // {
          //   hid: `og:image`,
          //   property: 'og:image',
          //   content: process.env.CDN_URL + process.env.DEFAULT_MEDIA_PATH + filename
          // },
          // {
          //   hid: `og:site_name`,
          //   property: 'og:site_name',
          //   content: this.$store.state.organizations.item.name
          // }
      ],
    }
  },
  computed: {
    ...mapState({
      classContainerSideDatesOpen: state => state.organizations.config.classContainerSideDatesOpen,
      isContainerSideDatesOpen: state => state.organizations.config.isContainerSideDatesOpen,
      isContainerSideBookingOpen: state => state.organizations.config.isContainerSideBookingOpen,
      render: state => state.organizations.render
    })
  },
  mounted: function () {
    this.$nextTick(function () {
      this.onResize()
    })
    window.addEventListener('resize', this.onResize)
    console.log('resizzzze me')
  },
  methods: {
    onResize() {
      console.log('Resized layouuuuut')
      const vh = window.innerHeight * 0.01;
      document.documentElement.style.setProperty('--vh', `${vh}px`);
    }
  }
    // mounted() {
    //     this.$store.commit('organizations/setRender', true)
    // }
}
</script>

<style lang="scss">
.page-wraper .menu_overlay {
    z-index: 40 !important;
}

.loading_render {
    width: 100%;
    height: 100%;
    background: var(--color-primary);
    color: #fff;
}

.loading_render .message {
    width: 100%;
    position: absolute;
    top:50%;
    text-align: center;
}

.loading_render .message span {
    // display: block;
    text-transform: uppercase;
}
</style>
