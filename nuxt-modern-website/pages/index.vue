<template>
    <LazyTheSliderSwiper :params="params" :data="data"></LazyTheSliderSwiper>
</template>

<script>
import { mapState } from 'vuex'
import { camelCase, ucFirst } from '~/plugins/filters.js'

export default {
  layout: ({ store }) => camelCase(store.state.organizations.config.theme),
  components : {
    TheSliderSwiper: () => import('~/components/TheSliderSwiper')
  },
  data() {
    return {
      params: {
        slider: {
          template: 'hotels-day-types',
          swiperOptions: {
            autoplay: false,
            speed: 2500,
            mousewheel: false,
            effect: "fade",
            pagination: {
              el: '.swiper-pagination',
              renderBullet(index, className) {
                return `<a href="#" class="${className} swiper-pagination-bullet-custom">${index + 1}</a>`
              }
            }
          }
        }
      }
    }
  },
  computed: {
    ...mapState({
      data: state => state.hotels.day_types
    })
  },
  async fetch({ app, params, store, payload }) {
    if (payload) {
      store.commit('pages/setItem', payload.webPage)
    } else {
      store.dispatch('pages/getOneBy', { slug: 'accueil' })
    }
    // store.commit('pages/setHeadline', "une-journee-a-la-villa")
    store.dispatch('hotels/getListDayTypes')
  }
}
</script>

<style>
</style>
