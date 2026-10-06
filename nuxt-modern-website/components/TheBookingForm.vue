<template>
    <component :is="template" :params="params"></component>
</template>
<script>
import { camelCase, ucFirst } from '~/plugins/filters.js'
export default {
    name: 'TheBookingForm',
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
            let themeName = this.$store.state.organizations.config.theme
            let templateName = ucFirst(camelCase(this.$store.state.organizations.config.theme))
            if (this.params.hasOwnProperty('template')) {
                // templateName = 'Light'
            }

            return () => import(`~/components/theme-${themeName}/components/BookingForm${templateName}`)
        }
    }
}
</script>
