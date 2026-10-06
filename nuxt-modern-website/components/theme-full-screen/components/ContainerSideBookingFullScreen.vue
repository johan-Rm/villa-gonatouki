<template>
    <transition name="slide-fade-top">
        <div class="container-side-booking-wrapper container-side-wrapper" v-show="isContainerSideBookingOpen">
            <div class="row no-padding p-left">
                <div v-if="!$device.isMobile" class="small-12 medium-3 large-3 column" :style="getBackgroundImage('piscines-img-33361.jpg', 'large')">
                    <div class="overlay_side_area"></div>
                </div>
                <div class="small-12 medium-9 large-9 wrapper">
                    <i class="fa fa-window-close" @click="close"></i>
                    <div v-if="formBookingContainer" class="form_booking_container">
                        <div class="form_wrapper">
                            <div class="row no-padding">
                                <div class="form_infos_wrapper small-12 medium-6 large-6">
                                    <BookingFormInformationsFullScreen :params="getParams" />
                                </div>
                                <div v-if="!$device.isMobile" class="form_rooms_wrapper small-12 medium-6 large-6">
                                    <div class="heading">
                                        <h5 class="entry-title">{{ $t($getFreeContentLanguageKey('Réservez votre séjour à la')) }} <br/><span class="color_secondary">{{ $t($getFreeContentLanguageKey('Villa Gonatouki')) }}</span>
                                        </h5>
                                    </div>
                                    <BookingFormSummaryFullScreen />
                                </div>
                            </div>
                        </div>
                    </div>
                    <transition name="slide-fade-right">
                        <div v-if="successBookingContainer" class="succes_booking_container">
                            votre reservation c'est ok

                        </div>
                    </transition>
                    <div class="bottom_side">
                        <div class="row no-padding">
              
                            <div class="small-12 medium-12">
                                {{ $t($getFreeContentLanguageKey('Informations réservation')) }}
                                <span>+212 658 256 247</span> / <span>reservation@gonatouki.com</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
import { mapState } from 'vuex'
// https://www.codepanion.com/posts/2020-02-08-implement-form-validation-from-scratch-using-vue-js-mixins/
import validationMixin from '~/mixins/validations'
import { EventBus } from '~/plugins/event-bus.js'

export default {
  name:'ContainerSideBookingFullScreen',
  components: {
    BookingFormRoomsFullScreen: () => import(
      '~/components/theme-full-screen/components/BookingFormRoomsFullScreen'
    ),
    BookingFormSummaryFullScreen: () => import(
      '~/components/theme-full-screen/components/BookingFormSummaryFullScreen'
    ),
    BookingFormDatesFullScreen: () => import(
      '~/components/theme-full-screen/components/BookingFormDatesFullScreen'
    ),
    BookingFormInformationsFullScreen: () => import(
      '~/components/theme-full-screen/components/BookingFormInformationsFullScreen'
    )
  },
  data() {
    return {
      successBookingContainer: false,
      formBookingContainer: true,
      errors: [],
      // snackbar: {
      //   "status": true,
      //   "text": "Your booking has been process",
      //   "timeout": 3000
      // },
      loading: false,
      params: {
        'color-text': true,
        options: {
          autoHeight: true,
          loop: true,
          speed: 900,
          mousewheel: true,
          effect: "slide"
        }
      }
    }
  },
  mixins: [validationMixin],
  computed: {
    ...mapState({
      isContainerSideBookingOpen: state => state.organizations.config.isContainerSideBookingOpen,
      privatization_offers: state => state.hotels.privatization_offers,
      booking: state => state.hotels.booking,
      rooms: state => state.hotels.rooms,
      show: state => state.organizations.config.transition.show.datePicker
    }),
    flipKey() {
      return this.isContainerSideBookingOpen.toString();
    },
    getParams() {
      const rooms = this.rooms.concat(this.privatization_offers)
      const slug = this.booking.room.slug
      var room = rooms.find(r => r.slug === slug)
      this.$store.commit('hotels/setRoom', room)

      return { slug: room.slug }
    }
  },
  mounted() {
    EventBus.$on('submit-booking', data => {
      // console.log('event submit-booking has bien recpt')
      this.submit()
    })
  },
  methods: {
    // onSubmit(e) {
      // e.preventDefault();
      // this.errors = this.validateForm(this.user);

      // if(this.errors.formIsValid) {
      //   console.log('Form is valid. Make an ajax request');
      // }
    // },
    // onChange(e, inputName) {
    //   const inputValue = e.target.value;
    //   // or
    //   // const inputValue = this.user[inputName];
    //   const inputErrors = this.validateField(inputName, inputValue);

    //   if(inputErrors && inputErrors.length) {
    //     this.errors[inputName] = inputErrors;
    //   } else {
    //     this.errors[inputName] = null;
    //   }
    // }
    submit() {
      this.loading = true
      var form = {
        datesOfStay: this.booking.datesOfStay,
        rooms: this.booking.rooms
      }
      form = { ...form, ...this.booking.person }

      // console.log('submit form booking')
      // console.log(form)
      // console.log(this.errors)
      // console.log(this.booking.person)
      // console.log(this.rooms)


      // return false
      this.errors = this.validateForm(form)
       if(true === this.errors.formIsValid){
        /**
        * GET OR CREATE PERSON
        **/
        const params = { email: this.booking.person.email }
        var person = {} // créer une requete spécif pour get person by email
        this.$axios.get('/people', { params }).then((response) => {
          person = response.data['hydra:member'][0]
          if(1 != response.data['hydra:totalItems']) {
            person = this.createPerson(this.booking)
          } else {
            /**
            * CREATE EVENT
            **/
            var event = this.createEvent(this.booking, person)
          }
          
        }).catch(error => {
          console.log(error)
          console.log('error get /people into ContainerSideBookingFullScreen')
        })
        
        /**
        * FAIRE NOTIFICATION VUETIFY
        **/
        // alert('formIsValid === true!!')
      } else {
        alert('formIsValid === false!!')
      }
    },
    createPerson(booking) {
      const params = {
          email: booking.person.email,
          phone: booking.person.phone,
          phoneCountry: booking.person.phoneCountry,
          firstname:booking.person.firstname,
          lastname: booking.person.lastname,
          text: booking.person.message,
          origin: "booking form"
      }
      var person = {}
      this.$axios.post('/people', params )
        .then((response) => {
          person = response.data
          /**
          * CREATE EVENT
          **/
          var event = this.createEvent(booking, person)
      }).catch((e) => { console.log(e) })
// console.log('createPerson')
// console.log(person)
      return person
    },
    createEvent(booking, person) {
//       console.log('createEvent')
// console.log(person)
      // var room = this.rooms.find(r => r.slug === booking.room.slug)
      var rooms = []
      var details = {}
      booking.rooms.forEach(element => {
        rooms.push(element['@id'])
        details[element.slug] = {
            "numberOfRooms" : element.selectedNumberOfRooms,
            "addOn" : element.selectedAddOn
        }
      })

      this.$store.commit('hotels/setBookingDetails', JSON.stringify(details))
      // booking.details = JSON.stringify(details)

      const params = {
        "beginAt": booking.datesOfStay.start,
        "endAt": booking.datesOfStay.end,
        "person": person['@id'],
        // room: room['@id'],
        "rooms": rooms,
        "price": booking.totalPrice.toString(),
        "details": booking.details,
        "numberOfAdults": parseInt(booking.numberOfAdults),
        "numberOfChildren": parseInt(booking.numberOfChildren)
      }
      var event = {}
      this.$axios.post('/events', params )
        .then((response) => {
          // console.log('event = response.data')
          // console.log(response.data)
          event = response.data
// console.log('createEvent')
// console.log(person)
          /**
          * CREATE AND SEND MESSAGE
          **/
          this.createAndSendMessage(person, event)
      }).catch((e) => { console.log(e) })

      return event
    },
    createAndSendMessage(person, event) {
      // console.log('person')
      // console.log(person)
      // console.log('event')
      // console.log(event)
      const message = {
        subject: "unused subject",
        text: this.booking.person.message,
        dateSent: this.$dayjs().format('YYYY-MM-DD H:m:s'),
        messageAttachment: null,
        origin: "booking form",
        recipient: null,
        sender: person['@id'],
        event: event['@id']
      }
      this.$axios.post('/messages', message).then((response) => {
        const params = {
          message: this.booking.person.message,
          firstname: this.booking.person.firstname,
          lastname: this.booking.person.lastname,
          from: 'johan.remy@graines-digitales.online',//this.$store.state.organizations.item.email,
          to: this.booking.person.email,
          locale: 'fr', //this.$store.state.i18n.currentLocale
          person: person['id'],
          event: event['id']
        }
        this.$axios.post(
          process.env.URL_DMS + '/email/confirmation-contact'
          , params
        ).then((response) => {

            this.resetBooking()
            this.formBookingContainer = false
            this.successBookingContainer = true
            // this.snackbar.status = true
            /**
            * FAIRE NOTIFICATION VUETIFY
            **/
        }).catch((e) => { console.log(e) })
      }).catch((e) => { console.log(e) })
    },
    resetBooking() {
      this.$store.commit(
        'hotels/setBookingPerson'
        , {}
      )
      this.$store.commit(
        'hotels/setBookingDatesOfStay'
        , {}
      )
      this.$store.commit(
        'hotels/setBookingRoom'
        , []
      )
    },
    close() {
      this.$store.commit(
        'organizations/setConfigIsContainerSideBookingOpen'
        , true
      )
      this.$store.commit(
        'organizations/setTransitionShowDatepicker'
        , false
      )
      this.$store.commit(
        'hotels/resetBooking'
        , true
      )
    },
    getBackgroundImage: function (filename, format) {
      if(null !== filename) {

        return this.$getUrlBackgroundImage(filename, { "format": format })
      }

      return null
    }
  }
}
</script>

<style lang="scss" scoped>

.container-side-booking-wrapper .booking-form-summary-container {
    // padding: 2rem 0;
}

.container-side-booking-wrapper .btn-slide {
 position: absolute;
 right: 40%;
 bottom: 30px;
}

.container-side-booking-wrapper .fa-window-close {
  position: absolute;
  top: 50px;
  right: 40px;
  font-size: 1.5rem;
  cursor: pointer;
  z-index: 51;
}

.container-side-booking-wrapper h4 {
  color: #fff;
  margin-top: 10px;
  text-transform: uppercase;
}

.container-side-booking-wrapper h5 {
  color: #fff;
  text-align: left;
  font-size: 1.3em;
  line-height: 1.8rem;
}

.container-side-booking-wrapper .heading {
  text-align: center;
  // padding: 10px 30px;
}

.container-side-booking-wrapper .form_dates_wrapper {
  padding: 0 0;
  // text-align: center;
}

.container-side-booking-wrapper {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 40;
  background-color: var(--color-primary-rgba);
  color: #fff;
}

.container-side-booking-wrapper .bottom_side {
  // position: absolute;
  // left: 0;
  // bottom: 20px;
  width: 100%;
  font-size: 0.7rem;
  font-style: italic;
}

.container-side-booking-wrapper .bottom_side .row {
  padding: 0 40px;
  text-align: center;
}

.container-side-booking-wrapper.p-right {
  left: inherit;
  right: 25px;
}

.container-side-booking-wrapper a {
  color: #fff;
}

.container-side-booking-wrapper.bg-no-transparent {
  background-color: var(--color-primary);
}

.container-side-booking-wrapper .theme--light.v-card
, .container-side-booking-wrapper .theme--light.v-tabs > .v-tabs-bar
, .container-side-booking-wrapper .theme--light.v-tabs-items {
  background-color: transparent;
}


.container-side-booking-wrapper .theme--light.v-card
{
  height: 100%;
  overflow: hidden;
  color: #fff;
}

.container-side-booking-wrapper .v-tabs .v-tabs-bar a {
  color: var(--color-secondary) !important;
  width: 50%;
  text-align: center;
}

.container-side-booking-wrapper .theme--light.v-card > .theme--light.v-tabs p
, .container-side-booking-wrapper .theme--light.v-card > .theme--light.v-tabs h2
, .container-side-booking-wrapper .theme--light.v-card > .theme--light.v-tabs h4
, .container-side-booking-wrapper .theme--light.v-card > .theme--light.v-tabs ul li {
  color: #fff;
}

.container-side-booking-wrapper .theme--light.v-tabs > .v-tabs-bar .v-tab:not(.v-tab--active)
{
  color: #fff!important;
  background-color: transparent;
}

.container-side-booking-wrapper .v-sheet.v-card:not(.v-sheet--outlined) {
  box-shadow: inherit;
}

.container-side-booking-wrapper .blog-post-container
{
  background: transparent !important;
}

.container-side-booking-wrapper > .row, .container-side-booking-wrapper > .row > div {
  height: 100%;
}

.container-side-booking-wrapper h5 {
  width: 80%;
  text-align: center;
  text-transform: uppercase;
  margin: 0 auto;
  margin-bottom: .5em;
  margin-top: .5em;
}

.container-side-booking-wrapper .link_dates {
  text-align: center;
  /*color: var(--color-secondary);*/
}

.container-side-booking-wrapper .link_dates a {
  color: var(--color-secondary);
}

.container-side-booking-wrapper .close_dates {
  position: absolute;
  top:55px;
  left: 40px;
}

.container-side-booking-wrapper .column {
  position: relative;
}

.container-side-booking-wrapper .wrapper {
  padding: 50px 40px 40px 40px;
  position: relative;
}

.container-side-booking-wrapper .form_wrapper {
  height: 100%;
  // padding: 10px 0 0 0 ;
}

.container-side-booking-wrapper .form_wrapper .row{
  height: 100%;
}

.container-side-booking-wrapper .form_rooms_wrapper {
 // max-height: 100px;
 // overflow: hidden;
 padding: 0 20px 0 0;
 position: relative;
}

.container-side-booking-wrapper .form_infos_wrapper {
 // padding: 30px 0 0 0;
}


.container-side-booking-wrapper .bottom_side {
  position: absolute;
  left: 0;
  bottom: 20px;
  width: 100%;
}

.container-side-booking-wrapper .form_booking_container {
  position: relative;
  height: 100%;
  overflow: hidden;
}

@media screen and (max-width: 40.625em){
    .container-side-booking-wrapper .fa-window-close[data-v-709a7761] {

        top: 55px;
    	right: 15px;

    }

    .container-side-booking-wrapper .wrapper {
    	padding: 60px 20px 0px 20px;
    }
}
</style>
