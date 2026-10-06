<template>
    <div class="page-about-full-screen">
        <div class="card-page row no-padding align-center">
            <div v-if="!$device.isMobile"
                class="small-12 medium-3 large-3  wrapper-column wrapper-column-right"
                :style="getBackgroundImage('piscines-img-31821-vertical.jpg', 'large')"
            >
                <div class="overlay_area"></div>
                <div v-if="webPage.pushForward" class="photo-caption">
                    <div class="aside_wrapper">
                        <transition name="slide-fade-right">
                            <span v-if="show">{{ $t($getContentLanguageKey(webPage.category.slug, 'name', 'Tag')) }}</span>
                        </transition>
                    </div>
                    <p>{{ $t($getContentLanguageKey(webPage.slug, 'pushForward', 'WebPage')) }}</p>
                </div>
            </div>
            <div class="small-12 medium-9 large-9 ">
                <div class="about-container">
                    <div class="_page-content-padding description text-center">
                        <div class="aside_wrapper">
                            <transition name="slide-fade-left">
                                <aside v-if="show" class="post-meta"><span>{{ $t($getContentLanguageKey(webPage.slug, 'alternativeHeadline', 'WebPage')) }}</span></aside>
                            </transition>
                        </div>
                        <h2 class="entry-title">{{ $t($getContentLanguageKey(webPage.slug, 'headline', 'WebPage')) }}</h2>
                        <blockquote v-if="webPage.blockquote">
                            <p>“{{ $t($getContentLanguageKey(webPage.slug, 'blockquote', 'WebPage')) }}”</p>
                        </blockquote>
                        <div v-if="!$device.isMobile" class="description-container row no-padding align-justify">
                            <div class="small-12 medium-6 large-6 p-left">
                                <p v-for="(value, index) in startText(webPage.slug)" :key="index" v-html="value"></p>
                            </div>
                            <div class="small-12 medium-6 large-6 p-right">
                                <p v-for="(value, index) in endText(webPage.slug)" :key="index" v-html="value"></p>
                            </div>
                        </div>
                        <div v-else class="description-container description-container-mobile row no-padding align-justify">
                            <div class="small-12 medium-6 large-6 p-left">
                                <p v-for="(value, index) in fullText(webPage.slug)" :key="index" v-html="value"></p>
                            </div>
                        </div>
                    </div>
                    <div class="btn-container-wrapper">
                        <v-btn @click="nextSlide(params.index, params.origin)" class="site-button btn-slide _button-sm">
                            <span>{{ $t($getFreeContentLanguageKey('Explore')) }}</span>
                            <!-- <span>{{ $t('Explore') }}</span> -->
                        </v-btn>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { EventBus } from '~/plugins/event-bus.js'
import { mapState } from 'vuex'

export default {
    name:'AboutFullScreen',
    props: {
        params: {
            type: Object,
            default: () => ({
                bg_primary: 'bg-primary'
            })
        },
        data: {
            type: Object
        }
    },
    computed: {
        ...mapState({
            show: state => state.organizations.config.transition.show.slider
        }),
        webPage() {
            return this.data
        }
    },
    mounted() {

        this.$store.commit('organizations/setTransitionShowSlide', true)
    },
    methods:{
        nextSlide: function (index, origin) {
          const data = { "slide": 1, "origin": origin }
          EventBus.$emit('next-slide', data)
        },
        fullText(slug) {
            let text = this.$i18n.t(this.$getContentLanguageKey(slug, 'text', 'WebPage'))
            const array = text.split('|#|')

            return array
        },
        startText(slug) {
            let text = this.$i18n.t(this.$getContentLanguageKey(slug, 'text', 'WebPage'))
            const array = text.split('|#|')

            return array.slice(0,2)
        },
        endText(slug) {
            let text = this.$i18n.t(this.$getContentLanguageKey(slug, 'text', 'WebPage'))
            const array = text.split('|#|')

            return array.slice(2,4)
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
<style scoped>

.page-about-full-screen {
    /* height: 100vh; */
    height: 100%;
}

.about-container {
    /* height: 100vh; */
    height: 100%;
    position: relative;
    background: var(--color-primary);
}

.about-container .row, .page-about-full-screen .card-page
{
  height: 100%;
}

.about-container .description h2 {
    /*margin-bottom: 50px !important;*/
}

.about-container .align-justify
{
    text-align: justify;
    padding: 0px;
}

.page-about-full-screen p {
    color: #fff;
}

.page-about-full-screen .photo-caption p {
  padding: 0 50px;
}

.page-about-full-screen .description {
    /* padding-left: 15px;
    padding-right: 15px; */
}

.page-about-full-screen .btn-slide
{
    position: absolute;
    right: 44%;
    bottom: 25px;
}

.page-about-full-screen .description-container  {

    /* padding-left: 30px;
    padding-right: 30px; */
}

.page-about-full-screen .description-container > div p {
    margin-bottom: 10px;
    padding-left: 15px;
    padding-right: 15px;
    font-size: 1rem;
}

.page-about-full-screen .description-container > div.p-left p {
    padding-left: 30px;
}

.page-about-full-screen .description-container > div.p-right p {
    padding-right: 30px;
}

.page-about-full-screen blockquote p {
  color: var(--color-secondary);
}

@media screen and (max-width: 40.0625em){
    .page-about-full-screen > .row > .large-3 {
        height: 25vh;
    }
    .page-about-full-screen > .row > .large-9 {
        /* height: 75vh; */
        height: 100%;
    }

    .page-about-full-screen .description-container  {
        overflow: hidden;
        max-height: 60vh;
    }

    .page-about-full-screen .description-container > div.p-left p {
    	padding: 0 30px;
    }

    .page-about-full-screen .description-container > div.p-right p {
    	padding: 0 30px;
    }

    .page-about-full-screen .description-container > div {
        padding: 0px 0;
    }

    .about-container .description-container {
	       padding: 0px;
    }

}

</style>
