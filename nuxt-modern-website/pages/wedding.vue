<template>
    <ThePage :params="params" :data="page"></ThePage>
</template>

<script>
import { mapState } from 'vuex'
import TheSliderSwiper from '~/components/TheSliderSwiper'
import { camelCase, ucFirst } from '~/plugins/filters.js'
import ThePage from '~/components/ThePage'
export default {
    layout: ({ store }) => camelCase(store.state.organizations.config.theme),
    components : {
        TheSliderSwiper,
        ThePage
    },
    data() {

        return {
            params: {
                template: 'slider-default',
                list: {
                    "limit": 8
                }
            }
        }
    },
    computed: {
        ...mapState({
            data (state) {
                return {
                    webPage: state.pages.item,
                    activities: state.hotels.activities,
                    activitiesWithoutImages: state.hotels.activitiesWithoutImages,
                }
            },
            page (state) {

                return {
                    webPage: state.pages.item,
                    extraSlide: state.hotels.activitiesWithoutImages,
                }
            }
        })
    },
	async fetch({ app, params, store, payload }) {
        if (payload) {
            store.commit('pages/setItem', payload.webPage)
        } else {
            store.dispatch('pages/getOneBy', { slug: 'les-activites' })
        }
        await store.commit('pages/setHeadline', "MAriage")
        await store.dispatch('hotels/getListActivities')
        await store.dispatch('hotels/getListActivitiesWithoutImages')

        if(store.state.hotels.activities.length > 0) {
            var slides = []
            store.state.hotels.activities.forEach(function(activity, i) {
                let item = {
                  "image": activity.primaryImage,
                  "category": activity.category.name,
                  "title": activity.name,
                  "description": activity.description,
                  "videos": []
                }
                slides.push(item)
            })
            if(app.$device.isMobile) {
                await store.commit('components/slider_swiper_pages/setList', slides)
            } else {
                await store.commit('components/slider_swiper_pages/setListByTwin', slides)
            }
        }
    }
}
</script>

<style>
</style>
