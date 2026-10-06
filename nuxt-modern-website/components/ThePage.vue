<template>
<div>
    <component
        :is="template"
        :params="params"
        :data="data">
    </component>
</div>
</template>
<script>
import { camelCase, ucFirst } from '~/plugins/filters.js'
export default {
    name: 'ThePage', // for the multi full pages without slider
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
                templateName = ucFirst(camelCase(this.params.template)) + ucFirst(camelCase(this.$store.state.organizations.config.theme))
            }

            // console.log('ThePage data ')
            // console.log(this.data)
            // console.log('ThePage params ')
            // console.log(this.params)
            // console.log(themeName)
            // console.log(templateName)

            return () => import(`~/components/theme-${themeName}/components/Page${templateName}`)
        }
    }
}
</script>
