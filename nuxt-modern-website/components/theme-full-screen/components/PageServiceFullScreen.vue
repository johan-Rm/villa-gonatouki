<template>
    <div class="page-service-full-screen">
        <div class="page-container row no-padding align-center">
            <div
            class="small-12 medium-3 large-3 columns wrapper-column"
            :style="getBackgroundImage(data.webPage.primaryImage.filename, 'large')">
                <div class="overlay_area"></div>
                <transition name="slide-fade-left">
                    <div v-if="showColumn" class="column-service dark">
                        <div class="column-service-container">
                            <div class="card-padding description text-center">
                                <div class="aside_wrapper">
                                    <!-- <transition name="slide-fade-left"> -->
                                        <aside class="post-meta">
                                            <span>Essaouira, Maroc</span>
                                        </aside>
                                    <!-- </transition> -->
                                </div>
                                <h2 class="entry-title">{{ $t($getContentLanguageKey(data.webPage.slug, 'headline', 'WebPage')) }}</h2>
                                <aside class="push-forward">{{ $t($getContentLanguageKey(data.webPage.slug, 'pushForward', 'WebPage')) }}</aside>
                                <p v-if="data.webPage.text">{{ $t($getContentLanguageKey(data.webPage.slug, 'text', 'WebPage')) }}</p>
                                <ul v-if="data.webPage.list">
                                    <li v-for="(elem, index) in data.webPage.list.slice(0,7)" :key="index">
                                        <a href="#">{{ $t($getContentLanguageKey(elem.slug, 'name', 'HotelService')) }}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="btn-container-wrapper">
                                <v-btn class="site-button btn-slide _button-sm" :to="localePath('contact')">
                                    <span>{{ $t($getFreeContentLanguageKey('Contacter-nous')) }}</span>
                                </v-btn>
                            </div>
                        </div>
                    </div>
                </transition>
                <div v-if="data.webPage.pushForward" class="photo-caption">
                    <div class="aside_wrapper">
                        <transition name="slide-fade-right">
                            <span v-if="show">{{ $t($getContentLanguageKey(data.webPage.category.slug, 'name', 'Tag')) }}</span>
                        </transition>
                    </div>
                    <p>{{ $t($getContentLanguageKey(data.webPage.slug, 'alternativeHeadline', 'WebPage')) }}</p>
                </div>
            </div>
            <div class="small-12 medium-9 large-9">
                <div class="service-container">
                    <SliderSwiperServiceFullScreen :params="mergedParams" :slides="slides" :page="data" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
import SliderSwiperServiceFullScreen from '~/components/theme-full-screen/components/SliderSwiperServiceFullScreen'
export default {
    name: 'PageServiceFullScreen',
    props: {
        params: {
            type: Object
        },
        data: {
            type: Object
        }
    },
    components: {
        SliderSwiperServiceFullScreen
    },
    computed: {
        ...mapState({
            slides: state => state.components.slider_swiper_pages.list,
            show: state => state.organizations.config.transition.show.slider,
            showColumn: state => state.organizations.config.transition.show.columnService
        }),
        mergedParams() {
            let preventClicksPropagation = true
            let allowTouchMove = (this.$device.isMobile)? true: false
            let speed = (this.$device.isMobile)? 100: 900
            let params = {
                options: {
                    "direction": 'vertical',
                    "speed": speed,
                    "mousewheel": true,
                    "effect": "slide",
                    "preventClicksPropagation": preventClicksPropagation,
                    "allowTouchMove": allowTouchMove
                }
            }

            return {...this.params, ...params}
        }
    },
    mounted() {
        this.$store.commit('organizations/setTransitionShowSlide', true)
    },
    methods: {
        getBackgroundImage: function (filename, format) {
          if(null !== filename) {

            return this.$getUrlBackgroundImage(filename, { "format": format })
          }

          return null
        }
    }
}
</script>

<style lang="scss">

.page-service-full-screen .column-service {
    width: 100%;
    height: 100%;
    position: absolute;
    top:0;
    left:0;
    z-index: 50;
}

.page-service-full-screen .column-service-container {
    width: 100%;
    height: 100%;
    // background: var(--color-primary);
    background: #fff;
}

.page-service-full-screen .wrapper-column {
    padding: 0;
}

.page-service-full-screen .card-padding
{
    padding: 80px 15px 20px 15px;
}

.page-service-full-screen h2 {
    text-transform: uppercase;
}

.page-service-full-screen .dark .column-service-container {
	width: 100%;
	height: 100%;
	background: var(--color-primary);
	color: #fff;
}

.page-service-full-screen .dark .column-service-container h2
, .page-service-full-screen .dark .column-service-container aside.post-meta span {
	color: #fff;
}

// .page-service-full-screen .column-service.dark .column-service-container a.btn-slide {
//     color:#fff !important;
//     border: 1px solid var(--color-secondary) !important;
// }
//
// .page-service-full-screen .column-service.dark .column-service-container a.btn-slide:hover {
//     border: 1px solid var(--color-secondary) !important;
//     color:#fff !important;
// }

.page-service-full-screen .column-service.dark .column-service-container .btn-slide span {
    color:#fff !important;
}

.page-service-full-screen .column-service.dark .column-service-container aside.post-meta span::before
, .page-service-full-screen .column-service.dark .column-service-container aside.post-meta span::after {
	background: #fff;
}

.page-service-full-screen aside.push-forward {
  color: var(--color-secondary);
  font-weight: 700;
}

.page-service-full-screen .description p {
	padding: 10px 2rem;
	height: 35vh;
	overflow: hidden;
	text-align: justify;
}

@media screen and (max-width: 40.0625em){
    .page-service-full-screen .column-service {
        display: none;
    }

    .page-service-full-screen p {
    	padding: 0 20px;
    	text-align: justify;
    }

    .page-service-full-screen blockquote p {
    	padding: 0 50px;
    	text-align: center;
    }
}
</style>
