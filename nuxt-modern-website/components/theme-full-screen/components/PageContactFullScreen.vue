<template>
<div class="page-contact-full-screen">
    <div v-if="checkDevice()" class="slider-swiper-contact-full-screen align-center no-padding p-right">
        <swiper
          :ref="swiperComponentPages"
          :options="mergedParams.options"
          @slide-change-transition-start="onSwiperSlideChangeTransitionStart"
          @slide-change-transition-end="onSwiperSlideChangeTransitionEnd"
          class="_swiper-container-vertical"
          >
            <swiper-slide :id="definedSlideId(0)" class="twin panel contact-form content-flex">   
              <ContactForm/>
               <!-- <br/>
                <pre>
                ContactFormFullScreen
                </pre>
                <br/> -->
            </swiper-slide>
            <swiper-slide :id="definedSlideId(1)" class="twin panel"> 
              <div class="contact-informations">
                <ContactInformations/>
                </div>
                <div class="contact-maps">
                    <ContactMaps/>
                    <!-- <br/>
                    <pre>
                    ContactMapsFullScreen
                    </pre>
                    <br/> -->
                </div>
            </swiper-slide>
        </swiper>
    </div>
    <div v-else class="row align-center no-padding p-right contact-container">
        <div class="small-12 medium-6 large-6 panel contact-form content-flex">
            <ContactForm/>
            <!-- <br/>
            <pre>
            ContactFormFullScreen
            </pre>
            <br/> -->
        </div>
        <div class="small-12 medium-6 large-6 panel contact-informations-maps">
            <div class="contact-informations">
                <ContactInformations/>
                <!-- <br/>
                <pre>
                ContactInformationsFullScreen
                </pre>
                <br/> -->
            </div>  
            <div class="contact-maps">
                <ContactMaps/>
                <!-- <br/>
                <pre>
                ContactMapsFullScreen
                </pre>
                <br/> -->
            </div>
        </div>
    </div>
</div>
</template>
<script>
import { EventBus } from '~/plugins/event-bus.js'
export default {
    name: 'PageContactFullScreen',
    components: {
        ContactForm: () => import('~/components/theme-full-screen/components/CardContactFormFullScreen'),
        ContactInformations: () => import('~/components/theme-full-screen/components/CardContactInformationsFullScreen'),
        ContactMaps: () => import('~/components/theme-full-screen/components/CardContactMapsFullScreen'),
    },
    props: {
        params: {
            type: Object
        }
    },
    data() {
        return {}
    },
    mounted() {
        EventBus.$on('next-slide', data => {
            if (this.swiperComponentPages === data.origin) {
                this.swiper.slideTo(data.slide, 900, false)
            }
        })
    },
    methods: {
        checkDevice() {
            return this.$device.isMobile
        },
        onSwiperSlideChangeTransitionStart(swiper) {
            let key = swiper.realIndex
            this.$store.commit('organizations/setTransitionShowSlide', false)
        },
        onSwiperSlideChangeTransitionEnd(swiper) {
            this.$store.commit('organizations/setTransitionShowSlide', true)
            EventBus.$emit('slide-change', true)
        },
        definedSlideId: function(index) {
            return 'slide-image-' + index
        }
    },
    computed: {
        mergedParams() {
            let preventClicksPropagation = true
            let allowTouchMove = (this.$device.isMobile) ? true : false
            let speed = (this.$device.isMobile) ? 100 : 900
            let params = {
                options: {
                    "direction": 'vertical',
                    "loop": false,
                    "speed": speed,
                    "mousewheel": true,
                    "preventClicksPropagation": preventClicksPropagation,
                    "allowTouchMove": allowTouchMove
                }
            }

            return {
                ...this.params,
                ...params
            }
        },
        swiperComponentPages() {

            return 'swiper-component-contact-' + this.$route.name
        },
        swiper() {
            const index = this.swiperComponentPages

            return this.$refs[index].$swiper
        }
    }
    
}
</script>
<style>

.page-contact-full-screen {
    height: 100%;
}

.slider-swiper-contact-full-screen .panel {
    height: 100%;
}

.page-contact-full-screen .panel.contact-form {
    background: var(--color-primary);
}

.page-contact-full-screen .panel .contact-informations {
    /* background: pink; */
    height: 50%;
}

.page-contact-full-screen .panel .contact-maps {
    /* background: light-green; */
    height: 50%;
}

</style>
