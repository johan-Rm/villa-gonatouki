<template>
    <component :is="template" :params="params"></component>
</template>
<script>
import { camelCase, ucFirst } from '~/plugins/filters.js'
export default {
    name: 'TheFooter',
    props: {
        params: {
            type: Object,
            default: () => ({
                bg_primary: 'bg-primary'
            })
        }
    },
    computed: {
        template () {
            let name = ucFirst(camelCase(this.$store.state.organizations.config.theme))
            if (this.params.hasOwnProperty('template')) {
                name = 'Light'
            }

            return () => import(`~/components/theme-full-screen/components/Footer${name}`)
        }
    }
}
</script>
