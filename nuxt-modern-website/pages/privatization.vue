<template>
  <ThePage :params="params" :data="data"></ThePage>
</template>

<script>
import { mapState } from 'vuex'
import { camelCase, ucFirst } from '~/plugins/filters.js'
import ThePage from '~/components/ThePage'
export default {
	layout: ({ store }) => camelCase(store.state.organizations.config.theme),
  components : {
    ThePage
  },
  data() {

    return {
      params: {
        template: 'slider-default',
        list: {
          "limit": 5
        }
      }
    }
  },
  computed: {
    ...mapState({
      data (state) {

        return {
          webPage: state.pages.item,
        }
      }
    })
  },
  async fetch({ app, params, store, payload }) {
      if (payload) {
      store.commit('pages/setItem', payload.webPage)
    } else {
      store.dispatch('pages/getOneBy', { slug: 'privatization' })
    }
	await store.commit('pages/setHeadline', "Privatisation")
    await store.dispatch('articles/getListBy', { slug: 'privatization' })
    await store.dispatch('hotels/getPrivatizationOffers')

    if(store.state.articles.list.length > 0) {
      var slides = []
      store.state.articles.list.forEach(function(article, i) {
        let item = {
          "image": article.primaryImage,
          "category": article.category,
          "title": article.headline,
          "description": article.articleBody,
          "slug": article.slug,
          "videos": []
        }
        slides.push(item)
      })
      await store.commit('components/slider_swiper_pages/setListArticle', slides)
      // await store.commit('components/slider_swiper_pages/setListByTwin', slides)
      let list = store.state.hotels.privatization_offers[0].amenities
      var data = {
          "webPage": store.state.pages.item,
          "image": store.state.pages.item.primaryImage.filename,
          "privatization_offers": store.state.hotels.privatization_offers,
          "list": list.map(v => ({...v, isActive: false}))
      }
      await store.commit('components/slider_swiper_pages/addList', data)
    }
  }
}
</script>

<style>
</style>
