<template>
   <header id="">
    <h1 style="display:none;">{{$t($getContentLanguageKey($store.state.pages.item.slug, 'headline', 'WebPage')) }}</h1>s
    <div class="">
      <div class="">
        <svg
          xmlns="http://www.w3.org/2000/svg" version="1.1" id="menu-icon" x="0" y="0" width="19.2" height="12" viewBox="0 0 19.2 12" enable-background="new 0 0 19.188 12.031" xml:space="preserve">
          <path class="thb-top-line" fill="none" stroke="#161616" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M1.1 1h12c2.8 0 5.1 2.2 5.1 5 0 2.8-2.3 5-5.1 5l-12-10"/>
          <path class="thb-mid-line" fill="none" stroke="#161616" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M1.1 6h12"/>
          <path class="thb-bottom-line" fill="none" stroke="#161616" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M1.1 11h12c2.8 0 5.1-2.2 5.1-5 0-2.8-2.3-5-5.1-5l-12 10"/>
        </svg>
      </div>
      <a href="index.html" class="">
        <img src="/images/logo_black.png" class="" alt="TwoFold"/>
        <img src="/images/logo_white.png" class="" alt="TwoFold"/>
      </a>
      <em style="font-size: 0.8em">Villa - Essaouira</em>
    </div>
    <div class="">
      <breadcrumb :params="params" :data="params"/>
    </div>
  </header>
</template>

<script>
import Breadcrumb from '~/components/theme-default/components/BreadcrumbDefault'
// import LangSwitcher from '~/components/theme-modern-immobilier/components/TheLangSwitcher'
export default {
    name: 'HeaderDefault',
    components : {
      Breadcrumb
        // LangSwitcher
    },
    props: {
        params: {
            type: Object
        }
    },
    data () {
      return {}
    }
}
</script>
<style type="text/css">
</style>
