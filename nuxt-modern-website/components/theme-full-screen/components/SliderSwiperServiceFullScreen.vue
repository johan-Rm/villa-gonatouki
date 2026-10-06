<template>
    <div class="slider-swiper-service-full-screen">
        <swiper ref="swiperComponentService" :options="params.options" class="swiper-service" :class="classPosition" @slide-change-transition-start="onSwiperSlideChangeTransitionStart" @slide-change-transition-end="onSwiperSlideChangeTransitionEnd">
            <swiper-slide id="slide-image-0">
                <card-simple :params="getParams(0)" :data="page" />
            </swiper-slide>
            <swiper-slide v-for="(slide, index) in slides" :key="index+1" class="_swiper-slide">
                <template v-if="slide.hasOwnProperty('webPage')">
                    <card-page :params="getParams(index)" :data="slide" />
                </template>
                <template v-else-if="!isArray(slide)">
                    <card-simple-service :params="getParams(index)" :data="slide" />
                </template>
                <template v-else>
                    <card-twin :params="getParams(index)" :data="slide" />
                </template>
            </swiper-slide>
        </swiper>
    </div>
</template>

<script>
import { EventBus } from '~/plugins/event-bus.js'
export default {
  name: 'SliderSwiperServiceFullScreen',
  props: {
      params: {
          type: Object
      },
      slides: {
          type: Array
      },
      page: {
          type: Object
      }
  },
  components: {
      CardSimpleService: () => import('~/components/theme-full-screen/components/CardSimpleServiceFullScreen'),
      CardSimple: () => import('~/components/theme-full-screen/components/CardSimpleFullScreen'),
      CardPage: () => import('~/components/theme-full-screen/components/CardPageFullScreen'),
      CardTwin: () => import('~/components/theme-full-screen/components/CardTwinFullScreen')
  },
  computed: {
      classPosition() {

        return "center-slide"
      },
      classColorText() {
        if (this.params.hasOwnProperty('color-text')) {

            return 'text-white'
        }

        return null
      },
      swiper() {

        return this.$refs.swiperComponentService.$swiper
      }
  },
  mounted() {
    this.$store.commit('organizations/setTransitionShowColumnService', 0)
    EventBus.$on('next-slide', data => {
      if("slider-swiper-service" == data.origin) {
        if(!this.swiper.hasOwnProperty('destroyed')) {
          this.swiper.slideTo(data.slide, 900, false)
        }
      }
    })
  },
  methods: {
    isArray(value) {
      return Array.isArray(value)
    },
    getParams(index) {

      return {
        "id": index,
        "index": index,
        "origin": "slider-swiper-service"
      }
    },
    onSwiperSlideChangeTransitionStart(swiper) {
        let key = swiper.realIndex
        this.$store.commit('organizations/setTransitionShowColumnService', key)
        this.$store.commit('organizations/setTransitionShowSlide', false)
    },
    onSwiperSlideChangeTransitionEnd(swiper) {
       this.$store.commit('organizations/setTransitionShowSlide', true)
    },
    getImagePath: function (image) {
        if(null == image) {

            return null
        }

        return process.env.WEB_HOST + process.env.PATH_DEFAULT_MEDIA + image.filename
    }
  }
}
</script>

<style lang="scss">

.slider-swiper-service-full-screen {
  position: relative;
  height: 100%;
}

.slider-swiper-service-full-screen .swiper-wrapper
{
  // padding: 45px 0;
}

.slider-swiper-service-full-screen ul {
    padding: 10px 20px;
    margin: 0
}

.slider-swiper-service-full-screen ul li {
    text-align: left;
    position: relative;
    list-style-type: none;
}

.slider-swiper-service-full-screen ul li a {
    color: var(--color-primary) !important;
}

.slider-swiper-service-full-screen .text-white h3 {
    color: #fff !important;

}
.slider-swiper-service-full-screen h3 {
  color: var(--color-primary) !important;
  text-align: left;
  padding: 10px 20px;
  text-transform: uppercase;
  margin: 0;
  font-size: 2.2rem;
  padding-top: 0;
}



</style>
