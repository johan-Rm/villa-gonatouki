<template>
<div class="booking-form-rooms-container">
  <form class="wpcf7-form" novalidate>
      <div class="large-12">
        <span class="wpcf7-form-control wpcf7-checkbox wpcf7-validates-as-required">
          <div
            v-for="(room, index) in getRooms"
            :key="index"
            class="wpcf7-form-control-wrap your-rooms"
          >
           <div class="row">
             <div class="small-4 medium-4 large-4">
                <v-checkbox
                    v-model="form.rooms"
                    :label="room.name"
                    :value="room.slug"
                    @change="selectedRoom(index, room)"
                    hide-details
                  ></v-checkbox>
             </div>
             <div class="small-4 medium-4 large-4">
              <transition name="slide-fade-right">
              <div v-if="room.more_infos" class="row">
                <div class="small-2 medium-2 large-2">
                   <v-select
                    :options="[1,2,3]"
                    :value="room.selected"
                    @input="selected => updatedRoom(room, selected)"
                    class="style-chooser"
                    :clearable="false"
                    :autoscroll="false"
                    maxHeight="20px"
                  />
                </div>
                <div class="small-10 medium-10 large-10">
                   <div class="wpcf7-list-item-label">
                  {{ getNbRoomLabel(room.selected, room) }}
                  </div>
                </div>
              </div>
              </transition>
             </div>
             <div class="small-4 medium-4 large-4">
              <transition name="slide-fade-right">
                <label v-if="room.more_infos">
                  <aside class="post-meta">{{ getPriceLabel(room) }}</aside>
                </label>
              </transition>
             </div>
           </div>
          </div>
        </span>
      </div>
      <div class="form_validation">
      {{ $t($getFreeContentLanguageKey('Prix total de votre séjour')) }}   : <span class="color_secondary">{{ totalPrice() }}</span>
      </div>
  </form>
</div>
</template>
<script>
import { mapState } from 'vuex'
export default {
  name:'BookingFormRoomsFullScreen',
  data () {

    return {
      list: [],
      form: {
        rooms: [],
      }
    }
  },
  computed: {
    ...mapState({
      rooms: state => state.hotels.rooms,
      booking: state => state.hotels.booking,
      datesOfStay: state => state.hotels.booking.datesOfStay
    }),
    getRooms() {
      this.rooms.forEach(element => {
        this.list.push({
          ...element
          , selected: 1
          , more_infos: false
          , numberOfRooms: 1
        })
      })

      return this.list
    }
  },
  methods: {
    selectedRoom(index, room) {
      console.log('BookingFormRoomsFullScreen : selectedRoom')
      // console.log('this.form.rooms')
      // console.log(this.form.rooms)
      // console.log('this.booking')
      // console.log(this.booking)


      let result = this.form.rooms.find(r => r === room.slug)
      // console.log('result : ' + result)
      if('undefined' == typeof result) {
        // console.log('removeBookingRooms')
        room.more_infos = false
        this.$store.commit('hotels/removeBookingRooms', room.slug)
      } else {
        // console.log('addBookingRooms')
        room.more_infos = true
        var rooms = {
          "slug": room.slug,
          "numberOfRooms": 1
        }
        this.$store.commit('hotels/addBookingRooms', rooms)
      }
    },
    updatedRoom(room, selected) {
      // console.log('BookingFormRoomsFullScreen : updatedRoom')
      room.selected = selected
      room.numberOfRooms = selected
    },
    getNbRoomLabel: function(number, room) {
      let nbPerson = number * room.maximumOccupants

      return this.$pluralize('chambre', number, false)
        + ' pour ' + this.$pluralize('personne', nbPerson, true)
    },
    getPriceLabel: function(room) {
      var label = 'no price'
      const today = new Date()
      if(Object.entries(this.datesOfStay).length > 0) {
        let dates = this.$getRangeDatesFrom2Dates(this.datesOfStay.start, this.datesOfStay.end)
        dates.pop()
        let count = dates.length
        let price = this.$generateBookingPrice('fr', room, dates, room.numberOfRooms, room.selectedAddOn)
        // room.price = price
        label = 'pour ' + this.$pluralize('nuit', count, true) + ' ' + price + ' €'
      } else {
        let dates = [ today ]
        let price = this.$generateBookingPrice('fr', room, dates, room.numberOfRooms, room.selectedAddOn)
        // room.price = price
        label = 'pour 1 nuit ' + price + ' €'
      }

      return label
    },
    totalPrice() {
      var totalPrice = 0
      var rooms = this.list
      this.booking.rooms.forEach(function(elem, i){
        let room = rooms.find(r => r.slug === elem.slug)
        totalPrice = room.price + totalPrice
      })

      return totalPrice + ' €'
    }
  }
}
</script>
<style lang="scss">

.style-chooser .vs__search::placeholder,
.style-chooser .vs__dropdown-toggle,
.style-chooser .vs__dropdown-menu {
  background: #fff;
  border: 2px solid var(--color-secondary);
  padding: 0;
  /*color: #394066;*/
  /*text-transform: lowercase;*/
  /*font-variant: small-caps;*/
  /*display: inline-block;*/
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
</style>
