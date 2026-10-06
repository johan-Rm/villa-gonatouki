<template>
    <!-- <TheSliderSwiper :params="params" :data="data"></TheSliderSwiper> -->
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
                // slider: {
                //     template: 'js-split'
                // },
                list: {
                    "limit": 5
                },
                column: true
            }
        }
    },
    computed: {
        ...mapState({
            // data (state) {
            //     return {
            //         webPage: state.pages.item,
            //         activities: state.hotels.activities,
            //         activitiesWithoutImages: state.hotels.activitiesWithoutImages,
            //     }
            // },
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
        await store.commit('pages/setHeadline', "les-activites")
        await store.dispatch('hotels/getListActivities')
        await store.dispatch('hotels/getListActivitiesWithoutImages')

        if(store.state.hotels.activities.length > 0) {
            var slides = []
            store.state.hotels.activities.forEach(function(activity, i) {
                let item = {
                  "slug": activity.slug,
                  "image": activity.primaryImage,
                  "category": activity.category,
                  "name": activity.name,
                  "description": activity.description,
                  "pushForward": activity.pushForward,
                  "videos": []
                }
                slides.push(item)
            })

            await store.commit('components/slider_swiper_pages/setListActivity', slides)
            // await store.commit('components/slider_swiper_pages/setListByTwin', slides)
            var data = {
                "webPage": store.state.pages.item,
                "image": 'exterieur-jardin-img-38561.jpg',
                "list": store.state.hotels.activitiesWithoutImages
            }
            await store.commit('components/slider_swiper_pages/addList', data)
        }
    }
}
</script>

<style>
</style>
