<template>
    <component :is="template" :params="params"></component>
</template>
<script>
import { camelCase, ucFirst } from '~/plugins/filters.js'
export default {
    name: 'TheContainerSide',
    props: {
        params: {
            type: Object,
            default: () => ({
                bg_primary: 'bg-primary',
                template: 'dates'
            })
        }
    },
    computed: {
        template () {
            let themeName = this.$store.state.organizations.config.theme
            let templateName = ucFirst(camelCase(this.$store.state.organizations.config.theme))
            if (this.params.hasOwnProperty('template')) {
                templateName = ucFirst(camelCase(this.params.template)) + ucFirst(camelCase(this.$store.state.organizations.config.theme))
            }

            return () => import(`~/components/theme-${themeName}/components/ContainerSide${templateName}`)
        }
    }
}
</script>