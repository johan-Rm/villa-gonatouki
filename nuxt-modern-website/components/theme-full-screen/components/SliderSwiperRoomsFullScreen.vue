<template>
    <div class="slider-swiper-vertical-rooms-full-screen">
        <swiper ref="swiperComponentRooms"
            :options="params.options"
            @slide-change-transition-start="onSwiperSlideChangeTransitionStart"
            @slide-change-transition-end="onSwiperSlideChangeTransitionEnd"
            class="_swiper-container-vertical"
        >
            <swiper-slide id="slide-image-0">
                <card-page :params="getParams(0)" :data="data" />
            </swiper-slide>
            <swiper-slide v-for="(value, index) in data.rooms" :key="index+1" :id="definedSlideId(index)">
                <div class="slider-rooms-wrapper row align-center no-padding p-right" :id="definedSlideContainerId(index)">
                    <div class="slider-rooms-slide small-12 medium-8 large-9" style="background-repeat: no-repeat;background-size: cover;">
                        <SliderSwiperRoomFullScreen :params="mergedParams" :data="value" :slug="value.slug" />
                        <i v-if="(data.rooms.length-1) > index" @click="nextSlide(index+1)" class="fa fa-caret-down slide-down">
                        </i>
                        <i v-if="index > 0" @click="preventSlide(index+1)" class="fa fa-caret-up slide-up">
                        </i>
                    </div>
                    <div class="slider-rooms-container small-12 medium-4 large-3">
                        <div class="wrapper-details">
                            <div class="details album-header">
                                <aside class="post-meta maximumOccupants">
                                    {{ getOccupants(value) }} {{ $t($getFreeContentLanguageKey('personnes')) }}
                                </aside>
                                <aside class="post-meta">
                                    {{ getPriceLabel(value) }}
                                </aside>
                                <h3 class="entry-title">{{ $t($getContentLanguageKey(value.slug, 'name', 'Room')) }}</h3>
                                <v-card color="basil">
                                    <v-tabs
                                        align-with-title
                                        show-arrows
                                        :height="vTabsHeight"
                                    >
                                        <v-tab href="#tab-room_details">
                                            {{ $t($getFreeContentLanguageKey('Détails')) }}
                                        </v-tab>
                                        <v-tab href="#tab-room_description">
                                            {{ $t($getFreeContentLanguageKey('Description')) }}
                                        </v-tab>
                                        <v-tab-item value="tab-room_details">
                                            <ul v-if="checkIfSimpleAmenities(value.amenities)">
                                                <v-tooltip
                                                    v-for="(elem, index) in reorderAmenities(value)"
                                                    :key="index"
                                                    bottom
                                                >
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <li v-if="checkAmenityLabel(elem, 30)" v-bind="attrs" v-on="on">
                                                            {{ getAmenityLabel(elem) | truncate(30) }}
                                                        </li>
                                                        <li v-else>
                                                            {{ getAmenityLabel(elem) }}
                                                        </li>
                                                    </template>
                                                    <span>{{ getAmenityLabel(elem) }}</span>
                                                </v-tooltip>
                                            </ul>
                                            <ul v-else>
                                                <li v-for="(elems, category) in reorderAmenities(value)" :key="category">
                                                    {{ category }}
                                                    <ul>
                                                        <v-tooltip v-for="(elem, index) in elems" :key="index" bottom>
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <li v-if="checkAmenityLabel(elem, 25)" v-bind="attrs" v-on="on">
                                                                    {{ getAmenityLabel(elem) | truncate(25) }}
                                                                </li>
                                                                <li v-else>
                                                                    {{ getAmenityLabel(elem) }}
                                                                </li>
                                                            </template>
                                                            <span>{{ getAmenityLabel(elem) }}</span>
                                                        </v-tooltip>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </v-tab-item>
                                        <v-tab-item value="tab-room_description">
                                            <div class="text">
                                                {{ value.description }}
                                            </div>
                                        </v-tab-item>
                                    </v-tabs>
                                </v-card>
                                <div class="rooms_addon">
                                    <span>{{ $t($getFreeContentLanguageKey('lit-supplementaire-a-la-demande-15-par-pers')) }}</span>
                                </div>

                            </div>
                            <div class="btn-container-wrapper">
                                <div class="rooms_label">
                                    <span>{{ getRoomsLabel(value.numberOfRooms) }}</span>
                                </div>
                                <v-btn @click="openSideContainer(value.slug)"
                                class="site-button btn-slide _button-sm">
                                    <span>{{ $t($getFreeContentLanguageKey('Réserver cette chambre')) }}</span>
                                </v-btn>
                            </div>
                        </div>
                    </div>
                </div>
            </swiper-slide>
        </swiper>
    </div>
</template>

<script>
import { EventBus } from '~/plugins/event-bus.js'
import { mapState } from 'vuex'
export default {
    name: 'SliderSwiperRoomsFullScreen',
    props: {
      params: {
          type: Object
      },
      data: {
          type: Object
      }
    },
    components: {
        CardPage: () => import('~/components/theme-full-screen/components/CardPageFullScreen'),
        SliderSwiperRoomFullScreen: () => import('~/components/theme-full-screen/components/SliderSwiperRoomFullScreen')
    },
    computed: {
      ...mapState({
        booking: state => state.hotels.booking,
        isContainerSideBookingOpen: state => state.organizations.config.isContainerSideBookingOpen
      }),
      mergedParams(value) {
        let params = {
          id: value,
          options: {
            template: 'room',
            swiperOptions: {
              autoplay: {
                delay: 2500,
                disableOnInteraction: false
              },
              speed: 1500,
              mousewheel: false,
              effect: "fade",
              preventClicksPropagation: true,
              allowTouchMove: false
            }
          }
        }

        return {...this.params, ...params}
      },
      swiper() {

        return this.$refs.swiperComponentRooms.$swiper
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
      EventBus.$on('next-slide', data => {
        if("slider-swiper-rooms" === data.origin) {
          this.swiper.slideTo(data.slide, 200, false)
        }
      })
    },
    methods: {
        checkAmenityLabel(amenity, length) {
            let test = amenity.name + ' ' + amenity.moreInfo
            if(test.length > length) {

                return true
            }

            return false
        },
        getAmenityLabel(amenity) {
            let name = this.$t(this.$getContentLanguageKey(amenity.slug, 'name', 'HotelAmenity'))
            let moreInfo = ""
            if("" !== amenity.moreInfo) {
              moreInfo = this.$t(this.$getContentLanguageKey(amenity.slug, 'moreInfo', 'HotelAmenity'))
            }

            return name + ' ' + moreInfo
        },
        checkIfSimpleAmenities(amenities) {
            if(amenities[0].category.slug !== 'accueil') {

                return false
            }

            return true
        },
        reorderAmenities(room) {
            // console.log('reorderAmenities')
            // console.log(room.name)
            // console.log(room.amenities)
            // console.log(room)
            var amenities = room.amenities
            if(amenities[0].category.slug !== 'accueil') {
                let groupbyKeys = function groupBy(objectArray, property) {
                   return objectArray.reduce((acc, obj) => {
                      const key = obj[property].slug
                      if (!acc[key]) {
                         acc[key] = []
                      }
                      // Add object to list for given key's value
                      acc[key].push(obj)
                      return acc
                   }, {})
                }

                return groupbyKeys(room.amenities, 'category')
            }

            return amenities
        },
        getOccupants(room) {
                if(room.maximumOccupants == room.minimumOccupants) {
                    return room.maximumOccupants
                }

                return room.minimumOccupants + '/' + room.maximumOccupants
        },
      openSideContainer(slug) {
        var room = {
          "slug": slug,
          "numberOfRooms": 0
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
      getRoomsLabel: function(count) {
          var label = ''
          let word1 = this.$t(this.$getFreeContentLanguageKey('chambre'))
          label = count + ' ' + this.$pluralize(word1, count, false)
          let word2 = this.$t(this.$getFreeContentLanguageKey('disponible'))
          label += ' ' + this.$pluralize(word2, count, false)

          return label
        },
        getPriceLabel: function(room) {

          var label = 'no price'
          const today = new Date()
          if(Object.entries(this.datesOfStay).length > 0) {
            let dates = this.$getRangeDatesFrom2Dates(this.datesOfStay.start, this.datesOfStay.end)
            dates.pop()
            let count = dates.length
            let price = this.$generateBookingPrice('fr', room, dates, 1, "non")
            // room.price = price
            let prefix = this.$t(this.$getFreeContentLanguageKey('À partir de'))
            label = prefix + ' ' + this.$pluralize('nuit', count, true) + ' ' + price + ' €'
          } else {
            let dates = [ today ]
            let price = this.$generateBookingPrice('fr', room, dates, 1, "non")
            // room.price = price
            let prefix = this.$t(this.$getFreeContentLanguageKey('À partir de'))
            label = prefix + ' ' + price + ' €'
          }

          return label
        },
        getBackgroundImage: function (filename, format) {
            if(null !== filename) {

            return this.$getUrlBackgroundImage(filename, { "format": format })
          }

          return null
        },
        onSwiperSlideChangeTransitionStart(swiper) {
          let key = swiper.realIndex
          this.$store.commit('organizations/setTransitionShowSlide', false)
        },
        onSwiperSlideChangeTransitionEnd(swiper) {
           this.$store.commit('organizations/setTransitionShowSlide', true)
        },
        nextSlide(index) {
          let next = index + 1
          this.swiper.slideTo(next, 900, false)
        },
        preventSlide(index) {
          let prevent = index - 1
          this.swiper.slideTo(prevent, 900, false)
        },
        definedSlideContainerId: function (index) {
            index = index + 1

            return 'slide-container-' + index
        },
        definedSlideId: function (index) {
            index  = index  + 1

            return 'slide-image-' + index
        },
        defineSlideByIndex: function (index) {

            return index % 2 == (0 || 1) ? true : false
        },
        getParams(index) {

          let direction = 'left'
          if(false === this.defineSlideByIndex(index)) {
            direction = 'right'
          }

          let id = this.definedSlideId(index)

          return {
            "id": id,
            "direction": direction,
            "index": index,
            "full": true,
            "origin": "slider-swiper-rooms",
            "btnLabel": "Chambre confort à partir de 72€"
          }
        }
    }
}
</script>

<style lang="scss">
.slider-swiper-vertical-rooms-full-screen .swiper-slide {
  overflow: hidden;
}

.slider-swiper-vertical-rooms-full-screen .custom-swiper-button-next , .slider-swiper-vertical-rooms-full-screen .custom-swiper-button-prev {
  background: var(--color-secondary);
  cursor: pointer;
}

.slider-swiper-vertical-rooms-full-screen .slider-rooms-wrapper {
  height: 100%;
}

.slider-swiper-vertical-rooms-full-screen .slider-rooms-container {
  padding-top: 35px;

}

.slider-swiper-vertical-rooms-full-screen .slider-rooms-container .wrapper-details {
  height: 100%;
  position: relative;
  // display: flex;
  // align-items: center;
}

.slider-swiper-vertical-rooms-full-screen .slider-rooms-container .details {
  padding: 15px 15px;
  width:100%;
  height: 100%;
}

.slider-swiper-vertical-rooms-full-screen .slider-rooms-container .details .text {
  text-align: justify;
  padding: 15px 15px;

}

.slider-swiper-vertical-rooms-full-screen .text::first-letter {
  text-transform: uppercase;
}

.slider-swiper-vertical-rooms-full-screen .slider-rooms-container .details .description {
  text-align: justify;
}

.slider-swiper-vertical-rooms-full-screen .slider-rooms-container .wrapper-menu {
  height: 30%;
}

.slider-swiper-vertical-rooms-full-screen .slider-rooms-container .details ul {
  text-align: left;
  padding-right: 15px;
}

.slider-swiper-vertical-rooms-full-screen .swiper-pagination {
  background: var(--color-primary);
  color: #fff;
}


.slider-swiper-vertical-rooms-full-screen .swiper-pagination-bullet {
  padding: 2px 15px;
  display: block;
  border-radius: 0;
  width: auto;
  opacity: 1;
  background: inherit;
  color: #fff;
  text-align: left;
  height: inherit;
}

.slider-swiper-vertical-rooms-full-screen .swiper-pagination {
  // bottom: 50px;
  text-align: center;
  position: inherit;
}
.slider-swiper-vertical-rooms-full-screen .swiper-pagination-bullet-active {

}

.slider-swiper-vertical-rooms-full-screen .swiper-pagination-bullet {
  display:none;
}

.slider-swiper-vertical-rooms-full-screen .swiper-pagination-bullet-active {
  display:block;
}

.slider-swiper-vertical-rooms-full-screen h3, .slider-swiper-vertical-rooms-full-screen h5 {
  text-transform: uppercase;
  margin-bottom: 0;
  // margin-top: 15px;
  color: var(--color-secondary);
}

.slider-swiper-vertical-rooms-full-screen aside.post-meta {
  // color: var(--color-primary);
  font-style: italic;
  font-size: 1rem;
  font-weight: 700;
}

.slider-swiper-vertical-rooms-full-screen aside.post-meta.maximumOccupants {
  color: var(--color-primary);
  font-style: italic;
  font-size: 0.8rem;
  font-weight: 400;
}

.slider-swiper-vertical-rooms-full-screen .v-tabs-items {
  padding:15px 0;
}

.slider-swiper-vertical-rooms-full-screen .v-tabs {
  margin-top: 15px;
  font-size: 0.9rem;
}



.slider-swiper-vertical-rooms-full-screen .slider-rooms-slide {
  position: relative
}

.slider-swiper-vertical-rooms-full-screen .rooms_label {
  // position: absolute;
  // right: 0;
  // bottom: 60px;
  width: 100%;
  // z-index: 5000;
  // min-width: 30%;
  // text-align:center;
  // box-shadow:0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
  // background: var(--color-primary-rgba-80);
  // padding: 10px 20px;
  // border-radius: 7px;
}

.slider-swiper-vertical-rooms-full-screen .fa {
  color: #fff;
  font-weight: bold;
  cursor: pointer;
  font-size: 48px;
  position: absolute;
  z-index: 50;
  color: var(--color-secondary);
}

.slider-swiper-vertical-rooms-full-screen .fa.slide-up {
  top: 45%;
  right: -15px;
}

.slider-swiper-vertical-rooms-full-screen .fa.slide-down {
  bottom: 40%;
  right: -15px;
}

.slider-swiper-vertical-rooms-full-screen .rooms_label span {
  // color: #fff;
  color: var(--color-secondary);
  font-weight: 400;
  // cursor: pointer;
  font-size: 0.9rem;
}

.slider-swiper-vertical-rooms-full-screen ul {
  text-align: left;
  position: relative;
  list-style-type: none;
  padding-left: 30px;
}

.slider-swiper-vertical-rooms-full-screen ul li::before {
  content: "\2713";
  color: var(--color-secondary);
  font-weight: bold;
  display: inline-block;
  width: 1em;
  margin-left: -1em;
  padding-right: 5px;
}

.slider-swiper-vertical-rooms-full-screen ul li {
  margin-bottom: 1px;
  font-size: 0.9rem;
  text-transform: lowercase;
}

.slider-swiper-vertical-rooms-full-screen ul li a {
  color: var(--color-primary) !important;
}

.slider-swiper-vertical-rooms-full-screen ul li::first-letter {
  text-transform: uppercase;
}

.slider-swiper-vertical-rooms-full-screen .v-tabs-items .v-window__container {
  // min-height: 220px;
}

.slider-swiper-vertical-rooms-full-screen .btn-slide {
  position: absolute;
  right: 15%;
  bottom: 20px;
}

.slider-swiper-vertical-rooms-full-screen .rooms_label span::before {
  content: " ";
  bottom: calc(50% - 1px);
  bottom: -webkit-calc(50% - 1px);
  width: 30px;
  height: 1px;
  background: rgba(255, 255, 255, 0.7);
  position: absolute;
  left: 20px;
}

.slider-swiper-vertical-rooms-full-screen .rooms_addon {
    font-size: 0.7rem;
    font-style: italic;
}

.slider-swiper-vertical-rooms-full-screen .slider-rooms-container .v-sheet.v-card {
    // height: 50vh;
    width: 95%;
    margin: 0 auto;
    overflow: hidden;
}

.slider-swiper-vertical-rooms-full-screen .v-sheet.v-card:not(.v-sheet--outlined) {
	box-shadow: inherit !important;
  max-height: 67%;
overflow-y: scroll;
}

@media screen and (min-width: 90.0625em){
    .slider-swiper-vertical-rooms-full-screen .slider-rooms-container .v-sheet.v-card {
        height: 60vh;
    }
}

@media screen and (max-width: 40.625em){
    .slider-swiper-vertical-rooms-full-screen .slider-rooms-wrapper.row > .large-3 {
        height: 50vh;
    }
    .slider-swiper-vertical-rooms-full-screen .slider-rooms-wrapper.row > .large-9 {
        height: 50vh;
    }

    .slider-swiper-vertical-rooms-full-screen .slider-rooms-container {
           padding-top: inherit;
    }

    .slider-swiper-vertical-rooms-full-screen .v-sheet.v-card {
        max-height: 25vh;
        width: 90%;
        margin: 0 auto;
        overflow: scroll;
    }

    .slider-swiper-vertical-rooms-full-screen .v-tabs {
        margin-top: 0px;

    }
    .slider-swiper-vertical-rooms-full-screen .fa.slide-up
    , .slider-swiper-vertical-rooms-full-screen .fa.slide-down {
        display: none;
    }

    .slider-swiper-vertical-rooms-full-screen .v-tabs-items .v-window__container {
    	height: 115px;
        overflow: hidden;
    }

    .slider-swiper-vertical-rooms-full-screen .v-tabs-items {
    	padding: 5px 0;
    }

    .slider-swiper-vertical-rooms-full-screen .slider-rooms-container .details .text {

    	padding: 5px 0;
    }

    .slider-swiper-vertical-rooms-full-screen .slider-rooms-container .details {
      padding: 5px 5px;
    }
}

</style>
