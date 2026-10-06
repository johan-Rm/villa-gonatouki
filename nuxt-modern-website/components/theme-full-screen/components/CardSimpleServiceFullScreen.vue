<template>
    <div class="card-simple-service card-simple-service-full-screen">
        <div v-if="params.direction === 'right' || $device.isMobile"
            class="row align-center no-padding p-right"
            :id="params.id"
        >
            <div class="small-12 medium-8 large-9" :style="getBackgroundImage(data.image.filename, 'large')">
            </div>
            <div class="small-12 medium-4 large-3 wrapper-column wrapper-column-left">
                <div class="card-padding description text-center">
                    <!-- <div class="aside_wrapper">
                        <transition name="slide-fade-left"></transition>
                    </div>
                    <h2 class="entry-title">{{ $t(data.title) }}</h2> -->
                    <div class="title_wrapper">
                        <h4 class="entry-title">{{ $t($getContentLanguageKey(data.title, 'name', 'Tag')) }}</h4>
                    </div>
                    <!-- <br /> -->
                    <!-- <p v-if="data.description">{{ $t(data.description) }}</p> -->
                    <!-- <ul v-if="data.list">
                        <li v-for="(elem, index) in data.list.slice(0,7)" :key="index">
                            <a href="#">{{ elem.name }}</a>
                        </li>
                    </ul> -->
                    <div class="ul-container ">
                        <ul class="" v-if="data.list">
                            <v-tooltip v-for="(elem, index) in data.list.slice(0,7)" :key="index" bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <li v-if="elem.name.length > 40" v-bind="attrs" v-on="on">
                                        <span>{{ $t($getContentLanguageKey(elem.slug, 'name', 'HotelService')) | truncate(40) }}</span>
                                    </li>
                                    <li v-else>
                                        <span>{{ $t($getContentLanguageKey(elem.slug, 'name', 'HotelService')) }}</span>
                                    </li>
                                </template>
                                <span>{{ $t($getContentLanguageKey(elem.slug, 'name', 'HotelService')) }}</span>
                            </v-tooltip>
                        </ul>
                    </div>
                </div>
                <!-- <v-btn v-if="'mariage' == data.slug" class="site-button btn-slide _button-sm" :to="localePath('wedding')">
                    <span>{{ $t('Plus d infos') }}</span>
                </v-btn>
                <v-btn v-else class="site-button btn-slide _button-sm" :to="localePath('contact')">
                    <span>{{ $t('Contacter-nous') }}</span>
                </v-btn> -->
            </div>
        </div>
        <div v-else class="row align-center no-padding p-left" :id="params.id">
            <div class="small-12 medium-4 large-3 wrapper-column wrapper-column-right">
                <div class="card-padding description text-center">
                    <!-- <div class="aside_wrapper">
                        <transition name="slide-fade-left">
                            <aside v-if="show" class="post-meta"><span>{{ $t(data.category) }}</span></aside>
                        </transition>
                    </div>
                    <h2 class="entry-title">{{ $t(data.title) }}</h2> -->
                    <div class="title_wrapper">
                        <transition name="slide-fade-left">
                            <h3 v-if="show" class="entry-title">{{ $t($getContentLanguageKey(data.title, 'name', 'Tag')) | truncate(20) }}</h3>
                        </transition>
                    </div>
                    <!-- <br /> -->
                    <!-- <p v-if="data.description">{{ $t(data.description) }}</p> -->
                    <!-- <ul v-if="data.list">
                        <li v-for="(elem, index) in data.list.slice(0,7)" :key="index">
                            <a href="#">{{ elem.name }}</a>
                        </li>
                    </ul> -->
                    <div class="ul-container ">
                        <ul class="" v-if="data.list">
                            <v-tooltip v-for="(elem, index) in data.list.slice(0,7)" :key="index" bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <li v-if="elem.name.length > 45" v-bind="attrs" v-on="on">
                                        <span>{{ $t($getContentLanguageKey(elem.slug, 'name', 'HotelService')) | truncate(45) }}</span>
                                    </li>
                                    <li v-else>
                                        <span>{{ $t($getContentLanguageKey(elem.slug, 'name', 'HotelService')) }}</span>
                                    </li>
                                </template>
                                <span>{{ $t($getContentLanguageKey(elem.slug, 'name', 'HotelService')) }}</span>
                            </v-tooltip>
                        </ul>
                    </div>
                </div>
                <!-- <v-btn v-if="'mariage' == data.slug" class="site-button btn-slide _button-sm" :to="localePath('wedding')">
                    <span>{{ $t('Plus d infos') }}</span>
                </v-btn>
                <v-btn v-else class="site-button btn-slide _button-sm" :to="localePath('contact')">
                    <span>{{ $t('Contacter-nous') }}</span>
                </v-btn> -->
            </div>
            <div class="small-12 medium-8 large-9" :style="getBackgroundImage(data.image, 'large')">
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: 'CardSimpleServiceFullScreen',
  props: {
    params: {
        type: Object
    },
    data: {
        type: Object
    }
  },
  computed: {
    ...mapState({
      show: state => state.organizations.config.transition.show.slider
    })
  },
  mounted() {
    this.$store.commit('organizations/setTransitionShowSlide', true)
  },
  methods: {
    getBackgroundImage: function (filename, format) {
      if(null !== filename) {

        return this.$getUrlBackgroundImage(filename, { "format": format, "size": 'cover' })
      }

      return null
    }
  }
}
</script>

<style lang="scss" scoped>

.card-simple-service, .card-simple-service .row
{
  height: 100%;
  width: 100%;
}

.card-simple-service .card-padding
{
  padding: 40px 30px 40px 30px;
}

.card-simple-service .wrapper-column
{
  position: relative;
  height: 100%;
  // padding: 30px 0 0 0;
}

.card-simple-service .wrapper-column a.btn-half
{
    position: absolute;
    bottom: 25px;
}

.card-simple-service .wrapper-column-left a.btn-half
{
  left: 30%;
}

.card-simple-service .wrapper-column-right a.btn-half
{
  right: 30%;
}

.card-simple-service .aside_wrapper {
  height: 25px;
}

.card-simple-service .btn-slide {
  position: absolute;
  bottom: 20px;
  right: 25%;
}

.card-simple-service .description p {
  padding: 10px;
}

.card-simple-service h4 {
	text-transform: uppercase;
	text-align: left;
	padding-left: 5px;
	margin-bottom: 5px;
}

.card-simple-service ul li::before {
	content: "✓";
	color: var(--color-secondary);
	font-weight: bold;
	display: inline-block;
	width: 1em;
	margin-left: -1em;
	padding-right: 5px;
}

.card-simple-service-full-screen ul li {
  // border-top: 1px solid var(--color-primary-rgba-30);
  margin-bottom: 0px;
  font-size: 0.9rem;
  text-transform: lowercase;
}

@media screen and (max-width: 40.0625em){
    .card-simple-service-full-screen > .row > .large-3 {
        height: 50vh;
    }
    .card-simple-service-full-screen > .row > .large-9 {
        height: 50vh;
    }
}
</style>
