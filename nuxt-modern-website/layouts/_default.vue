<template>
<div class="page-wraper">

  <div id="wrapper">
    <Nuxt />
  </div>

</div>
</template>
<script>
import TheNavigationMenu from '~/components/TheNavigationMenu'
import TheHeader from '~/components/TheHeader'
export default {
  head() {

    return {
      htmlAttrs: {
        lang: this.$i18n.defaultLocale
      },
      // bodyAttrs: {
        // class: this.$store.state.organizations.config.bodyClass
      // },
      // title:  `${ this.$store.state.pages.item.metaTitle } | ${ this.$store.state.organization.item.name }`,
      // __dangerouslyDisableSanitizers: ['script'],
      // script: [
      //   { innerHTML: JSON.stringify(this.structuredData), type: 'application/ld+json' }
      // ],
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
  components : {
    TheNavigationMenu,
    TheHeader,
    // TheFooter,
    // TheScrollTop
  },
  mounted() {
    const script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "https://twofold.fuelthemes.net/wp-content/themes/twofold-wp/assets/js/app.min.js?ver=3.4.2";
    document.body.appendChild(script);
  }
}
</script>
<style lang="scss">
/*
@import '@/assets/css/app.css';
@import '@/assets/css/thb-app-inline-css.css';
@import '@/assets/scss/custom.scss';
*/
</style>
