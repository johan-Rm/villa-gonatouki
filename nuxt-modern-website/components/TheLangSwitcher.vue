<template>
<div class="the-lang-switcher">
  <label>Language :</label>
  <div
      v-for="locale in availableLocales"
      :key="locale.code"
      class="item"
      :class="params.cssClass"
      >
        <nuxt-link
        class="lang_switcher"
        :to="switchLocalePath(locale.code)"
        >
          {{ locale.code }}
        </nuxt-link>
  </div>
</div>
</template>

<script>
import toLower from 'lodash.tolower'

export default {
  name: 'TheLangSwitcher',
  props: {
    params: {
        type: Object
    }
  },
  computed: {
    availableLocales () {
      var locales = []
      var i18n = this.$i18n
      this.$i18n.locales.forEach(function(locale, i){
        if('fr' === locale.code) {
          locales.push(locale)
        } else if('Bonjour le monde' !== i18n.t('Bonjour le monde', locale.code)) {
          locales.push(locale)
        }
      })

      return locales
    }
  },
   methods: {
    getImage(lang) {

        return process.env.CDN_URL + process.env.DEFAULT_MEDIA_PATH + toLower(lang) + "-flag-square-icon-32.png"
    }
  }
}
</script>

<style  scoped>
.the-lang-switcher label {
  color: var(--color-secondary);
  font-size: inherit;
}

.the-lang-switcher .text-secondary  {
  margin: 2px 7px;
  min-width: 25px;
  text-align: center;
}

.the-lang-switcher .text-secondary a.lang_switcher {
  color: var(--color-secondary);
  text-transform: uppercase;
}

.the-lang-switcher > .item {
  display:inline-block;
  /*padding:0 5px;*/
  margin-right: 5px;
}

.the-lang-switcher .text-secondary a.lang_switcher.nuxt-link-exact-active {
  /*color: var(--color-secondary-rgba-80) !important;*/
  color: #fff !important;
  font-weight:600 !important;
}

/* à reprendre */
.the-lang-switcher.row > div {
  /*background-color: var(--color-primary-rgba-80);*/
  border-radius: 7px;
  padding: 3px;
  text-align: center;
}

.the-lang-switcher.row > div a {

  text-transform: uppercase;
}
</style>
