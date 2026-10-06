<template>
  <TheContact></TheContact>
</template>
<script>
import { camelCase, ucFirst } from '~/plugins/filters.js'
import TheContact from '~/components/TheContact'
export default {
	layout: ({ store }) => camelCase(store.state.organizations.config.theme),
  components : {
    TheContact
  },
	async fetch({ app, params, store, payload }) {
    if (payload) {
      store.commit('pages/setItem', payload.webPage)
    } else {
      store.dispatch('pages/getOneBy', { slug: 'contact' })
    }
  }
}
</script>

<style>
</style>
