<template>
<button class="scroltop" aria-label="scroltop">
    <span class="fa fa-angle-up  relative" id="btn-vibrate"></span>
</button>
</template>
<script>
// import { initScrollTop } from '~/plugins/custom_transform_to_export.js'
export default {
    name: "TheScrollTop",
    mounted () {
        // this.$nextTick(function(){ initScrollTop() }.bind(this))
    },
}
</script>
<style>
button.scroltop {
    bottom: 55px;
}
</style>
