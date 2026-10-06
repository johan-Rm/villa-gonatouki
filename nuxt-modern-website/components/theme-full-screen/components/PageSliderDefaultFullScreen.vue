<template>
    <div class="pages-default-full-screen">
      <SliderSwiperPages :params="mergedParams" :data="slides" />
    </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: 'PageSliderDefaultFullScreen',
  components: {
    SliderSwiperPages: () => import('~/components/theme-full-screen/components/SliderSwiperPagesFullScreen')
  },
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
      slides: state => state.components.slider_swiper_pages.list
    }),
    mergedParams() {

      // console.log('slides')
      // console.log(this.$store.state.components.slider_swiper_pages.list)

        let preventClicksPropagation = true
       let allowTouchMove = (this.$device.isMobile)? true: false
       let speed = (this.$device.isMobile)? 100: 900
      let params = {
        options: {
          "direction": 'vertical',
          "loop": false,
          "speed": speed,
          "mousewheel": true,
          "preventClicksPropagation": preventClicksPropagation,
          "allowTouchMove": allowTouchMove
        }
      }

      return {...this.params, ...params}
    }
  }
}
</script>

<style lang="scss" scoped>
.pages-default-full-screen {
  height:100%;

}
</style>
