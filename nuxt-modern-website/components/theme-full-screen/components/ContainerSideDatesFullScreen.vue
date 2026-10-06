<template>
    <transition name="slide-fade-bottom">
    <div class="container-side-wrapper" v-show="isContainerSideDatesOpen">
      <div class="row no-padding p-left">
        <div v-if="!$device.isMobile"
          class="small-12 medium-6 large-6 column"
          :style="getBackgroundImage('piscines-img-33361.jpg', 'large')"
        >
        <div class="overlay_side_area"></div>
        </div>
        <div class="small-12 medium-6 large-6 wrapper">
          <div class="wrapper-content">
            <i class="fa fa-window-close" @click="close"></i>
            <div class="heading">
              <div class="aside_wrapper">
                <transition name="slide-fade-left">
                  <aside  class="post-meta">
                    <span>Essaouira, Maroc</span>
                  </aside>
                </transition>
              </div>
              <h4 class="entry-title">
                {{ $t($getFreeContentLanguageKey('Choisissez les dates de votre séjour qui vous font plaisir')) }}
              </h4>
            </div>
            <div class="date_wrapper">
              <BookingFormDatesFullScreen/>
              <div class="link_dates">
                <a @click="close" href="#">{{ $t($getFreeContentLanguageKey('Retour au site')) }}</a> / <nuxt-link :to="localePath('rooms')">{{ $t($getFreeContentLanguageKey('Voir les chambres')) }}</nuxt-link>
              </div>
            </div>


            <div class="bottom_side">
              <div class="row">
                <div class="small-12 medium-12">
                  {{$t($getFreeContentLanguageKey('Informations réservation')) }}
                  <span>+212 658 256 247</span> / <span>reservation@gonatouki.com</span>
                </div>
                <!-- <div class="small-1 medium-1">
                  <a href="https://www.facebook.com/FuelThemes/" class="social facebook" target="_blank">
                    <i class="fab fa-facebook"></i>
                  </a>
                  <a href="index.html%3Fp=537.html#" class="social instagram" target="_blank">
                    <i class="fab fa-instagram"></i>
                  </a>
                </div> -->
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
    </transition>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name:'ContainerSideDatesFullScreen',
  components: {
    BookingFormDatesFullScreen: () => import(
      '~/components/theme-full-screen/components/BookingFormDatesFullScreen'
    )
  },
  data() {
    return {
      params: {
        'color-text': true,
        options: {
          autoHeight: true,
          loop: true,
          speed: 900,
          mousewheel: true,
          effect: "slide"
        }
      }
    }
  },
  computed: {
    ...mapState({
        isContainerSideDatesOpen: state => state.organizations.config.isContainerSideDatesOpen,
    })
  },
  methods: {
    close() {
      this.$store.commit(
        'organizations/setConfigIsContainerSideDatesOpen'
        , true
      )

    },
    getBackgroundImage: function (filename, format) {
      if(null !== filename) {

        return this.$getUrlBackgroundImage(filename, { "format": format })
      }

      return null
    }
  }
}
</script>

<style lang="scss" scoped>

.container-side-wrapper h4 {
	color: #fff;
	text-transform: uppercase;
	width: 75%;
	margin: 0 auto;
	margin-top: 10px;
}

.container-side-wrapper .heading {
  text-align: center;
  height: 20%;
}

.container-side-wrapper .date_wrapper {
  // padding: 90px 0;
  height: 75%;
}

.container-side-wrapper .wrapper-content {
  // display: flex;
  // align-items: center;
  height: 100%;
  width: 100%;
}

.container-side-wrapper {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 40;
  background-color: var(--color-primary-rgba);
  color: #fff;
}

.container-side-wrapper .fa-window-close {
  position: absolute;
  top: 50px;
  right: 40px;
  font-size: 1.5rem;
  cursor: pointer;
  z-index: 51;
}

.container-side-wrapper .bottom_side {
  width: 100%;
  height: 5%;
  font-size: 0.7rem;
  font-style: italic;
  text-align: center;
}

.container-side-wrapper .bottom_side .row {
  padding: 0 40px;
}

.container-side-wrapper.p-right {
  left: inherit;
  right: 25px;
}

.container-side-wrapper a {
  color: #fff;
}

.container-side-wrapper.bg-no-transparent {
  background-color: var(--color-primary);
}

.container-side-wrapper .theme--light.v-card
, .container-side-wrapper .theme--light.v-tabs > .v-tabs-bar
, .container-side-wrapper .theme--light.v-tabs-items {
  background-color: transparent;
}


.container-side-wrapper .theme--light.v-card
{
  height: 100%;
  overflow: hidden;
  color: #fff;
}

.container-side-wrapper .v-tabs .v-tabs-bar a {
  color: var(--color-secondary) !important;
  width: 50%;
  text-align: center;
}

.container-side-wrapper .theme--light.v-card > .theme--light.v-tabs p
, .container-side-wrapper .theme--light.v-card > .theme--light.v-tabs h2
, .container-side-wrapper .theme--light.v-card > .theme--light.v-tabs h4
, .container-side-wrapper .theme--light.v-card > .theme--light.v-tabs ul li {
  color: #fff;
}

.container-side-wrapper .theme--light.v-tabs > .v-tabs-bar .v-tab:not(.v-tab--active)
{
  color: #fff!important;
  background-color: transparent;
}

.container-side-wrapper .v-sheet.v-card:not(.v-sheet--outlined) {
  box-shadow: inherit;
}

.container-side-wrapper .blog-post-container
{
  background: transparent !important;
}

.container-side-wrapper .row, .container-side-wrapper .row > div {
  height: 100%;
}

.container-side-wrapper .link_dates {
  text-align: center;
  /*color: var(--color-secondary);*/
  margin-top: 3.5em;
}

.container-side-wrapper .link_dates a {
  color: var(--color-secondary);
}

.container-side-wrapper .close_dates {
  position: absolute;
  top:55px;
  left: 40px;
}

.container-side-wrapper .column {
  position: relative;
}

.container-side-wrapper .wrapper {
  padding: 50px 40px 0px 40px;
  position: relative;
}

</style>
