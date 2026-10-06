<template>
<div>
  <div>
    <div v-for="i in 5" :key="i" :style="{ backgroundImage: `url(${getBackgroundUrl()})` }" data-color="logo-light" id="slide-image-650">
    <!-- <div v-for="i in 5" :key="i" class="swiper-slide page-padding swiper-lazy" :data-background="getBackgroundUrl()" data-color="logo-light" id="slide-image-650"> -->
        <div class="photo-caption">
          <em>1/7</em> Never give up
        </div>
    </div>
  </div>
  <div class=""></div>
</div>
</template>
<script>
export default {
    name: 'SliderDefault',
    props: {
        params: {
            type: Object
        },
        data: {
            type: Object
        }
    },
    data() {
        return {
          options: {
            slidesPerView: 1,
            loop: true,
            // slidesPerView: 'auto',
            // centeredSlides: true,
            mousewheelControl: true,
            // spaceBetween: 30,
            pagination: {
              el: '.swiper-pagination',
              // dynamicBullets: true,
              clickable: true,
              // type: 'progressbar',
              renderBullet(index, className) {
                return `<a href="#" class="${className} swiper-pagination-bullet-custom">${index + 1}</a>`
              }
            },
            on: {
              slideChange() {
                // console.log('onSlideChangeEnd', this);
              },
              tap() {
                // console.log('onTap', this);
              }
            }
          }
        }
    },
    mounted() {
      // console.log('Current Swiper instance object', this.mySwiper)
      // this.mySwiper.slideTo(3, 900, false)
    },
    methods: {
        getBackgroundUrl: function () {

          // return require(`~/static/images/activities/IMG_GOLF.jpeg`)
          return  process.env.WEB_HOST + process.env.PATH_DEFAULT_MEDIA + 'IMG_GOLF.jpeg'
        }
    }
    // computed: {
    //     template () {
    //         const name = 'Default'
            
    //         return () => import(`~/components/theme-modern-immobilier/components/SliderContent${name}`)
    //     }
    // },
    // methods: {
    //     getBackgroundImage: function (imageFilename) {
    //         if(null !== imageFilename) {
    //             let url = process.env.CDN_URL + process.env.FORMAT_MEDIA_PATH + 'large_nostamp' + process.env.DEFAULT_MEDIA_PATH + imageFilename

    //             return 'background-image:url(' + url + ')'
    //         }

    //         return null
    //     },
    //     getMobileBackgroundImage: function (imageFilename) {
    //         if(null !== imageFilename) {
    //             let url = process.env.CDN_URL + process.env.FORMAT_MEDIA_PATH + 'grid_nostamp' + process.env.DEFAULT_MEDIA_PATH + imageFilename

    //             return 'background-image:url(' + url + ')'
    //         }

    //         return null
    //     }
    // }
}
</script>
<style lang="scss" scoped>

</style>
