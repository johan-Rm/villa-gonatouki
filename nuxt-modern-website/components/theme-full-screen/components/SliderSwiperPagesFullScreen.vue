<template>
    <div class="slider-swiper-pages-full-screen">
        <swiper
        :ref="swiperComponentPages"
        :options="params.options"
        @slide-change-transition-start="onSwiperSlideChangeTransitionStart"
        @slide-change-transition-end="onSwiperSlideChangeTransitionEnd"
        class="_swiper-container-vertical"
        >
            <swiper-slide
            v-for="(value, index) in data"
            :key="index"
            :id="definedSlideId(index)"
            class="twin"
            >
                <template v-if="value.hasOwnProperty('webPage') && value.webPage.slug == 'la-villa-gonatouki'">
                    <about-page :params="getParams(index)" :data="value.webPage" />
                </template>
                <template v-else-if="value.hasOwnProperty('webPage')">
                    <card-page :params="getParams(index)" :data="value" />
                </template>
                <template v-else-if="value.hasOwnProperty('article')">
                    <card-default :params="getParams(index)" :data="value.article" />
                </template>
                <template v-else-if="value.hasOwnProperty('activity')">
                    <card-activity :params="getParams(index)" :data="value.activity" />
                </template>
            </swiper-slide>
        </swiper>
    </div>
</template>
<script>
import { EventBus } from '~/plugins/event-bus.js'
export default {
  name: 'SliderSwiperPagesFullScreen',
  props: {
    params: {
      type: Object
    },
    data: {
      type: Array
    }
  },
  components: {
    AboutPage: () => import('~/components/theme-full-screen/components/AboutFullScreen'),
    CardPage: () => import('~/components/theme-full-screen/components/CardPageFullScreen'),
    CardDefault: () => import('~/components/theme-full-screen/components/CardDefaultFullScreen'),
    CardActivity: () => import('~/components/theme-full-screen/components/CardActivityFullScreen'),
    CardTwin: () => import('~/components/theme-full-screen/components/CardTwinFullScreen')
  },
  mounted() {

    EventBus.$on('next-slide', data => {
      if(this.swiperComponentPages === data.origin) {
        this.swiper.slideTo(data.slide, 900, false)
      }
   })
  },
  computed: {
    swiperComponentPages() {

      return 'swiper-component-pages-' + this.$route.name
    },
    swiper() {
      const index = this.swiperComponentPages

      return this.$refs[index].$swiper
    }
  },
  methods: {
    isArray(value) {

      return Array.isArray(value)
    },
    onSwiperSlideChangeTransitionStart(swiper) {
      let key = swiper.realIndex
      this.$store.commit('organizations/setTransitionShowSlide', false)
    },
    onSwiperSlideChangeTransitionEnd(swiper) {
      this.$store.commit('organizations/setTransitionShowSlide', true)
      EventBus.$emit('slide-change', true)
    },
    definedSlideContainerId: function (index) {

      return 'slide-container-' + index
    },
    definedSlideId: function (index) {

      return 'slide-image-' + index
    },
    defineSlideByIndex: function (index) {

      return index % 2 == (0 || 1) ? true : false
    },
    getParams(index) {
      let direction = 'left'
      if(false === this.defineSlideByIndex(index)) {
        direction = 'right'
      }

      let id = this.definedSlideId(index)
      let list = {}
      if(this.params.hasOwnProperty('list')) {
        list = this.params.list
      }

      return {
        "id": id,
        "direction": direction,
        "index": index,
        "full": true,
        "list": list,
        "column": this.params.column,
        "origin": this.swiperComponentPages
      }
    }
  }
}
</script>

<style lang="scss">

</style>
