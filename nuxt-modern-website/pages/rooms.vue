<template>
  <ThePage :params="params" :data="data"></ThePage>
</template>

<script>
import { mapState } from 'vuex'
import ThePage from '~/components/ThePage'
import { camelCase, ucFirst } from '~/plugins/filters.js'
export default {
  layout: ({ store }) => camelCase(store.state.organizations.config.theme),
  components : {
    ThePage
  },
  data() {
    return {
      params: {
        template: 'rooms'
      }
    }
  },
  computed: {
    ...mapState({
      data (state) {
        return {
          webPage: state.pages.item,
          rooms: state.hotels.rooms,
          amenities: state.hotels.amenities,
          services: state.hotels.services,
          "image": 'charme-deco-img-38381-vertical.jpg',
        }
      }
    })
  },
  async fetch({ app, params, store, payload }) {
    if (payload) {
      store.commit('pages/setItem', payload.webPage)
    } else {
      store.dispatch('pages/getOneBy', { slug: 'les-chambres' })
    }
    // store.commit('pages/setHeadline', "Les chambres")

  }
}
</script>
<style lang="scss" scoped>
</style>
