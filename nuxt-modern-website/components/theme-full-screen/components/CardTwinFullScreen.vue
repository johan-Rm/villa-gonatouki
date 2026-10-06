<template>
    <div class="card-twin card-twin-full-screen" :class="cssClass">
        <div class="wrapper_service">
            <div class="page-padding">
                <div v-for="(value, i) in data" :key="i" class="twin-wrapper-container ">
                    <div v-if="i==0" class="row">
                        <div class="small-6 medium-6 large-6 " :style="getBackgroundImage(value.image.filename, 'grid')">
                        </div>
                        <div class="small-6 medium-6 large-6">
                            <div class="text-center right">
                                <div class="text_center_wrapper">
                                    <!-- <div class="border_wrapper">
                                    </div> -->
                                    <!-- <aside v-if="value.category" class="post-meta">{{ $t(this.$getContentLanguageKey(value.category, 'name', 'Tag')) }}</aside> -->
                                    <div class="title_wrapper">
                                        <!-- <transition name="slide-fade-left"> -->
                                        <!-- <h3 v-if="show" class="entry-title">{{ $t(value.title) | truncate(20) }}</h3> -->
                                            <h4 class="entry-title">{{ $t($getContentLanguageKey(value.title, 'name', 'Tag')) | truncate(35) }}</h4>
                                        <!-- </transition> -->
                                    </div>
                                    <!-- <p v-if="value.description" v-html="$t($getContentLanguageKey(value., 'description', 'Tag'))"></p> -->
                                    <div class="ul-container ">
                                        <ul class="" v-if="value.list">
                                            <v-tooltip v-for="(elem, index) in value.list.slice(0,7)" :key="index" bottom>
                                                <template v-slot:activator="{ on, attrs }">
                                                    <li v-if="elem.name.length > 45" v-bind="attrs" v-on="on">
                                                        <span>{{ $t($getContentLanguageKey(elem.slug, 'name', 'HotelService')) | truncate(45) }}</span>
                                                    </li>
                                                    <li v-else>
                                                        <span>{{ $t($getContentLanguageKey(elem.slug, 'name', 'HotelService')) }}</span>
                                                    </li>
                                                </template>
                                                <span>{{ $t($getContentLanguageKey(elem.slug, 'name', 'HotelService')) }}</span>
                                            </v-tooltip>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="row">
                        <div class="small-6 medium-6 large-6">
                            <div class="text-center">
                                <div class="text_center_wrapper">
                                    <!-- <aside v-if="value.category" class="post-meta">{{ $t(value.category) }}</aside> -->
                                    <div class="title_wrapper">
                                        <!-- <transition name="slide-fade-left"> -->
                                        <!-- <h3 v-if="show" class="entry-title">{{ $t(value.title) | truncate(20) }}</h3> -->
                                            <h4 class="entry-title">{{ $t($getContentLanguageKey(value.title, 'name', 'Tag')) | truncate(35) }}</h4>
                                        <!-- </transition> -->
                                    </div>
                                    <!-- <p v-if="value.description" v-html="value.description"></p> -->
                                    <div class="ul-container">
                                        <ul v-if="value.list">
                                            <v-tooltip v-for="(elem, index) in value.list.slice(0,7)" :key="index" bottom>
                                                <template v-slot:activator="{ on, attrs }">
                                                    <li v-if="elem.name.length > 45" v-bind="attrs" v-on="on">
                                                        <span>{{ $t($getContentLanguageKey(elem.slug, 'name', 'HotelService')) | truncate(45) }}</span>
                                                    </li>
                                                    <li v-else>
                                                        <span>{{ $t($getContentLanguageKey(elem.slug, 'name', 'HotelService')) }}</span>
                                                    </li>
                                                </template>
                                                <span>{{ $t($getContentLanguageKey(elem.slug, 'name', 'HotelService')) }}</span>
                                            </v-tooltip>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="small-6 medium-6 large-6" :style="getBackgroundImage(value.image.filename, 'grid')">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: 'CardTwinFullScreen',
  props: {
    params: {
        type: Object
    },
    data: {
        type: Array
    }
  },
  computed: {
    ...mapState({
      show: state => state.organizations.config.transition.show.slider
    }),
    cssClass() {
      if(this.params.hasOwnProperty("full")) {
        return 'full'
      }

      return null
    }
  },
  mounted() {
    this.$store.commit('organizations/setTransitionShowSlide', true)
  },
  data() {
    return {
      // show: false
    }
  },
  methods: {
    getBackgroundImage: function (filename, format) {
      if(null !== filename) {

        return this.$getUrlBackgroundImage(filename, {
          "format": format, "size": 'auto', "position": 'center'
        })
      }

      return null
    }
  }
}
</script>

<style lang="scss" scoped>

.card-twin {
  height: 100%;
  width: 100%;
}

.card-twin.full {
  // padding: 0;
  // padding-top: 55px;
}

.card-twin .twin-wrapper-container {
  height: 50%;
  // margin-bottom: 50px;
}

.card-twin .twin-wrapper-container .row {
  height: 100%;
  margin: 0;
}

.card-twin .ul-container {
  width: calc(80% - 20px);
}

.card-twin h3 {
  padding-left: 0;
}

.card-twin .title_wrapper {
  // min-height: 45px;
}

.card-twin .ul-container h3 {
  text-align: left;
  margin: 0;
}

.card-twin .row > div {
  height: 100%;
}

.card-twin .right h3 {
  // text-align: right;
}

.card-twin ul {
  text-align: left;
  position: relative;
  list-style-type: none;
  padding-left: 30px;
}

.card-twin ul li::before {
  content: "\2713";
    /* Add content: \2022 is the CSS Code/unicode for a bullet */
  color: var(--color-secondary);
    /* Change the color */
  font-weight: bold;
    /* If you want it to be bold */
  display: inline-block;
    /* Needed to add space between the bullet and the text */
  width: 1em;
    /* Also needed for space (tweak if needed) */
  margin-left: -1em;
    /* Also needed for space (tweak if needed) */
  padding-right: 5px;
}

.card-twin ul li {
  // border-top: 1px solid var(--color-primary-rgba-30);
  margin-bottom: 0px;
  font-size: 0.9rem;
  text-transform: lowercase;
}

.card-twin ul li:last-child {
  // border-bottom: 1px solid var(--color-primary-rgba-30);
}

.card-twin ul li a {
  color: var(--color-primary) !important;
}

.card-twin ul li::first-letter {
  text-transform: uppercase;
}

.card-twin .text-center {
  height: 100%;
  padding:20px
}

.card-twin  .border_wrapper {
  height: 4px;
  margin-bottom: 10px;
}

.card-twin  .border_title {
  border: 2px solid var(--color-primary);
  width: calc(20% - 20px);
}

.card-twin .text_center_wrapper {
  // padding: 15px;
  padding-top: 0;
}

.card-twin .wrapper_service {
  height: 100%;
}

.card-twin .page-padding {
  padding: 35px 0 0 0 !important;
  height: 100%;
}

.card-twin h4 {
    text-transform: uppercase;
    text-align: left;
    padding-left: 20px;
    margin-bottom: 5px;
}
</style>
