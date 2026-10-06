<template>
    <component :is="template" :params="params" :data="data"></component>
</template>
<script>
import { camelCase, ucFirst } from '~/plugins/filters.js'
export default {
    name: 'TheBreadcrumb',
    props: {
        params: {
            type: Object
        },
        data: {
            type: Object
        }
    },
    computed: {
        template () {
            let name = 'Default'
            if (this.params.hasOwnProperty('template')) {
                name = 'Light'
            }

            return () => import(`~/components/theme-full-screen/components/Breadcrumb${name}`)
        }
    }
}
</script>
