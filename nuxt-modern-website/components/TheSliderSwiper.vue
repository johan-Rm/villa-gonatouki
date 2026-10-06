<template>
    <component v-if="render" :is="template" :params="params" :data="data"></component>
    <div v-else class="loading_render">
        <div class="message">
            <span>{{ $t($getFreeContentLanguageKey('Nous preparons votre chambre')) }} ...</span>
        </div>
    </div>
</template>
<script>
import { camelCase, ucFirst } from '~/plugins/filters.js'
import { mapState } from 'vuex'
export default {
    name: 'TheSlider',
    props: {
         params: {
            type: Object
        },
        data: {
            type: Array
        }
    },
    computed: {
        ...mapState({
          render: state => state.organizations.render
        }),
        template () {
            let params = this.params.slider
            let themeName = this.$store.state.organizations.config.theme
            let templateName = ucFirst(camelCase(this.$store.state.organizations.config.theme))

            if (params.hasOwnProperty('template')) {
                templateName = ucFirst(camelCase(params.template)) + ucFirst(camelCase(this.$store.state.organizations.config.theme))
            }

            return () => import(`~/components/theme-${themeName}/components/SliderSwiper${templateName}`)
        }
    },
    mounted() {
        this.$store.commit('organizations/setRender', true)
    }
}
</script>
