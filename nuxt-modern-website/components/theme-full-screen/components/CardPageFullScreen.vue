<template>
    <div class="card-page card-page-full-screen">
        <div class="row align-center no-padding p-left" :id="params.id">
            <div class="small-12 medium-8 large-9 page-container wrapper-container">
                <div class="_page-content-padding">
                    <div class="page-content-top">
                        <div class="aside_wrapper">
                            <transition name="slide-fade-left">
                                <aside v-if="show" class="post-meta">
                                    <span>Essaouira, Maroc</span>
                                </aside>
                            </transition>
                        </div>
                        <h2 class="entry-title">{{ $t($getContentLanguageKey(data.webPage.slug, 'headline', 'WebPage')) }}</h2>
                        <div v-if="data.privatization_offers">
                            <div class="row align-center collapse privatization_offers">
                                <div class="small-12 medium-6 large-6 wrapper-container">
                                    <v-card :elevation="3">
                                        <h4>{{ getFullVillaOffers.name }}</h4>
                                        <div class="booking_label">
                                            <div class="price_label">
                                                {{ getPriceLabel(getFullVillaOffers) }}
                                            </div>
                                            <a
                                                class="site-button btn-slide button-sm"
                                                @click="openSideContainer(getFullVillaOffers.slug)"
                                            >
                                                <span>{{ $t($getFreeContentLanguageKey('Réserver')) }}</span>
                                            </a>
                                        </div>
                                    </v-card>
                                </div>
                                <div class="small-12 medium-6 large-6 wrapper-container">
                                    <v-card :elevation="3">
                                        <h4>{{ getPartialVillaOffers.name }}</h4>
                                        <div class="booking_label">
                                            <div class="price_label">
                                                {{ getPriceLabel(getPartialVillaOffers) }}
                                            </div>
                                            <a
                                                class="site-button btn-slide button-sm"
                                                @click="openSideContainer(getPartialVillaOffers.slug)"
                                            >
                                                <span>{{ $t($getFreeContentLanguageKey('Réserver')) }}</span>
                                            </a>
                                        </div>
                                    </v-card>
                                </div>
                            </div>
                        </div>
                        <blockquote v-if="data.webPage.blockquote">
                            <p>“{{ $t($getContentLanguageKey(data.webPage.slug, 'blockquote', 'WebPage')) }}”</p>
                        </blockquote>
                        <p v-if="data.webPage.text && !data.privatization_offers" class="text" v-html="$t($getContentLanguageKey(data.webPage.slug, 'text', 'WebPage'))"></p>
                    </div>
                    <div class="page-content-bottom">
                        <hr class="separate" />
                        <p
                            v-if="data.webPage.text && data.privatization_offers"
                            class="text text_privatization_offers"
                            v-html="$t($getContentLanguageKey(data.webPage.slug, 'text', 'WebPage'))"
                        ></p>
                        <div v-if="$device.isMobile && data.privatization_offers" class="row align-center collapse privatization_list">
                            <div class="small-12 medium-6 large-6">
                                <ul class="_list _row">
                                    <v-tooltip v-for="(elem, index) in getList" :key="index" bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <li v-if="elem.name.length > 40" v-bind="attrs" v-on="on">
                                                <span>{{ elem.name | truncate(40) }}</span>
                                            </li>
                                            <li v-else>
                                                <span>{{ elem.name }}</span>
                                            </li>
                                        </template>
                                        <span>{{ elem.name }}</span>
                                    </v-tooltip>
                                </ul>
                            </div>
                        </div>
                        <div v-else-if="data.privatization_offers" class="privatization_list_container">
                            <div class="row align-center collapse privatization_list">
                                <div class="small-12 medium-6 large-6">
                                    <ul class="_list _row">
                                        <v-tooltip v-for="(elem, index) in getListFirst" :key="index" bottom>
                                            <template v-slot:activator="{ on, attrs }">
                                                <li v-if="elem.name.length > 40" v-bind="attrs" v-on="on">
                                                    <span>{{ elem.name | truncate(40) }}</span>
                                                </li>
                                                <li v-else>
                                                    <span>{{ elem.name }}</span>
                                                </li>
                                            </template>
                                            <span>{{ elem.name }}</span>
                                        </v-tooltip>
                                    </ul>
                                </div>
                                <div class="small-12 medium-6 large-6">
                                    <ul class="_list _row">
                                        <v-tooltip v-for="(elem, index) in getListSecond" :key="index" bottom>
                                            <template v-slot:activator="{ on, attrs }">
                                                <li v-if="elem.name.length > 40" v-bind="attrs" v-on="on">
                                                    <span>{{ elem.name | truncate(40) }}</span>
                                                </li>
                                                <li v-else>
                                                    <span>{{ elem.name }}</span>
                                                </li>
                                            </template>
                                            <span>{{ elem.name }}</span>
                                        </v-tooltip>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div v-else-if="data.list" class="row collapse">
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
                                                >
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <li v-if="elem.name.length > limit" v-bind="attrs" v-on="on">
                                                            <span><em>{{ elem.index }}</em> {{ elem.name | truncate(limit) }}</span>
                                                        </li>
                                                        <li v-else>
                                                            <span><em>{{ elem.index }}</em> {{ elem.name }}</span>
                                                        </li>
                                                    </template>
                                                    <span>{{ elem.name }}</span>
                                                </v-tooltip>
                                            </ul>
                                        </v-tab-item>
                                    </v-tabs-items>
                                </v-card>
                            </div>
                        </div>
                        <div v-else-if="data.rooms"
                            class="rooms_container_list"
                        >
                            <div class="row align-center collapse">
                                <div
                                    v-for="(elem, index) in data.rooms"
                                    :key="index"
                                    class="small-6 medium-4 large-4"
                                >
                                    <v-card :elevation="3">
                                        <h4>{{ elem.name }}</h4>
                                        <div class="booking_label">
                                            <div class="price_label">
                                                {{ getPriceLabel(elem) }}
                                            </div>
                                            <div class="btn_label">
                                                <a
                                                    class="site-button btn-slide button-sm"
                                                    @click="nextSlide(index + 1, params.origin)"
                                                >
                                                    <span>{{ $t($getFreeContentLanguageKey('Réserver')) }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </v-card>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn-container-wrapper">
                    <v-btn
                        @click="nextSlide(params.index, params.origin)"
                        class="site-button btn-slide _button-sm"
                        v-if="!data.rooms"
                    >
                        <span>{{ btnLabel }}</span>
                    </v-btn>
                </div>
            </div>
            <div
                v-if="!$device.isMobile"
                class="small-12 medium-4 large-3 wrapper-column wrapper-column-right"
                :style="getBackgroundImage(data.webPage.primaryImage.filename, 'vertical')"
            >
                <div class="overlay_area"></div>
                <div v-if="data.webPage.pushForward" class="photo-caption">
                    <div class="aside_wrapper">
                        <transition name="slide-fade-right">
                            <span v-if="show">{{ $t($getContentLanguageKey(data.webPage.category.slug, 'name', 'Tag')) }}</span>
                        </transition>
                    </div>
                    <p>{{ $t($getContentLanguageKey(data.webPage.slug, 'pushForward', 'WebPage')) }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
import { EventBus } from '~/plugins/event-bus.js'

export default {
  name: 'CardPageFullScreen',
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
      listFirst: [],
      listSecond: [],
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
    btnLabel() {
        if(this.params.hasOwnProperty('btnLabel')) {

          return this.$i18n.t(this.$getFreeContentLanguageKey(this.params.btnLabel))
        }
            return this.$i18n.t(this.$getFreeContentLanguageKey('Explore'))
    },
    getFullVillaOffers() {

      return this.data.privatization_offers[0]
    },
    getPartialVillaOffers() {

      return this.data.privatization_offers[1]
    },

    getListFirst() {
      if(this.data.hasOwnProperty('list')) {
        // var indexToSplit = 6
        // var data = this.data.list.slice(0,18)
        // var first = data.slice(0, indexToSplit)
        let limit = 5
        if(this.params.hasOwnProperty('list')) {
          limit = this.params.list.limit
        }
        this.data.list.forEach(element => {
          this.listFirst.push({
            ...element
            , isActive: false
          })
        })

        return this.listFirst.slice(0, limit)
      }

      return []
    },
    getListSecond() {
      if(this.data.hasOwnProperty('list')) {

        // var indexToSplit = 6
        // var data = this.data.list.slice(0,18)
        // var second = data.slice(indexToSplit + 1)
        let limit = 5
        if(this.params.hasOwnProperty('list')) {
          limit = this.params.list.limit
        }

        this.data.list.forEach(element => {
          this.listSecond.push({
            ...element
            , isActive: false
          })
        })
        this.listSecond.reverse()

        return this.listSecond.slice(0, limit)
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
     getListTab() {

      if(this.data.hasOwnProperty('list')) {
        var list = []
        this.data.list.forEach((element, index) => {

              list.push({
                ...element
                , "isActive": false
                , "index": index+1
              })
        })

        this.list = this.groupBy(list, "category")

        return this.list
      }

      return []
    },
    datesOfStay() {

      return this.booking.datesOfStay
      },
      vTabsHeight() {
          if(this.$device.isMobile) {

              return 40
          }

          return 55
      }
  },
  mounted() {
    this.$store.commit('organizations/setTransitionShowSlide', true)
  },
  methods: {
      // slideToRoom(index) {
      //     this.params.swiper.slideTo(index, 900, false)
      // },
      getItemsList(value) {
          if(this.$device.isMobile) {

              // return value.slice(0, 5)
          }

          return value
          // return value.slice(0, 15)
      },
      openSideContainer(slug) {
        var room = {
          "slug": slug,
          "numberOfRooms": 1
        }
        this.$store.commit('hotels/setBookingRoom', room)
        this.$store.commit(
          'organizations/setConfigIsContainerSideDatesOpen'
          , true
        )
        this.$store.commit(
          'organizations/setConfigIsContainerSideBookingOpen'
          , this.isContainerSideBookingOpen
        )
        this.$store.commit(
          'organizations/setTransitionShowDatepicker'
          , true
        )
    },
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
      var data = { slide: 1, "origin": origin }
      if(index > 0 ) {
          data = { slide: index, "origin": origin }
      }
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
    }

  }
}
</script>

<style lang="scss" scoped>

.card-page {
  background: var(--color-primary);
  color: #fff;
}

.card-page .separate {
  width: calc(30% - 1px);
  background: #fff;
  text-align: center;
  margin: 0 auto;
  margin-top: 20px;
}

.card-page .v-sheet.v-card {
	min-height: 85%;
	width: 90%;
	margin: 0 auto;
    overflow: hidden;
}

.card-page .v-sheet.v-card:not(.v-sheet--outlined) {
	box-shadow: inherit;
}

.card-page blockquote p {
  color: var(--color-secondary);
}

.card-page, .card-page .row
{
  height: 100%;
}

.card-page h4 {
  color: var(--color-secondary);
}


.card-page p {
  padding: 0 6rem;
  color: #fff;
}

.card-page p.text {
  text-align: justify;
}

.card-page ul
{
  text-align: left;
  position: relative;
  list-style-type: none;
  // margin-top: 30px;
  padding: 0 45px;
}

.card-page ul li
{
  margin-bottom: 5px;
  font-size: 0.9rem;
  text-transform: lowercase;
}

.card-page .v-tabs-items ul
{
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
}

.card-page .v-tabs-items ul li
{
  width: calc(30% - 20px);
}

.card-page ul li a
{
  color: #fff !important;
}

.card-page .album-header
{
  padding-bottom: 20px;
}


.card-page .page-container
{
    height: 100%;
}

.card-page .wrapper-container
{
  position: relative;
}

.card-page .wrapper-container .btn-slide
{
    position: absolute;
    right: 44%;
    bottom: 25px;
}

.card-page .privatization_offers {
  margin: 0px 0 25px 0;
  margin-bottom: 0;
  padding: 1rem 0;
}

.card-page .privatization_offers h4 {
  margin: 0 0 0 0;
  line-height: 1;
  text-transform: uppercase;
}


.card-page .privatization_offers a {
  color: var(--color-secondary);
  font-weight: 700;
}

.card-page .privatization_offers .booking_label {
  margin-top: 10px;
}

.card-page .privatization_offers .btn-slide {
  position: relative;
  bottom: inherit;
  right: inherit;
  margin-top: 25px;
}

.card-page .privatization_offers .v-card {
	width: 70%;
	margin: 0 auto;
	padding: 10px 0;
}

.card-page .page-content-top {
    height: 45vh;
    overflow: hidden;
}

.card-page .page-content-bottom {
    height: 40vh;
    overflow: hidden;
}

.page-rooms-full-screen .card-page .page-content-top {
    height: 55vh;
    overflow: hidden;
}

.page-rooms-full-screen .card-page .page-content-bottom {
    height: 35vh;
    overflow: hidden;
}

.card-page .container_tab_list ul li em {
    color: var(--color-secondary);
    margin-right: 5px;
}

.card-page .container_tab_list {
    // padding: 10px 15px 0 25px;
    width: 100%;
    margin-top: 20px;
}

.card-page .text_privatization_offers
{
  margin-top: 20px;
}

.card-page .rooms_container_list {
	height: 100%;
	padding: 0 30px;
  width: 80%;
  margin: 0 auto;
	margin-top: 1.0rem;
}

.card-page .rooms_container_list .v-card {
	padding: 0px 0;
}

.card-page .rooms_container_list .btn-slide {
    position: inherit;
    display: inline-block;
}

.card-page .rooms_container_list h4 {
    text-transform: uppercase;
    margin-bottom: 5px;
    font-size: 1rem;
}

.card-page .rooms_container_list .v-sheet.v-card {
	min-height: inherit;
	width: 90%;
    // margin-top: 20px;
    position: relative;
    min-height: 85px;
}

.card-page .rooms_container_list .booking_label {
  position: absolute;
  bottom: 15px;
  width: 100%;
  text-align: center;
}

.card-page .privatization_list_container {
    width: 80%;
    margin: 0 auto;
}

@media screen and (min-width: 90.0625em){
    .card-page .rooms_container_list .v-sheet.v-card {
        min-height: 150px;
    }

    .card-page .privatization_offers {
        padding: 2rem 0;
    }

    .card-page .privatization_offers .booking_label {
	       margin-top: 25px;
    }
}

@media screen and (max-width: 40.625em){

    .card-page .v-tabs-items ul li {
      width: calc(100% - 20px);
    }

    .card-page .rooms_container_list h4 {
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .card-page .rooms_container_list .booking_label {
      bottom: inherit;
    }

    .card-page .rooms_container_list .row {
        height: auto;
    }

    .card-page .rooms_container_list .row > div {
        height: 90px;
    }

    .card-page .rooms_container_list .v-sheet.v-card {
    	width: 95%;
    }
    .card-page .rooms_container_list .button-sm {
    	padding: 3px 10px;
    }

    .card-page p {
    	padding: 0 20px;
    	text-align: justify;
    }

    .card-page blockquote p {
    	padding: 0 50px;
    	text-align: center;
        margin-bottom: 5px;
    }

    .card-page .page-content-top {
        height: 45vh;
        overflow: hidden;
    }

    .card-page .page-content-bottom {
        height: 45vh;
        overflow: hidden;
    }

    .card-page .v-tabs-items ul {
      display: inherit !important;
      text-align: left;
      position: relative;
      list-style-type: none;
      height: 30vh;
      overflow-y: scroll;
      padding: 0.5rem;
      margin: 0 1.5rem;
    }

    .page-rooms-full-screen .card-page .page-content-top {
        height: 45vh;
        overflow: hidden;
    }

    .page-rooms-full-screen .card-page .page-content-bottom {
        height: 45vh;
        overflow: hidden;
    }
}
</style>
