<template>
<div class="booking-form-summary-container">
  <div class="row align-center no-padding p-left">
    <div class="small-12 medium-12 large-12 wrapper-container">
      <!-- <div class="recap_title">
        {{$t('Vous souhaitez réserver la') }}
        &nbsp;
        <span class="color_secondary">{{ room.name }}</span>
      </div> -->
      <!-- <transition name="slide-fade-left"> -->
          <div class="recap_infos rooms">
              <div class=""
                  v-for="(room, index) in getBookingRooms"
                  :key="index"
              >
                <span class="color_secondary">
                    {{ room.selectedNumberOfRooms }} "{{ room.name }}"
                </span>
                &nbsp;{{ getNbRoomLabel(room) }}&nbsp;{{ getPriceLabel(room) }}
            </div>
        </div>
      <!-- </transition> -->
      <!-- <hr class="separate"/> -->

      <transition name="slide-fade-left">
      <div v-if="booking.datesOfStay.hasOwnProperty('end') && booking.datesOfStay.end !== null"
        class="recap_infos"
      >
        {{ $t($getFreeContentLanguageKey('pour la période du')) }}  <span class="color_secondary">{{this.$dayjs(booking.datesOfStay.start).format('DD MMMM YYYY') }}</span> au <span class="color_secondary">{{this.$dayjs(booking.datesOfStay.end).format('DD MMMM YYYY') }}</span>
      </div>
      </transition>
      <transition name="slide-fade-left">
      <div v-if="booking.numberOfAdults > 0"
        class="recap_infos"
      >
        {{ $t($getFreeContentLanguageKey('pour')) }} <span class="color_secondary">{{booking.numberOfAdults }}</span> {{ $t($getFreeContentLanguageKey('adultes')) }}
        <span v-if="booking.numberOfChildren > 0" class="color_secondary">{{ $t($getFreeContentLanguageKey('et')) }} {{booking.numberOfChildren }} {{ $t($getFreeContentLanguageKey('enfants')) }}</span>
      </div>
      </transition>
      <transition name="slide-fade-left">
      <div v-if="booking.person.lastname"
        class="recap_infos name"
      >
        {{ $t($getFreeContentLanguageKey('votre réservation sera au nom de')) }} <span class="color_secondary">{{ booking.person.firstname }}</span> <span class="color_secondary">{{ booking.person.lastname }}</span>
      </div>
      </transition>
       <transition name="slide-fade-left">
      <div v-if="booking.person.phone"
        class="recap_infos"
      >
        {{ $t($getFreeContentLanguageKey('vous serez joignable au')) }} <span class="color_secondary">({{ booking.person.phoneCountry }}) {{ booking.person.phone }}</span>
      </div>
      </transition>
      <transition name="slide-fade-left">
      <div v-if="booking.person.email"
        class="recap_infos"
      >
        et par email : <span class="color_secondary">{{ booking.person.email }}</span>
      </div>
      </transition>
      <transition name="slide-fade-left">
      <div v-if="booking.person.message"
        class="recap_infos textarea"
      >
        <div><span class="color_secondary">{{ $t($getFreeContentLanguageKey('vous souhaitez nous faire part de')) }} : </span></div>
        <p>
          {{booking.person.message | truncate(200) }}
        </p>
      </div>
      </transition>
    </div>


    <div class="btn-container-wrapper">

            <div v-if="booking.rooms.length > 0" class="total total-form-valid">

                {{ $t($getFreeContentLanguageKey('prix total de votre séjour')) }} <span class="color_secondary">{{ totalPrice }}</span>

            </div>
            <div class="btn-form-valid">
                <v-btn
                  @click="submit()"
                  class="site-button btn-slide _button-sm"
                  :loading="loading"
                  :disabled="loading"
                  >
                    <span>{{ $t($getFreeContentLanguageKey('Valider')) }}</span>
                </v-btn>
            </div>

    </div>
   <!--  <div
      class="small-12 medium-3 large-3 wrapper-column wrapper-column-right"
      :style="getBackgroundImage(room.primaryImage.filename, 'vertical')"
    ></div> -->
  </div>
</div>
</template>
<script>
import { mapState } from 'vuex'
import { EventBus } from '~/plugins/event-bus.js'
export default {
  name:'BookingFormRoomFullScreen',
  data () {

    return {
      // selected: 1,
      // room: {}
      loading: false
    }
  },
  computed: {
    ...mapState({
      privatization_offers: state => state.hotels.privatization_offers,
      rooms: state => state.hotels.rooms,
      booking: state => state.hotels.booking
    }),
    totalPrice() {
        var totalPrice = 0
        var rooms = this.rooms.concat(this.privatization_offers)
        this.booking.rooms.forEach(function(elem, i){
          let room = rooms.find(r => r.slug === elem.slug)
          totalPrice = room.price + totalPrice
        })
        this.$store.commit('hotels/setBookingTotalPrice', totalPrice)

        return totalPrice + ' €'
    },
    getBookingRooms() {
        var rooms = this.booking.rooms

        // return rooms.sort(function (a, b) {
        //   return a.index - b.index
        // })
        return rooms
    }
    // room() {
    //   const rooms = this.rooms.concat(this.privatization_offers)
    //   const slug = this.booking.rooms[0].slug
    //   var room = rooms.find(r => r.slug === slug)
    //   this.$store.commit('hotels/setRoom', room)
    //
    //   return room
    // }
  },
  methods: {
    submit() {
      // console.log('emit submit')
      EventBus.$emit('submit-booking', {})
    },
    getCivility(civility) {

      return ('female' === civility)? 'Mme': 'M.'
    },
    getNbRoomLabel: function(room) {
        // console.log("getNbRoomLabel")
        // console.log(room)
      let nbPerson = room.selectedNumberOfRooms * room.maximumOccupants

      // this.$pluralize('chambre', numberOfRooms)
      return 'pour ' + nbPerson + ' pers.'//this.$pluralize('personne', nbPerson, true)
    },
    getPriceLabel: function(room) {
      var label = 'no price'
      const today = new Date()
      if(Object.entries(this.booking.datesOfStay).length > 0) {
        let dates = this.$getRangeDatesFrom2Dates(this.booking.datesOfStay.start, this.booking.datesOfStay.end)
        dates.pop()
        let count = dates.length
        let price = this.$generateBookingPrice('fr', room, dates, room.selectedNumberOfRooms, room.selectedAddOn)
        // console.log('getPriceLabel')
        // console.log(price)
        this.$store.commit('hotels/setRoomsPrice', { "slug": room.slug, "price": price })
        label = 'et ' + this.$pluralize('nuit', count, true) + ' ' + price + ' €'
      } else {
          // console.log('getPriceLabelllllllllllllllll')
// console.log(room)
        let dates = [ today ]
        let price = this.$generateBookingPrice('fr', room, dates, room.selectedNumberOfRooms, room.selectedAddOn)
        this.$store.commit('hotels/setRoomsPrice', { "slug": room.slug, "price": price })
        label = 'et 1 nuit ' + price + ' €'
      }

      return label
    },
    getBackgroundImage: function (filename, format) {
      if(null !== filename) {

        return this.$getUrlBackgroundImage(filename, { "format": format, "size": 'cover' })
      }

      return null
    }
  }
}
</script>
<style lang="scss">
.booking-form-summary-container {

    padding:30px 0 0 0;
}

.booking-form-summary-container .wrapper-container {
  min-height: 300px;
  padding: 0px 30px;
}

.style-chooser .vs__search::placeholder,
.style-chooser .vs__dropdown-toggle,
.style-chooser .vs__dropdown-menu {
  background: #fff;
  // border: 2px solid var(--color-secondary);
  padding: 0;
  /*color: #394066;*/
  /*text-transform: lowercase;*/
  /*font-variant: small-caps;*/
  /*display: inline-block;*/
}

.style-chooser .vs__search::placeholder, .style-chooser .vs__dropdown-toggle, .style-chooser .vs__dropdown-menu {

}

.style-chooser .vs__search, .style-chooser .vs__search:focus {
  font-size: inherit;
}

.style-chooser .vs__clear,
.style-chooser .vs__open-indicator {
  /*fill: #394066;*/
}

.style-chooser input[type="search"] {
  border: 0;
  padding: 0;
}

.style-chooser .vs__actions {
  display: none;
}

.style-chooser .vs__selected {

  line-height: 0.4;

}

.booking-form-summary-container .recap_infos {
  min-height: 32px;
}

.booking-form-summary-container .recap_infos.rooms {
  // min-height: 144px;
  margin-bottom: 20px;
}

.booking-form-summary-container .total {
        line-height: 2.5rem;
}

.booking-form-summary-container .recap_infos.total {
  text-align: center;
  font-size: 1.2rem;
  margin: 15px 0;
}


.booking-form-summary-container .recap_infos.name .color_secondary {
  text-transform: capitalize;
}


.booking-form-summary-container .recap_infos.contact {
  height: 88px;
}

.booking-form-summary-container .recap_infos.textarea span {
        // background: var(--color-secondary);
        // border-radius: 5px;
        // color:#fff;
        // padding: 5px 10px;
        text-transform: uppercase;
}

.booking-form-summary-container .recap_infos.textarea {
  height: 110px;
  max-height: 110px;
  overflow: hidden;
  margin-top: 15px;
}
</style>
