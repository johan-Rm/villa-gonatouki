// https://github.com/surmon-china/vue-awesome-swiper
import Vue from 'vue'
import 'swiper/swiper-bundle.css'
import { Swiper as SwiperClass, Navigation, Pagination, Mousewheel, Autoplay, EffectFade, EffectCoverflow, EffectFlip, EffectCube } from 'swiper/swiper.esm'
import getAwesomeSwiper from 'vue-awesome-swiper/dist/exporter'

SwiperClass.use([Navigation, Pagination, Mousewheel, Autoplay, EffectFade, EffectCoverflow, EffectFlip, EffectCube])
Vue.use(getAwesomeSwiper(SwiperClass))
const { Swiper, SwiperSlide } = getAwesomeSwiper(SwiperClass)


Vue.component('swiper', Swiper)
Vue.component('swiper-slide', SwiperSlide)