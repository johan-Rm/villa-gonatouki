<template>
<div>
  
  <div>
    <i class="fa fa-hotel"></i>
  </div>
  <div>
    <i class="fa fa-plus"></i>
  </div>
</div>
</template>
<script>
// import SocialMedia from '~/components/theme-modern-immobilier/components/SocialMedia'
// import SearchButton from '~/components/theme-modern-immobilier/components/SearchButton'
export default {
    name:'NavigationSideDefault',
    data () {
        return {}
    }
}
</script>
<style scoped>
.navigation-side-container {
  position:absolute;
  bottom: 25px;
  left: 25px;
  z-index: 39;
}

.navigation-side {
  
  padding: 10px;
  border-radius: 8px;
  margin-left: 5px;
}
</style>