<template>
    <div class="card-simple card-simple-full-screen dark-page">
        <div class="row align-center no-padding p-left" :id="params.id">
            <div class="page-content-card-simple">
                <div class="page-content-top">
                    <div class="aside_wrapper">
                        <transition name="slide-fade-left">
                            <aside v-if="show" class="post-meta">
                                <span>Essaouira, Maroc</span>
                            </aside>
                        </transition>
                    </div>
                    <h2 class="entry-title">{{ $t($getContentLanguageKey(data.webPage.slug, 'headline', 'WebPage')) }}</h2>
                    <blockquote v-if="data.webPage.blockquote">
                        <p>“{{ $t($getContentLanguageKey(data.webPage.slug, 'blockquote', 'WebPage')) }}<br/>{{ $t($getContentLanguageKey(data.webPage.slug, 'pushForward', 'WebPage')) | truncate(30, false) }}”</p>
                    </blockquote>
                    <p class="text" v-if="data.webPage.text" v-html="$t($getContentLanguageKey(data.webPage.slug, 'text', 'WebPage'))"></p>
                </div>
                <div class="page-content-bottom">
                    <hr class="separate" />
                    <!-- <ul v-if="data.list" class="list _row">
                        <li class="_large-6" v-for="(elem, index) in getList" :key="index">
                            <a href="#" @click="elem.isActive = !elem.isActive">
                                <span>{{ elem.name | truncate(50) }}</span>
                            </a>
                            <transition name="slide-fade-left">
                                <div class="child" v-if="elem.isActive">
                                    {{ elem.moreInfo }}
                                </div>
                            </transition>
                        </li>
                    </ul> -->

                    <div class="container_tab_list">
                        <v-card>
                            <v-tabs
                                v-model="tab"
                                align-with-title
                                show-arrows
                                :height="vTabsHeight"
                            >
                                <v-tab v-for="(value, index) in getListTab" :key="index">
                                    {{ index }}
                                </v-tab>
                            </v-tabs>
                            <v-tabs-items v-model="tab">
                                <v-tab-item v-for="(value, index) in getListTab" :key="index">
                                    <ul class="_list _row">
                                        <v-tooltip
                                            v-for="(elem, index) in getItemsList(value)"
                                            :key="index"
                                            bottom
                                            content-class="dark-tooltip"
                                        >
                                            <template v-slot:activator="{ on, attrs }">
                                                <li v-if="elem.name.length > limit" v-bind="attrs" v-on="on">
                                                    <span><em>{{ elem.index }}</em> {{ $t($getContentLanguageKey(elem.slug, 'name', 'HotelService')) | truncate(limit) }}</span>
                                                </li>
                                                <li v-else>
                                                    <span><em>{{ elem.index }}</em> {{ $t($getContentLanguageKey(elem.slug, 'name', 'HotelService')) }}</span>
                                                </li>
                                            </template>
                                            <span>{{ $t($getContentLanguageKey(elem.slug, 'name', 'HotelService')) }}</span>
                                        </v-tooltip>
                                    </ul>
                                </v-tab-item>
                            </v-tabs-items>
                        </v-card>
                    </div>
                </div>
            </div>
            <div class="btn-container-wrapper">
                <v-btn @click="nextSlide(params.index, params.origin)" class="site-button btn-slide _button-sm">
                    <span>{{ $t($getFreeContentLanguageKey('Explore')) }}</span>
                </v-btn>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
import { EventBus } from '~/plugins/event-bus.js'
export default {
  name: 'CardSimpleFullScreen',
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
      list: [],
      tab: null
    }
  },
  computed: {
    ...mapState({
      show: state => state.organizations.config.transition.show.slider,
      booking: state => state.hotels.booking
    }),
    limit() {
      return (this.$device.isMobile)? 32: 25
    },
    getFullVillaOffers() {

      return this.data.privatization_offers[0]
    },
    getPartialVillaOffers() {

      return this.data.privatization_offers[1]
    },
    getListTab() {
     if(this.data.hasOwnProperty('list')) {
       var list = []
       this.data.list.forEach((element, index) => {
         list.push({
           ...element
           , isActive: false
           , "index": index+1
         })
       })

       this.list = this.groupBy(list, "category")


       return this.list
     }

     return []
    },
    getList() {
      if(this.data.hasOwnProperty('list')) {
        this.data.list.forEach(element => {
          this.list.push({
            ...element
            , isActive: false
          })
        })

        return this.list.slice(0, 18)
      }

      return []
    },
    datesOfStay() {

      return this.booking.datesOfStay
    },
      vTabsHeight() {
          if(this.$device.isMobile) {

              return 45
          }

          return 55
      }
  },
  mounted() {
    this.$store.commit('organizations/setTransitionShowSlide', true)
  },
  methods: {
      groupBy(tableauObjets, propriete){
        return tableauObjets.reduce(function (acc, obj) {
          var cle = obj[propriete];
          if(!acc[cle.slug]){
            acc[cle.slug] = [];
          }
          acc[cle.slug].push(obj);
          return acc;
        }, {});
      },
    getBackgroundImage: function (filename, format) {
      if(null !== filename) {

        return this.$getUrlBackgroundImage(filename, { "format": format, "size": 'cover' })
      }

      return null
    },
    nextSlide: function (index, origin) {
      const data = { "slide": 1, "origin": origin }
      EventBus.$emit('next-slide', data)
    },
    getPriceLabel: function(room) {

      var label = 'no price'
      const today = new Date('2021-02-03')
      if(Object.entries(this.datesOfStay).length > 0) {
        let dates = this.$getRangeDatesFrom2Dates(this.datesOfStay.start, this.datesOfStay.end)
        let count = dates.length
        let price = this.$generateBookingPrice('fr', room, dates, 1, "non")
        label = 'Total pour '  + this.$pluralize('nuit', count, true) + ' ' + price + ' €'
      } else {
        let dates = [ today ]
        let price = this.$generateBookingPrice('fr', room, dates, 1, "non")
        label = 'À partir de ' + price + ' €'
      }

      return label
  },
  getItemsList(value) {
      if(this.$device.isMobile) {

          // return value.slice(0, 5)
      }

      return value
      // return value.slice(0, 15)
  }

  }
}
</script>

<style lang="scss" scoped>

.card-simple {
  background: var(--color-primary);
  width: 100%;
}

.card-simple .page-content-card-simple {
    width: 100%;
}

.card-simple blockquote p {
  color: var(--color-secondary);
}



.card-simple, .card-simple .row
{
  height: 100%;
}

.card-simple h4 {
  color: var(--color-secondary);
}

.card-simple p {
  padding: 0 6rem;
  color: #fff;
}

.card-simple p.text {
  text-align: justify;
}

.card-simple .container_tab_list ul li em {
    color: var(--color-secondary);
    margin-right: 5px;
    // font-size: 1rem;
}

.card-simple ul
{
  text-align: left;
  position: relative;
  list-style-type: none;
  // margin-top: 30px;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  padding: 0 45px;
}



.card-simple ul li
{
  // border-top: 1px solid var(--color-primary-rgba-30);
  width: calc(50% - 20px);
  margin-bottom: 5px;
  font-size: 0.9rem;
  text-transform: lowercase;
}



.card-simple ul li a span {
  // display: inline-block;
}

.card-simple ul li a span::first-letter {
  // text-transform: uppercase;
}

.card-simple  ul li::before {
  // content: "\2713";
  // color: var(--color-secondary);
  // font-weight: bold;
  // display: inline-block;
  // width: 1em;
  // margin-left: -1em;
  // padding-right: 5px;
}

.card-simple ul li:nth-last-child(-n+2)
{
  // border-bottom: 1px solid var(--color-primary-rgba-30);
}

.card-simple ul li a
{
  color: var(--color-primary) !important;
}

.card-simple .album-header
{
  padding-bottom: 20px;
}



.card-simple .blog-post-container
{
// background: #fff;
// background-image: url("https://www.transparenttextures.com/patterns/white-wall-3-2.png");
//     background-repeat: repeat;
//     background-color: var(--color-primary-rgba-80);
}

.card-simple .wrapper-container
{
  position: relative;
}

.card-simple  .btn-slide
{
    position: absolute;
    right: 44%;
    bottom: 25px;
}

.card-simple .privatization_offers {
  margin: 20px 0 45px 0;
}

.card-simple .privatization_offers .btn-slide {
  position: relative;
  bottom: inherit;
  right: inherit;
  margin-top: 25px;
}

.card-simple .separate {
  width: calc(30% - 1px);
  background: #fff;
  text-align: center;
  margin: 0 auto;
  margin-top: 20px;
}

.card-simple .page-content-top {
    height: 55vh;
    overflow: hidden;
}

.card-simple .page-content-bottom {
    height: 35vh;
    overflow: hidden;
}

.card-simple .container_tab_list {
    // padding: 10px 15px 0 25px;
    width: 100%;
    height:100%;
}

.card-simple .v-sheet.v-card {
	min-height: 85%;
	width: 90%;
	margin: 0 auto;
    overflow: hidden;
    margin-top: 20px;
}

.card-simple .v-sheet.v-card:not(.v-sheet--outlined) {
	box-shadow: inherit;
}

.card-simple .v-tabs-items ul {
	display: flex;
	flex-wrap: wrap;
	justify-content: space-between;
  
}

.card-simple .v-tabs-items ul li {
	width: calc(30% - 20px);
}

@media screen and (max-width: 40.625em){

  .card-simple .v-tabs-items ul {
     display: inherit !important;
    height: 30vh;
    overflow: scroll;
    padding: 0.5rem;
    margin: 0 1.5rem;
  }

    .card-simple .v-tabs-items ul li
    {
      width: calc(100% - 20px);
    }

   

    .card-simple p {
    	padding: 0 20px;
    	text-align: justify;
    }

    .card-simple blockquote p {
    	padding: 0 1.5rem;
    	text-align: center;
    }

    .card-simple .page-content-top {
      height: 45vh;
      overflow: hidden;
    }

    .card-simple .page-content-bottom {
        height: 45vh;
        overflow: hidden;
    }

}

</style>
