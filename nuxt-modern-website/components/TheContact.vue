<template>
    <component :is="template" :params="mergedParams"></component>
</template>
<script>
import { camelCase, ucFirst } from '~/plugins/filters.js'
export default {
    name: 'TheContact',
    props: {
        params: {
            type: Object,
            default: () => ({
                bg_primary: 'bg-primary'
            })
        },
        data: {
            type: Object
        }
    },
    computed: {
        template () {
            let themeName = this.$store.state.organizations.config.theme
            let templateName = ucFirst(camelCase(this.$store.state.organizations.config.theme))
            if (this.params.hasOwnProperty('template')) {
                // templateName = 'Light'
            }

            return () => import(`~/components/theme-${themeName}/components/PageContact${templateName}`)
        },
         mergedParams() {
            let params = {
                
            }

            return {...this.params, ...params}
        }
    }
}
</script>
