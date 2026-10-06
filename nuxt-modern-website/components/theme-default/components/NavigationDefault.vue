<template>
<nav>
    <h1>Navigation</h1>
    <div>
      <ul>
        <li>
          <nuxt-link to="/">Accueil</nuxt-link>
        </li>
        <li>
          <nuxt-link to="/about">La villa</nuxt-link>
        </li>
        <li>
          <nuxt-link to="/rooms">Les chambres</nuxt-link>
        </li>
        <li>
          <nuxt-link to="/activities">Les activités</nuxt-link>
        </li>
        <li>
          <nuxt-link to="/privatization">Privatisation</nuxt-link>
        </li>
        <li>
          <nuxt-link to="/contact">Contact</nuxt-link>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="small-12 medium-12 columns">
        <span>+212 658 256 247</span>
      </div>
      <div class="small-12 medium-12 columns">
        <span>reservation@gonatouki.com</span>
      </div>
      <div class="small-12 medium-12 columns">
        <a href="https://www.facebook.com/FuelThemes/" class="social facebook" target="_blank">
          <i class="fa fa-facebook"></i>
        </a>
        <a href="index.html%3Fp=537.html#" class="social pinterest" target="_blank">
          <i class="fa fa-pinterest"></i>
        </a>
        <a href="index.html%3Fp=537.html#" class="social twitter" target="_blank">
          <i class="fa fa-twitter"></i>
        </a>
        <a href="index.html%3Fp=537.html#" class="social instagram" target="_blank">
          <i class="fa fa-instagram"></i>
        </a>
        <a href="index.html%3Fp=537.html#" class="social tumblr" target="_blank">
          <i class="fa fa-tumblr"></i>
        </a>
        <a href="index.html%3Fp=537.html#" class="social deviantart" target="_blank">
          <i class="fa fa-spotify"></i>
        </a>
      </div>
    </div>
  </nav>
</template>
<script>
// import SocialMedia from '~/components/theme-modern-immobilier/components/SocialMedia'
// import SearchButton from '~/components/theme-modern-immobilier/components/SearchButton'
export default {
    name:'NavigationDefault',
    data () {
        return {}
    }
}
</script>
<style scoped>
</style>