<template>
<div class="booking-form-dates-container">
  <client-only>
    <vue-hotel-datepicker
      :startingDateValue="startDate"
      :endingDateValue="endDate"
      @check-in-changed="checkInChanged"
      @check-out-changed="checkOutChanged"
      @clear-selection="clearSelection"
      :i18n="fr"
      :format="format"
    />
  </client-only>
  <div class="row align-center mt-7">
      <div class="small-6 medium-6 large-6 columns">
          <div class="row align-center">
              <div class="small-6 medium-6 large-6 columns">
                 <label>Pour</label>
              </div>
               <div class="small-6 medium-6 large-6 columns">
                   <v-text-field
                     :label="$t($getFreeContentLanguageKey('Adultes'))"
                     v-model="form.numberOfAdults"
                     @blur="updateNumberOfAdults()"
                   ></v-text-field>
               </div>
          </div>
      </div>
      <div class="small-6 medium-6 large-6 columns">
          <div class="row align-center">
              <div class="small-6 medium-6 large-6 columns">
                  <label>et</label>
              </div>
               <div class="small-6 medium-6 large-6 columns">
                   <v-text-field
                     :label="$t($getFreeContentLanguageKey('Enfants'))"
                     v-model="form.numberOfChildren"
                    @blur="updateNumberOfChildren()"
                   ></v-text-field>
               </div>
          </div>
      </div>
  </div>


</div>
</template>
<script>
import { mapState } from 'vuex'
export default {
    name: 'BookingFormDatesFullScreen',
    data() {

        return {
            form: {
                numberOfAdults: 0,
                numberOfChildren: 0
            },
            clear: false,
            format: "DD-MM-YYYY",
            datesOfStay: {
                start: null,
                end: null
            },
            fr: {
                "night": "Nuit",
                "nights": "Nuits",
                "week": "semaine",
                "weeks": "semaines",
                "day-names": [
                    "Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"
                ],
                "check-in": "Arrivée",
                "check-out": "Départ",
                "month-names": [
                    "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
                ],
                "tooltip": {
                    "halfDayCheckIn": "Available CheckIn",
                    "halfDayCheckOut": "Available CheckOut",
                    "saturdayToSaturday": "Only Saturday to Saturday",
                    "sundayToSunday": "Only Sunday to Sunday",
                    "minimumRequiredPeriod": "%{minNightInPeriod} %{night} minimum.",
                }
            }
        }
    },
    computed: {
        ...mapState({
            booking: state => state.hotels.booking
        }),
        startDate() {
            // console.log('this.booking.datesOfStay.start')
            // console.log(this.booking.datesOfStay.start)
            if (this.booking.datesOfStay.hasOwnProperty('start')) {
                return this.booking.datesOfStay.start
            }

            return null

        },
        endDate() {
            // console.log('this.booking.datesOfStay.end')
            // console.log(this.booking.datesOfStay.end)

            if (this.booking.datesOfStay.hasOwnProperty('end')) {
                return this.booking.datesOfStay.end
            }

            return null
        }
    },
    mounted() {
        this.form.numberOfAdults = this.booking.numberOfAdults
        this.form.numberOfChildren = this.booking.numberOfChildren    
    },
    methods: {
        updateNumberOfAdults() {
            this.$store.commit('hotels/setBookingNumberOfAdults', this.form.numberOfAdults)
        },
        updateNumberOfChildren() {
            this.$store.commit('hotels/setBookingNumberOfChildren', this.form.numberOfChildren)
        },
        clearSelection: function(event) {
            // console.log('BookingFormDatesFullScreen : clearSelection')
        },
        checkInChanged: function(dateStart) {
            // console.log('BookingFormDatesFullScreen : checkInChanged')
            this.$store.commit('hotels/setBookingDatesOfStay', {
                "start": dateStart,
                "end": null
            })
            // console.log("this.booking.datesOfStay")
            // console.log(this.booking.datesOfStay)
        },
        checkOutChanged: function(dateEnd) {
            if (null !== this.booking.datesOfStay.start && null !== dateEnd) {
                // console.log('BookingFormDatesFullScreen : checkOutChanged')
                this.$store.commit('hotels/setBookingDatesOfStay', {
                    "start": this.booking.datesOfStay.start,
                    "end": dateEnd
                })
            } else {
                this.$store.commit('hotels/setBookingDatesOfStay', {})
            }
            // console.log("this.booking.datesOfStay")
            // console.log(this.booking.datesOfStay)
        }
    }
}
</script>
<style lang="scss">
.booking-form-dates-container p {
    color: #000 !important;
}

.booking-form-dates-container .datepicker--open {
    width: 100%;
}

.booking-form-dates-container .datepicker__inner {
    padding: 0;
}

.booking-form-dates-container .datepicker__month-name {
    padding: 0;
}

.booking-form-dates-container .datepicker__month-day-wrapper {
    padding-top: calc(100% - 20px);
}

.booking-form-dates-container .datepicker__months {
    width: 100%;
}

.booking-form-dates-container .v-input__slot {
    margin-bottom: 0px;
}

.booking-form-dates-container input[type="text"] {
    margin-bottom: 0px;
}

.booking-form-dates-container .vhd__datepicker__dummy-wrapper {
	border: 0;
}

.booking-form-dates-container .vhd__datepicker__input {

    border-bottom-width: thin 0 0 0;
    border-bottom: 1px solid #fff;

}



</style>
