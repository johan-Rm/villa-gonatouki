<template>
    <LazyThePage :params="params" :data="data"></LazyThePage>
</template>

<script>
import { mapState } from 'vuex'
import { camelCase, ucFirst } from '~/plugins/filters.js'

export default {
	layout: ({ store }) => camelCase(store.state.organizations.config.theme),
  components: {
    ThePage: () => import('~/components/ThePage')
  },
  data() {
    return {
      params: {
          template: 'slider-default',
      }
    }
  },
  computed: {
    ...mapState({
      page: state => state.pages.item,
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
        store.dispatch('pages/getOneBy', { slug: 'la-villa-gonatouki' })
      }
      await store.dispatch('articles/getListBy', { slug: 'la-villa' })
      await store.commit('pages/setHeadline', "la-villa")
      // store.dispatch('pages/getOneByFilter', { slug: 'la-villa-gonatouki' })

      if(store.state.articles.list.length > 0) {
        var slides = []
        store.state.articles.list.forEach(function(article, i) {
          let item = {
            "image": article.primaryImage,
            "category": article.category,
            "title": article.headline,
            "description": article.articleBody,
            "pushForward": article.pushForward,
            "slug": article.slug,
            "videos": article.videos
          }
          slides.push(item)
        })
        await store.commit('components/slider_swiper_pages/setListArticle', slides)
        // await store.commit('components/slider_swiper_pages/setListByTwin', slides)

        var data = {
            "webPage": store.state.pages.item,
            "image": 'exterieur-jardin-img-38561.jpg',
        }
        await store.commit('components/slider_swiper_pages/addList', data)
      }

	}
}
</script>

<style>
</style>
