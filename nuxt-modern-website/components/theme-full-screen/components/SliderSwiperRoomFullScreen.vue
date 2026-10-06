<template>
    <div class="slider-swiper-room-full-screen">
        <swiper
        :ref="swiperComponentId"
        :options="swiperOptions"
        @slide-change-transition-start="onSwiperSlideChangeTransitionStart"
        class="slider-rooms-full-screen _swiper-gallery bottom-slide">
            <swiper-slide
            v-for="(value, index) in data.gallery.imageGalleries"
            :key="index"
            class="_swiper-slide page-padding"
            :style="getBackgroundImage(value.image.filename, 'large')"
            data-color="logo-light"
            :id="definedSlideId(index)"
            >
            </swiper-slide>
        </swiper>
    </div>
</template>

<script>
export default {
    name: 'SliderSwiperRoomFullScreen',
    props: {
        params: {
            type: Object
        },
        data: {
            type: Object
        },
        slug: {
            type: String
        }
    },
    data() {

      // console.log('this.params.options.swiperOptions')
      // console.log(this.params.options.swiperOptions)

      return {
        swiperOptions: this.params.options.swiperOptions
      }
    },
    computed: {
      // swiper() {

      //   return this.$refs.swiperComponent.$swiper
      // },
      totalImages() {

        return this.data.gallery.imageGalleries.length
      },
      swiperComponentId() {

        return 'swiperComponent-' + this.slug
      }
    },
    methods: {
      currentImage(index) {

        return index + 1
      },
      getBackgroundImage: function (filename, format) {
          if(null !== filename) {
             if(this.$device.isMobile) {
                 format = 'vertical'
             }

             return this.$getUrlBackgroundImage(filename, { "format": format })
          }

          return null
      },
      getImageSrcSet: function (filename) {
        if(null == filename) {

            return null
        }
      },
      onSwiperSlideChangeTransitionStart(swiper) {
        // console.log('rooms onSwiperSlideChangeTransitionStart')
        let key = swiper.realIndex
      },
      definedSlideId: function (index) {

        return 'slide-image-' + index
      }
    }
}
</script>
<style lang="scss" scoped>

.slider-rooms-full-screen .photo-caption.bg-transparent {
  background-color: var(--color-primary-rgba-80);
  padding: 10px 20px;
  border-radius: 7px;
}

.slider-rooms-full-screen .photo-caption  {
  /*display: none;*/
  position: absolute;
  bottom: 25px;
  left: 25px;
  box-shadow:0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
  // background: rgba(0,0,0,0.5);
  background: var(--color-primary-rgba-50);
  // background: rgba(0,0,0, 0.5);
  padding: 10px 20px;
  border-radius: 7px;
}

@media screen and (max-width: 40.625em){
    .slider-swiper-room-full-screen {
        height: 100% !important;
    }
    .slider-swiper-room-full-screen .swiper-container {
        height: inherit !important;
    }




}
</style>
