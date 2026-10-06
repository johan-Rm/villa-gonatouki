<template>
 <ul id="menu-header" class="">
<!--  <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-489">
  <a href="blog/index.html">{{ $t('Accueil') }}</a>
</li> -->
<li class="">
  <a href="index.html%3Fp=399.html">{{$t($store.state.pages.item.headline) }}</a>
</li>
</ul>
</template>
<script>
export default {
    name: 'BreadcrumbDefault',
    props: {
        params: {
            type: Object
        },
        data: {
            type: Object
        }
    },
    data() {
        return {
        }
    }
}
</script>
<style lang="scss" scoped>
</style>
