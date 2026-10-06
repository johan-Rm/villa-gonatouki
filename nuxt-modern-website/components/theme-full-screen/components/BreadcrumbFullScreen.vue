<template>
 <!-- <ul id="menu-header" class="header-menu"> -->
<!--  <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-489">
  <a href="blog/index.html">{{ $t('Accueil') }}</a>
</li> -->
<!-- <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-487"> -->
  <div class="header-menu aside_wrapper">
      <transition name="slide-fade-right">
        <a v-if="show" href="#">{{ getHeaderName() }}</a>
      </transition>
  </div>
<!-- </li>
</ul> -->
</template>
<script>
import { mapState } from 'vuex'
export default {
    name: 'BreadcrumbFullScreen',
    props: {
        params: {
            type: Object
        },
        data: {
            type: Object
        }
    },
    computed: {
      ...mapState({
        webPage: state => state.pages.item,
        show: state => state.organizations.config.transition.show.breadcrumb,
        currentDayTypesName: state => state.organizations.config.currentDayTypesName
      })
    },
    mounted() {
      // console.log('mounted BreadcrumbFullScreen')
      this.$store.commit('organizations/setTransitionShowBreadcrumb', true)
    },
    updated() {
      // console.log('updated BreadcrumbFullScreen')
      this.$store.commit('organizations/setTransitionShowBreadcrumb', true)
  },
  methods: {
      getHeaderName() {
          if(this.$route.path === "/") {
              if(this.currentDayTypesName.slug) {

                return this.$i18n.t(this.$getContentLanguageKey(this.currentDayTypesName.slug, 'name', 'HotelTypicalDay'))
              }

              return ""
          }

          return this.$i18n.t(this.$getContentLanguageKey(this.webPage.slug, 'headline', 'WebPage'))
      }
  }
}
</script>
<style lang="scss" scoped>
.header-menu a {
  color: #fff;
  font-size: 24px;
  text-transform: uppercase;
  font-weight: 700;
  font-family: "Rajdhani", sans-serif;
  display: inline-block;
}
.header-menu a:hover {
    color: var(--color-secondary);
}

@media screen and (max-width: 40.0625em){
    .header-menu a {
      font-size: 0.7rem;
    }
}
</style>
