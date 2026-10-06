<template>
   <header id="header" class="header-full-screen _no-bar elevation-3">
    <h1 style="display:none;">{{$t($getContentLanguageKey(webPage.slug, 'headline', 'WebPage')) }}</h1>
    <div class="logo-holder">
      <div class="mobile-toggle" @click="close">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          version="1.1"
          id="menu-icon" x="0" y="0" width="19.2" height="12" viewBox="0 0 19.2 12" enable-background="new 0 0 19.188 12.031" xml:space="preserve"
        >
          <path
            class="thb-top-line"
            fill="none"
            stroke="#161616"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-miterlimit="10"
            d="M1.1 1h12c2.8 0 5.1 2.2 5.1 5 0 2.8-2.3 5-5.1 5l-12-10"
          />
          <path
            class="thb-mid-line"
            fill="none"
            stroke="#161616"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-miterlimit="10"
            d="M1.1 6h12"
          />
          <path
            class="thb-bottom-line"
            fill="none"
            stroke="#161616"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-miterlimit="10"
            d="M1.1 11h12c2.8 0 5.1-2.2 5.1-5 0-2.8-2.3-5-5.1-5l-12 10"
            />
        </svg>
      </div>
      <a href="#" @click="close" class="logo mobile-toggle">
        <h4><strong>VILLA</strong> GONATOUKI</h4>
      </a>
    </div>
    <div class="right-holder" @click="openSideContainer()">
      <TheBookingButton/>
    </div>
    <div class="right-holder">
      <Breadcrumb :params="params" :data="params"/>
    </div>
  </header>
</template>

<script>
import Breadcrumb from '~/components/theme-full-screen/components/BreadcrumbFullScreen'
import TheBookingButton from '~/components/TheBookingButton'
import { mapState } from 'vuex'
export default {
    name: 'HeaderFullScreen',
    components : {
      Breadcrumb,
      TheBookingButton
    },
    props: {
      params: {
          type: Object
      }
    },
    computed: {
      ...mapState({
        webPage: state => state.pages.item,
        isContainerSideDatesOpen: state => state.organizations.config.isContainerSideDatesOpen,
        // render: state => state.organizations.render
      })
    },

    methods: {
      close() {
        this.$store.commit(
          'organizations/setConfigIsContainerSideDatesOpen'
          , true
        )
        this.$store.commit(
          'organizations/setConfigIsContainerSideBookingOpen'
          , true
        )
      },
      openSideContainer() {
        this.$store.commit(
          'organizations/setConfigIsContainerSideBookingOpen'
          , true
        )
        this.$store.commit(
          'organizations/setConfigIsContainerSideDatesOpen'
          , this.isContainerSideDatesOpen
        )
      }
    }
}
</script>
<style scoped>

#header .logo.mobile-toggle {
  display: inherit;
  width: inherit;
  height: inherit;
}

#header {
  border-bottom: 1px solid var(--color-secondary);
  background: var(--color-primary);
	padding: 0px 30px;
  z-index: 600;
  min-height: 35px;
}

#header.no-bar {
  background: none;
}

#header.bg-no-transparent {
  background: var(--color-primary);
}

#header.no-bar .logo-holder {
  color: #fff;
  background-color: var(--color-primary);
  padding: 5px 5px;
  border-radius: 7px;
  /*display:inline-block;*/
  text-align:center;
}

#header .mobile-toggle path
{
  stroke:#fff
}

#header h4 {
  color: #fff;
  margin: 0;
  font-weight: 700;
  /* font-size: 0.7rem; */
}

#header .mobile-toggle {
	margin-right: 5px;
	/* font-size: 0.7rem; */
}

#header h4 strong {
  color: var(--color-secondary);
}

#header .logo-holder, #header .right-holder {
	display: flex;
	align-items: center;
	font-size: 0.7rem;
	text-align: center;
}

@media screen and (max-width: 40.0625em){
    #header {
        padding: 0px 10px;
    }

    #header h4 {
      font-size: 0.7rem;
    }

    #header .mobile-toggle {
    	font-size: 0.7rem;
        margin-right: 0px;
    }

    #header .right-holder {
       font-size: 0.6rem;
       font-weight: 700;
   }
}
</style>
