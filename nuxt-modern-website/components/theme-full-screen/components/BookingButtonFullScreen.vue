<template>
<div class="booking-button-container">
  <!-- <div class="booking-button booking-button-icon book">
    <i class="fa fa-hotel"></i>
  </div> -->
  <div class="booking-button book" :class="bgNotTransparent">
      <div class="booking-button-label booking-button-label-effect">
        <a href="#">{{ bookingLabel }}</a>
      </div>
  </div>
</div>
</template>
<script>
import { mapState } from 'vuex'
// import SocialMedia from '~/components/theme-modern-immobilier/components/SocialMedia'
// import SearchButton from '~/components/theme-modern-immobilier/components/SearchButton'
export default {
    name:'BookingButtonFullScreen',
    data () {
        return {}
    },
    computed: {
      ...mapState({
        bodyClassWithoutFullImage: state => state.organizations.config.bodyClassWithoutFullImage,
        booking: state => state.hotels.booking
      }),
      bookingLabel() {

        // return 'Booking'
        return 'Choisir vos dates'
      },
      bgNotTransparent() {

        return (this.bodyClassWithoutFullImage)? 'bg-no-transparent': ''
      }
    }
}
</script>
<style scoped>


._booking-button-icon {
  background-color: var(--color-black-rgba-80);
  padding: 10px;
  border-radius: 8px;
  /*margin-left: 5px;*/
}

._booking-button-circle {
  color: #fff;
  background-color: var(--color-secondary);
  padding: 10px 0;
  border-radius: 50%;
  height: 55px;
  width: 55px;
  display:inline-block;
  text-align:center;
  line-height: 1.2em;
  text-transform: uppercase;
  font-size: 0.9em;
  cursor: pointer;
}

._booking-button-container .booking-button {
    /* color: var(--color-secondary); */
  color: #fff;
  background-color: var(--color-primary-rgba-80);
  padding: 10px 20px;
  border-radius: 7px;
  display:inline-block;
  text-align:center;
  line-height: 1.2em;
  text-transform: uppercase;
  font-size: 0.9em;
  font-weight: bold;
  /*margin-left: 5px;*/
  cursor: pointer;
}

._booking-button-container .booking-button.bg-no-transparent {

  background-color: var(--color-primary);
}

.header-full-screen .booking-button-label {

  background-color: #fff;
  background-color: var(--color-secondary);
  color: #fff;
  font-weight: 400;
  text-transform: uppercase;
  padding: 0px 10px;
  /*font-size: 1rem;*/
  cursor:pointer;
  border: 1px solid var(--color-secondary);
  /*border: 1px solid #fff;*/
  border-top: 0;
  border-bottom: 0;
  border-radius: 0;
  font-family: "Rajdhani", sans-serif;
min-height: 35px;
font-size: 24px;
}

.header-full-screen .booking-button-label-effect {
  /*color: var(--color-secondary);*/
  background-color: inherit;
  /*border-radius: 3px;*/
  position: relative;
  /*font-weight:700;*/
  /*border: 1px solid var(--color-secondary);*/
}

.header-full-screen .booking-button-label-effect::before {
  -webkit-transition-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
  transition-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
}

.header-full-screen .booking-button-label-effect:hover::before
, .container-side-open .header-full-screen .booking-button-label-effect:before {
  opacity: 1;
  -webkit-transform: translate3d(0, 0, 0);
  transform: translate3d(0, 0, 0);
  color: #fff;
}

.header-full-screen .booking-button-label-effect:hover
, .container-side-open .header-full-screen .booking-button-label-effect{
  color: #fff;
  border: 1px solid var(--color-secondary);
  border-top: 0;
  border-bottom: 0;
}

.header-full-screen .booking-button-label-effect::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: var(--color-secondary);
  z-index: -1;
  opacity: 0;
  -webkit-transform: scale3d(0.7, 1, 1);
  transform: scale3d(0.7, 1, 1);
  -webkit-transition: -webkit-transform 0.4s, opacity 0.4s;
  transition: transform 0.4s, opacity 0.4s;
  -webkit-transition-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
  transition-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
}

.header-full-screen .booking-button-label a {

     color: #fff;

 }

@media screen and (max-width: 40.0625em){
   .header-full-screen .booking-button-label a {
    	font-weight: 700;
    	font-size: 0.7rem;
        color: #fff;
        line-height:2.4rem;
    }
}
</style>
