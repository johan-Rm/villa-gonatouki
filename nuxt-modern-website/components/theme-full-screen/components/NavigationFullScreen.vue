<template>
<nav
    id="navigation-menu"
    class="nav-primary _bg-white"
    data-menu-speed="0.5"
    data-behaviour="thb-submenu"
>
    <h1 style="display:none;">La villa Gonatouki, Essaouira</h1>
   <!--  <div class="half-circle">
      <em class="location">Villa - Essaouira</em>
    </div> -->

    <div class="custom_scroll" id="menu-scroll">
      <ul id="  menu-navigation" class="navigation-menu">
        <li class="menu-item menu-item-type-post_type menu-item-object-page current-menu-ancestor current-menu-parent current_page_parent current_page_ancestor menu-item-has-children menu-item-372">
          <nuxt-link :to="localePath('index')">{{ $t($getContentLanguageKey('accueil', 'headline', 'WebPage')) }}</nuxt-link>
        </li>
        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-486">
          <nuxt-link :to="localePath('about')">{{ $t($getContentLanguageKey('la-villa-gonatouki', 'headline', 'WebPage')) }}</nuxt-link>
        </li>
        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-373">
          <nuxt-link :to="localePath('rooms')">{{ $t($getContentLanguageKey('les-chambres', 'headline', 'WebPage')) }}</nuxt-link>
        </li>
        <li class="menu-item menu-item-type-post_type menu-item-object-gallery menu-item-has-children menu-item-379">
          <nuxt-link :to="localePath('activities')">{{ $t($getContentLanguageKey('les-activites', 'headline', 'WebPage')) }}</nuxt-link>
        </li>
        <li class="menu-item menu-item-type-post_type menu-item-object-gallery menu-item-has-children menu-item-379">
          <nuxt-link :to="localePath('services')">{{ $t($getContentLanguageKey('les-services', 'headline', 'WebPage')) }}</nuxt-link>
        </li>
        <li class="menu-item menu-item-type-post_type menu-item-object-page current-menu-ancestor current-menu-parent current_page_parent current_page_ancestor menu-item-has-children menu-item-372">

                <!-- <span class="sub" ><em></em></span> -->
                <nuxt-link :to="localePath('privatization')">{{ $t($getContentLanguageKey('privatization', 'headline', 'WebPage')) }}</nuxt-link>
              <!-- <ul class="sub-menu">
                <li class="menu-item menu-item-type-post_type menu-item-object-collection menu-item-478"><a href="../../collection/collection-1/index.html">Les offres</a></li>
                <li class="menu-item menu-item-type-post_type menu-item-object-collection menu-item-482"><a href="../../collection/collection-2/index.html">Séminaire</a></li>
                <li class="menu-item menu-item-type-post_type menu-item-object-collection menu-item-481"><a href="../../collection/collection-3/index.html">Mariage</a></li>
              </ul> -->
        </li>
        <li class="menu-item menu-item-type-post_type menu-item-object-collection menu-item-has-children menu-item-439">
          <nuxt-link :to="localePath('contact')">{{ $t($getContentLanguageKey('contact', 'headline', 'WebPage')) }}</nuxt-link>
        </li>
      </ul>
    </div>
    <div class="bottom-pre-header row">
      <div class="small-4 medium-4 columns"></div>
      <div class="small-4 medium-4 columns"></div>
      <div class="small-4 medium-4 columns"></div>
    </div>
    <div class="bottom-header row no-padding">
      <div class="small-12 medium-12 columns">
        <span>+212 658 256 247</span>
      </div>
      <div class="small-12 medium-12 columns">
        <span>reservation@gonatouki.com</span>
      </div>
      <hr class="separate navigation"/>
      <div class="small-12 medium-12 columns">
<div class="">

                <a href="https://www.facebook.com/FuelThemes/" class="social facebook" target="_blank">
                <i class="fab fa-facebook"></i>
                </a>

                <a href="index.html%3Fp=537.html#" class="social instagram" target="_blank">
                  <i class="fab fa-instagram"></i>
                </a>
                <a href="index.html%3Fp=537.html#" class="social tripadvisor" target="_blank">
                  <i class="fab fa-tripadvisor"></i>
                </a>
</div>


            <TheLangSwitcher :params="params.langSwitcher"/>

      </div>
    </div>
  </nav>
</template>
<script>
// import SocialMedia from '~/components/theme-modern-immobilier/components/SocialMedia'
// import SearchButton from '~/components/theme-modern-immobilier/components/SearchButton'
import TheLangSwitcher from '~/components/TheLangSwitcher'
export default {
    name:'NavigationFullScreen',
    data () {
        return {
          params: {
            langSwitcher: {
              cssClass: "text-secondary"
            }
          }
        }
    },
    components: {
      TheLangSwitcher
    },
    methods: {
        openSubMenu(event) {
            // console.log('openSubMenu')
            if (event) {
                event.stopPropagation()
            }
        },
        goTo(page) {
            // console.log('goTo')
            this.$router.push(page)
        },
        getBackgroundImage: function (filename, format) {
            // :style="getBackgroundImage('piscines-img-31821')"
            // style="background-size: contain;
            if(null !== filename) {
                return 'background-image:url(' + process.env.WEB_HOST + process.env.PATH_DEFAULT_MEDIA + filename + ')'
            }

            return null
        }
    }
}
</script>
<style scoped>

#navigation-menu {
	border-radius: 0px 0px 15px 0px;
	/* border: 5px solid var(--color-secondary); */
	border-left:0;
  padding: 35px 20px 75px 20px;
	/*background-image: url("https://www.transparenttextures.com/patterns/clean-gray-paper.png");*/
  z-index: 500;
}

.navigation-menu li a {
	padding: 15px 0 15px 60px;
}

#navigation-menu em.location {
	padding: 15px 0 15px 60px;
    font-size: 0.7em;

}

#navigation-menu #menu-scroll {
    padding: 50px 0 0 0;
    height: 85%;
}

#navigation-menu
, #navigation-menu a
{
	/* color: var(--color-secondary); */
	color: var(--color-primary);
}

#navigation-menu li.menu-item-has-children a span em::before,
#navigation-menu li.menu-item-has-children a span em::after
{
  background: #fff;
}

#navigation-menu.transparent {
	background-color: transparent;
}

#navigation-menu.transparent
, #navigation-menu.transparent a
{
	/* color: var(--color-secondary); */
	color: var(--color-secondary-20);
}


#navigation-menu.nav-primary
{
	background-color: var(--color-primary);
}



#navigation-menu.nav-primary
, #navigation-menu.nav-primary a
{
	/* color: var(--color-secondary); */
	color: #fff;
}

#navigation-menu.secondary {
	background-color: var(--color-secondary);
}

#navigation-menu.secondary
, #navigation-menu.secondary a
{
	/* color: var(--color-secondary); */
	color: var(--color-primary);
}


#navigation-menu.nav-primary.bg-white
{
  background-color: #fff;
}

#navigation-menu.nav-primary.bg-white
, #navigation-menu.nav-primary.bg-white a
{
  /* color: var(--color-secondary); */
  color: var(--color-primary);
}

#navigation-menu.nav-primary.bg-white em
{
  color: #fff;
}

#navigation-menu.nav-primary.bg-white .bottom-header
{
  background: var(--color-secondary);
}

#navigation-menu.nav-primary.bg-white .bottom-header,
#navigation-menu.nav-primary.bg-white .bottom-header a
{
  color: #fff;
}

.half-circle {
    width: 200px;
    height: 50px; /* as the half of the width */
    background-color: var(--color-primary);
    border-top-left-radius: 50px;  /* 100px of height + 10px of border */
    border-top-right-radius: 50px; /* 100px of height + 10px of border */

    border-bottom: 0;
}

#navigation-menu a.social {
    display: inline-block;
    margin:2px 7px;
}

#navigation-menu a.social i {
	font-size: 24px;
}

#navigation-menu hr.separate {
	width: calc(100% - 1px);

}

#navigation-menu .disabled-link {
   color: grey;
   pointer-events: none;
   cursor: pointer;
}

.navigation-menu li.menu-item-has-children span.sub {
	display: flex;
	width: 25px;
	height: 25px;
	position: absolute;
	left: 0;
	top: 50%;
	align-items: center;
	justify-content: center;
	transform: translateY(-50%);
	cursor: pointer;
}

.navigation-menu li.menu-item-has-children span.sub em {
	display: block;
	position: relative;
	width: 11px;
	height: 11px;
}

#navigation-menu li.menu-item-has-children span.sub em::before
, #navigation-menu li.menu-item-has-children span.sub em::after {
	background: #fff;
}

.navigation-menu li.menu-item-has-children span.sub em::before {
	content: '';
	position: absolute;
	top: 5px;
	left: 0;
	width: 100%;
	height: 1px;
	background: #151515;
}

.navigation-menu li.menu-item-has-children span.sub em::after {
	content: '';
	position: absolute;
	top: 0;
	left: 5px;
	height: 100%;
	width: 1px;
	background: #151515;
}

#navigation-menu .bottom-header span {
    padding-left: 5px;
}
</style>
