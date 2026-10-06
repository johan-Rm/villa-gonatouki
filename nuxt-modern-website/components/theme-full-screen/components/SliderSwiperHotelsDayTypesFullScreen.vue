    <template>
    <div class="slider-swiper-default-full-screen">
        <div class="overlay_screen"></div>
        <swiper ref="swiperComponentDefault"
        :options="swiperOptions"
        @slide-change-transition-start="onSwiperSlideChangeTransitionStart"
        @slide-change-transition-end="onSwiperSlideChangeTransitionEnd"
        class="_swiper-gallery bottom-slide">
            <swiper-slide
            v-for="(dayType, index) in dayTypes"
            :key="index"
            class="_swiper-slide swiper-lazy"
            :style="getBackgroundImage(dayType.primaryImage.filename, 'large')"
            :id="definedSlideId(index)"
            >
            </swiper-slide>
        </swiper>
        <div
            class="welcome"
            :style="getPositionStyle()"
            ref="welcomeContainer"
            v-blur="blurConfigWelcome"
        >
            <h2>
                <span class="prefix main_title">{{ $t($getFreeContentLanguageKey('Bienvenue'))}}</span>
                <span class="prefix main_title">{{ $t($getFreeContentLanguageKey('à la'))}}</span>
                <span class="main_title">{{ $t($getFreeContentLanguageKey('Villa Gonatouki'))}}</span>
            </h2>
        </div>
        <transition name="slide-fade-right">
            <div
                v-if="dayTypesShow"
                id="multiscroll-nav"
            >
                <a
                v-for="(dayType, index) in dayTypes"
                :key="index"
                :id="definedSlideNavId(index)"
                :class="{ 'active': index === 0 }"
                href="#"
                class="multiscroll-elem"
                ref="multiscrollElem"
                >
                    <em>{{ dayType.hours }} <b>{{ $t($getContentLanguageKey(dayType.category.slug, 'name', 'Tag')) }}</b></em>
                </a>
            </div>
        </transition>
        <div v-blur="blurConfigDayTypes" class="photo-caption">
            <span>{{ captionLabel }}</span>
            <p>{{ captionValue | truncate(70) }}</p>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: 'SliderSwiperHotelsDayTypesFullScreen',
  props: {
    params: {
      type: Object
    },
    data: {
      type: Array
    }
  },
  data() {
    return {
        dayTypesShow: false,
        blurConfigDayTypes: {
            isBlurred: true,
            opacity: 0.01,
            filter: 'blur(1.2px)',
            transition: 'all 3.2s linear'
        },
        blurConfigWelcome: {
            isBlurred: true,
            opacity: 0.01,
            filter: 'blur(1.2px)',
            transition: 'all 0.8s linear'
        },
        swiperOptions: this.params.slider.swiperOptions,
        // text: this.data.elements[0].description,
        // label: this.data.elements[0].label,
        // value: this.data.elements[0].value
        captionLabel: null,
        captionValue: null
    }
  },
  computed: {
    ...mapState({
        booking: state => state.hotels.booking
    }),
    swiper() {

      return this.$refs.swiperComponentDefault.$swiper
    },
    dayTypes() {

        /**
        * ON CHOISIT UNE JOURNÉE AU HASARD
        **/
        var index = Math.floor(Math.random() * this.data.length)

        /**
        * OU ON CHOISIT UN EVENT SI JAMAIS UNE DATE ET SELECTIONER
        **/
        if(
            (this.booking.datesOfStay.hasOwnProperty('start') && this.booking.datesOfStay.start !== null)
            &&
            (this.booking.datesOfStay.hasOwnProperty('end') && this.booking.datesOfStay.end !== null)
        ) {
            for (var i = 0; i < this.data.length; i++) {
                const elements = this.data[i].elements
                for (var j = 0; j < elements.length; j++) {
                    const element = this.data[i].elements[j]
                      if(null !== element.event) {
                          if(this.booking.datesOfStay.start >= new Date(element.event.beginAt)
                              && this.booking.datesOfStay.end <= new Date(element.event.endAt)
                          ) {
                              index = i
                              break;
                          }
                      }
                }
            }
        }
        this.captionLabel = this.$i18n.t(this.$getContentLanguageKey(this.data[index].elements[0].slug, 'label', 'HotelTypicalDayElement'))
        this.captionValue = this.$i18n.t(this.$getContentLanguageKey(this.data[index].elements[0].slug, 'value', 'HotelTypicalDayElement'))
        this.$store.commit('organizations/setCurrentDayTypesName', this.data[index])

        return this.data[index].elements
    }
  },
  mounted() {
      //
      // console.log("this")
      // console.log(this.$route)
      // console.log(this.$refs.welcomeContainer)
      setTimeout(() => {
          this.blurConfigWelcome.transition = 'all 3.5s linear'
          this.blurConfigWelcome.isBlurred = false
            setTimeout(() => {
            this.blurConfigWelcome.transition = 'all 0.3s ease-in'
            this.blurConfigWelcome.filter = 'cubic-bezier(0.25, 0.1, 0.25, 1)'
            this.blurConfigWelcome.isBlurred = true
                setTimeout(() => {
                this.blurConfigDayTypes.filter = 'blur(1.2px)'
                this.blurConfigDayTypes.transition = 'all 1.2s linear'
                this.blurConfigDayTypes.isBlurred = false
                  this.dayTypesShow = true
                  this.swiper.autoplay.start()
                }, 400)
            }, 3500)
        }, 1500)
  },
  methods: {
    getPositionStyle: function () {
        let top = '50%'
        let left = '50%'
        const element = this.$refs.welcomeContainer;

        if(typeof element !== 'undefined') {

            // top = (element.clientHeight/2) + 'px'
            // left =  (element.clientWidth/2) + 'px'
        }

        return {
            'top': top,
            'left': left
        }
    },
    onSwiperSlideChangeTransitionStart(swiper) {


      // vue-blur config runtime
      this.blurConfigDayTypes.filter = 'blur(1.2px)'
      this.blurConfigDayTypes.transition = 'all 0.2s linear'
      this.blurConfigDayTypes.isBlurred = true
      // https://www.codegrepper.com/code-examples/delphi/vue+ref+add+class
      let elementsArray = document.getElementsByClassName("multiscroll-elem")
      if(elementsArray.length > 0) {
        for (let element of elementsArray) {
            element.classList.remove('active')
        }
      }
      let currentSlideId = this.definedSlideNavId(swiper.realIndex)
      let currentElem = document.getElementById(currentSlideId)
      currentElem.classList.add('active')
    },
    onSwiperSlideChangeTransitionEnd(swiper) {

      let elem = this.dayTypes[swiper.realIndex]
      // this.text = elem.description
      // this.label = elem.label
      // this.value = elem.value
      this.captionLabel = this.$i18n.t(this.$getContentLanguageKey(elem.slug, 'label', 'HotelTypicalDayElement'))
      this.captionValue = this.$i18n.t(this.$getContentLanguageKey(elem.slug, 'value', 'HotelTypicalDayElement'))
      this.blurConfigDayTypes.filter = 'blur(1.2px)'
      this.blurConfigDayTypes.transition = 'all 1.2s linear'
      this.blurConfigDayTypes.isBlurred = false
    },
    getBackgroundImage: function (filename, format) {
      if(null !== filename) {

        return this.$getUrlBackgroundImage(filename, { "format": format })
      }

      return null
    },
    definedSlideId: function (index) {

        return 'slide-image-' + index
    },
    definedSlideNavId: function (index) {

        return 'slide-image-nav-' + index
    }
  }
}
</script>

<style lang="scss" scoped>

.container-side-open .slider-swiper-default-full-screen .photo-caption
, .menu-open .slider-swiper-default-full-screen .photo-caption
{
  display: none;
}

.slider-swiper-default-full-screen .photo-caption h4 {
  color: #fff !important;
  font-weight: 400;
  font-size: 1.2rem;
}

.slider-swiper-default-full-screen .welcome {
        position: absolute;
        z-index: 200;
        max-width: 500px;

        display: flex;
        justify-content: center;
        align-items: center;
        /* position the div in center */
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
}

.slider-swiper-default-full-screen .welcome h2 {
    color: #fff;
    text-transform: uppercase;
    text-align: center;
}

.slider-swiper-default-full-screen .welcome h2 > span {
    display: block;
}

.slider-swiper-default-full-screen .welcome h2 > span.main_title.prefix {
    display: block;
    font-size: 1.5rem;
    font-style: italic;
}

.slider-swiper-default-full-screen .photo-caption p {
  color: #fff !important;
  font-weight: 400;
  font-size: 1rem;
  margin: 0;
}

.slider-swiper-default-full-screen .photo-caption p::first-letter {
  // color: var(--color-secondary) !important;
  // font-weight: 700;
  // font-size: 1.2rem;
  text-transform: uppercase;
}

.slider-swiper-default-full-screen .photo-caption
{
  position: absolute;
  bottom: 15px;
  right: 20px;
  z-index: 40;
  min-width:20%;
  max-width:35%;
  border-radius: 7px;
  text-align:center;
}

.slider-swiper-default-full-screen #multiscroll-nav a
{
  color: #fff;
}

.slider-swiper-default-full-screen #multiscroll-nav a:after
{
  background-color: #fff;
  display: none;
}
.slider-swiper-default-full-screen #multiscroll-nav a.active:after
{
  background-color: var(--color-secondary);
}

.slider-swiper-default-full-screen #multiscroll-nav a em
{
  opacity: 1;
  font-weight: 700;
  // font-size: 1rem;
  font-size: 0.9rem;
  right:0;
}

.slider-swiper-default-full-screen #multiscroll-nav a.active em
{
  opacity: 1;
  color: var(--color-secondary);
  font-size: 0.9rem;
}

.slider-swiper-default-full-screen #multiscroll-nav
{
  top: 25vh;
  z-index: 40;
  right: 2vw;
}

.slider-swiper-default-full-screen .photo-caption span::before {
  right: -50px;
}


.slider-swiper-default-full-screen .photo-caption span::before {
  content: ' ';
  bottom: calc(50% - 1px);
  bottom: -moz-calc(50% - 1px);
  bottom: -webkit-calc(50% - 1px);
  width: 30px;
  height: 1px;
  background: rgba(255,255,255,0.7);
  position: absolute;
  display: none;
}

.slider-swiper-default-full-screen .photo-caption p
{
  // margin-right: -60px;
}

.slider-swiper-default-full-screen .photo-caption span {

  position: relative;
  // right: -0.3em;
  color: #fff;
  // font-size:2.5rem;
  font-size:2.0rem;
  text-transform: uppercase;
}

.slider-swiper-default-full-screen #multiscroll-nav a::after {
	top: 8px;
}

.slider-swiper-default-full-screen #multiscroll-nav a {
	height: 20px;
}

@media screen and (max-width: 48.063em){
    #multiscroll-nav{
        display:block;
        right:25px
    }

    .slider-swiper-default-full-screen .photo-caption
    {

      max-width:95%;

    }
}
</style>
