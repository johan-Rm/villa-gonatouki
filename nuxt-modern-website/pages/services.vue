<template>
    <ThePage :params="params" :data="page"></ThePage>
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
                template: 'service'
            }
        }
    },
    computed: {
        ...mapState({
           page (state) {

               var list = []
               state.hotels.services.forEach(service => {
                   service.list.forEach(element => {
                       list.push({
                         slug: element.slug,
                           name: element.name,
                           category: element.category
                       })
                   })
               })
                return {
                    "webPage": state.pages.item,
                    "list": list
                }
            }
        })
    },
    async fetch({ app, params, store, payload }) {
        if (payload) {
          store.commit('pages/setItem', payload.webPage)
        } else {
          store.dispatch('pages/getOneBy', { slug: 'les-services' })
        }
        store.commit('pages/setHeadline', "les-services")
        if(store.state.hotels.services.length > 0) {
            var slides = []
            store.state.hotels.services.forEach(function(service, i) {
                let item = {
                  "image": {
                    filename: 'exterieur-jardin-img-38561.jpg'
                  },
                  "title": service.headline,
                  "list": service.list,
                  "videos": []
                }
                slides.push(item)
            })
            if(app.$device.isMobile) {
                await store.commit('components/slider_swiper_pages/setList', slides)
            } else {
                await store.commit('components/slider_swiper_pages/setListByTwin', slides)
            }

            // await store.commit('components/slider_swiper_pages/setListByTwin', slides)
            // var data = { "list": slides }
            // await store.commit('components/slider_swiper_pages/addList', data)
        }
    }
}
</script>

<style>
</style>
