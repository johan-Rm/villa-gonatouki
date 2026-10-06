<template>
    <div class="card-default card-default-full-screen">
        <div v-if="params.direction === 'right' || $device.isMobile"
            class="row align-center no-padding p-right"
            :id="params.id"
            :style="getBackgroundImage(data.image.filename, 'large')"
            @click="slideShow()"
        >
            <transition name="slide-fade-bottom">
                <div v-show="!isSlideShow && $device.isMobile" class="overlay_area">
                    <div class="photo-caption bottom">
                        {{data.image.id }}
                        <span v-if="data.image.alt">{{ $t(this.$getContentLanguageKey(data.image.slug, 'alt', 'MediaObject')) }}</span>
                        <span v-else>{{ $t(this.$getContentLanguageKey(data.image.slug, 'name', 'MediaObject')) }}</span>
                    </div>
                </div>
            </transition>
            <div class="bg-area small-12 medium-8 large-9">
                <transition :name="slideName('left')">
                    <div v-show="isSlideShow && data.image && !$device.isMobile" class="overlay_area">
                        <div class="photo-caption">
                            <span v-if="data.image.alt">{{ $t(this.$getContentLanguageKey(data.image.slug, 'alt', 'MediaObject')) }}</span>
                            <span v-else>{{ $t(this.$getContentLanguageKey(data.image.slug, 'name', 'MediaObject')) }}</span>
                        </div>
                    </div>
                </transition>
                <template v-if="data.videos">
                    <div
                        
                        class="video_container"
                        v-for="(video, index) in getVideos"
                        :key="index"
                    >
                        <div v-if="video.isActive" class="video_player">
                            <div class="">
                                <video-player
                                :src="video.url"
                                />
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <transition :name="slideName('right')">
            <div v-show="isSlideShow"
                @click="blockSlideShow($event)"
                class="small-12 medium-4 large-3 wrapper-column wrapper-column-left"
                :class="{ 'expanded-content': expandedContent }"
            >
                <div class="card-padding description text-center">

                    <div class="aside_wrapper">
                        <transition name="slide-fade-left">
                            <aside v-if="show" class="post-meta"><span>{{ $t(this.$getContentLanguageKey(data.category.slug, 'name', 'Tag')) }}</span></aside>
                        </transition>
                    </div>

                    <h2 class="entry-title">{{ $t(this.$getContentLanguageKey(data.slug, 'headline', 'Article')) }}</h2>
                    <div v-if="!$device.isMobile" class="description-content-expanded">
                        <aside v-if="data.pushForward" class="push-forward">{{ $t(this.$getContentLanguageKey(data.slug, 'pushForward', 'Article')) | truncate(30, false) }}</aside>
                        <p v-if="data.description && data.videos.length == 0">{{ $t(this.$getContentLanguageKey(data.slug, 'text', 'Article')) }}</p>
                    </div>
                    <ul v-if="data.list.length > 0">
                        <li v-for="(elem, index) in data.list.slice(0,7)" :key="index">
                            <a href="#">{{ elem.name }}</a>
                        </li>
                    </ul>

                    <ul class="menu-videos" v-if="data.videos.length > 0">
                        <li
                            v-for="(video, index) in getVideos"
                            :key="index"
                            @click="showVideo(video)"
                            :class="{ active: video.isActive }"
                        >
                            <span>{{ video.slug }}</span>
                        </li>
                    </ul>
                    <div v-if="!expandedContent && data.description && data.videos.length == 0"
                        class="description_short"
                    >
                        <p>
                            {{ $t(this.$getContentLanguageKey(data.slug, 'text', 'Article')) | truncate(120) }}
                        </p>
                        <div class="more" @click="expandedContent = true">
                            <v-icon aria-hidden="false">
                                mdi-chevron-up
                            </v-icon>
                        </div>
                    </div>
                    <div v-else-if="expandedContent && data.description && data.videos.length == 0"
                        class="description_expanded"
                    >
                        <p>
                            {{ $t(this.$getContentLanguageKey(data.slug, 'description', 'Article')) }}
                        </p>
                        <div class="more" @click="expandedContent = false">
                            <v-icon aria-hidden="false">
                                mdi-chevron-down
                            </v-icon>
                        </div>
                    </div>




                    <!-- <p v-if="data.description">{{ $t(data.description) }}</p> -->
                </div>
                <div class="btn-container-wrapper">
                    <v-btn v-if="'mariage' == data.slug" class="site-button btn-slide _button-sm" :to="localePath('wedding')">
                        <span>{{ $t($getFreeContentLanguageKey('Plus d\'infos')) }}</span>
                    </v-btn>
                    <v-btn v-else @click="openSideContainer(defaultRoomSlug)"
                    class="site-button btn-slide _button-sm">
                        <span>{{ $t($getFreeContentLanguageKey('Réserver')) }}</span>
                    </v-btn>
                </div>
            </div>
            </transition>
        </div>
        <div v-else
        @click="slideShow()"
        class="row align-center no-padding p-left"
        :id="params.id"
        :style="getBackgroundImage(data.image.filename, 'large')"
        >
            <transition name="slide-fade-bottom">
                <div v-show="!isSlideShow && $device.isMobile" class="overlay_area">
                    <div class="photo-caption bottom">
                        <span v-if="data.image.alt">{{ $t(this.$getContentLanguageKey(data.image.slug, 'alt', 'MediaObject')) }}</span>
                        <span v-else>{{ $t(this.$getContentLanguageKey(data.image.slug, 'name', 'MediaObject')) }}</span>
                    </div>
                </div>
            </transition>
            <transition :name="slideName('left')">
            <div v-show="isSlideShow"
            @click="blockSlideShow($event)"
            class="small-12 medium-4 large-3 wrapper-column wrapper-column-right"
            :class="{ 'expanded-content': expandedContent }"
            >
                <div class="card-padding description text-center">
                    <div class="aside_wrapper">
                        <transition name="slide-fade-left">
                            <aside v-if="show" class="post-meta"><span>{{ $t(this.$getContentLanguageKey(data.category.slug, 'name', 'Tag')) }}</span></aside>
                        </transition>
                    </div>
                    <h2 class="entry-title">{{ $t(this.$getContentLanguageKey(data.slug, 'headline', 'Article')) }}</h2>
                    <div class="description-content-expanded">
                        <aside v-if="data.pushForward" class="push-forward">{{ $t(this.$getContentLanguageKey(data.slug, 'pushForward', 'Article')) | truncate(30, false) }}</aside>
                        <p v-if="data.description && data.videos.length == 0">{{ $t(this.$getContentLanguageKey(data.slug, 'text', 'Article')) }}</p>
                    </div>
                    <!-- <ul v-if="data.list.length > 0">
                        <li v-for="(elem, index) in data.list.slice(0,7)" :key="index">
                            <a href="#">{{ elem.name }}</a>
                        </li>
                    </ul> -->
                    <ul class="menu-videos" v-if="data.videos.length > 0">
                        <li
                            v-for="(video, index) in getVideos"
                            :key="index"
                             @click="showVideo(video)"
                             :class="{ active: video.isActive }"
                        >
                            <span>{{ video.slug }}</span>
                        </li>
                    </ul>
                </div>
                <div class="btn-container-wrapper">
                    <v-btn v-if="'mariage' == data.slug" class="site-button btn-slide _button-sm" :to="localePath('wedding')">
                        <span>{{ $t($getFreeContentLanguageKey('Plus d\'infos')) }}</span>
                    </v-btn>
                    <v-btn v-else @click="openSideContainer(defaultRoomSlug)"
                    class="site-button btn-slide _button-sm">
                        <span>{{ $t($getFreeContentLanguageKey('Réserver')) }}</span>
                    </v-btn>
                </div>
            </div>
            </transition>
            <div class="bg-area small-12 medium-8 large-9">
                <transition :name="slideName('right')">
                    <div v-show="isSlideShow && data.image && !$device.isMobile" class="overlay_area">
                        <div class="photo-caption">
                            <span v-if="data.image.alt">{{ $t(this.$getContentLanguageKey(data.image.slug, 'alt', 'MediaObject')) }}</span>
                            <span v-else>{{ $t(this.$getContentLanguageKey(data.image.slug, 'name', 'MediaObject')) }}</span>
                        </div>
                    </div>
                </transition>
                <template v-if="data.videos.length > 0">
                    <div
                        class="video_container"
                        v-for="(video, index) in getVideos"
                        :key="index"
                    >
                        <div v-if="video.isActive" class="video_player">
                            <div class="">
                                <video-player
                                :src="video.url"
                                />
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import { EventBus } from '~/plugins/event-bus.js'
import { mapState } from 'vuex'
export default {
  name: 'CardDefaultFullScreen',
  props: {
    params: {
        type: Object
    },
    data: {
        type: Object
    }
  },
  computed: {
    ...mapState({
      show: state => state.organizations.config.transition.show.slider,
      isContainerSideBookingOpen: state => state.organizations.config.isContainerSideBookingOpen,
      defaultRoomSlug: state => state.hotels.rooms[0].slug
    }),
    getVideos() {
        this.data.videos.forEach((element, index) => {
            let bool = false
            if(index == 0) {
                bool = true
            }
            this.videos.push({
                ...element
                , isActive: bool
            })
        })

        return this.videos
    }
  },
  data() {

      return {
          isSlideShow: true,
          videos: [],
          expandedContent: false
      }
  },
  mounted() {
    this.$store.commit('organizations/setTransitionShowSlide', true)
    EventBus.$on('slide-change', data => {
      this.isSlideShow = true
    })
  },
  methods: {
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
      showVideo(video){
        this.videos.forEach(video => {
            video.isActive =  false
        })
        video.isActive = !video.isActive
      },
      getBackgroundImage: function (filename, format) {
          if(null !== filename) {

            return this.$getUrlBackgroundImage(filename, { "format": format, "size": 'cover' })
          }

          return null
      },
      blockSlideShow(event) {
        if (event) {
            event.stopPropagation()
        }
      },
      slideShow() {
          if(this.data.videos.length > 0) {
              this.isSlideShow = true
          } else{
              this.isSlideShow = !this.isSlideShow
          }
      },
      slideName(direction) {
          if(this.$device.isMobile) {

                return 'slide-fade-bottom'
          }

          return 'slide-fade-' + direction
      }
  }
}
</script>

<style lang="scss" scoped>

.card-default, .card-default .row
{
  height: 100%;
}

.card-default .card-padding
{
  padding: 80px 15px 20px 15px;
  height: 100%;
}

.card-default .bg-area, .card-default .wrapper-column
{
    position: relative;
    cursor:pointer;
}

.card-default .wrapper-column
{
  position: relative;
  height: 100%;
  background: #fff;
  // padding: 30px 0 0 0;
}

.card-default .wrapper-column .description-content-expanded
{
  max-height: 50%;
  overflow-y: scroll;
}

.card-default .wrapper-column a.btn-half
{
    position: absolute;
    bottom: 25px;
}

.card-default .wrapper-column-left
, .card-default .wrapper-column-right {
        cursor: auto;
}

.card-default .wrapper-column-left a.btn-half
{
  left: 30%;
}

.card-default .wrapper-column-right a.btn-half
{
  right: 30%;
}

.card-default h2 {
        text-transform: uppercase;
}

.card-default .aside_wrapper {
  height: 25px;
}

.card-default aside.push-forward {
  color: var(--color-secondary);
  font-weight: 700;
  padding: 10px 2rem;
}

.card-default .btn-slide {
  position: absolute;
  bottom: 20px;
  right: 25%;
}

.card-default .description p {
  padding: 10px 2rem;
//   height: 48vh;
  overflow: hidden;
  text-align: justify;
}


.card-default .photo-caption.bottom {
        position: absolute;
        bottom: 15px;
        color: #fff;
        z-index: 50;
        margin: 0;
        text-align: center;
        width: 100%;
}

.card-default .bg-area .photo-caption {
        position: absolute;
        bottom: 15px;
        color: #fff;
        z-index: 50;
        margin: 0;
}

.card-default .p-right .bg-area .photo-caption {
        right: inherit;
        left:15px;
        text-align: left;
}

.card-default .p-left .bg-area .photo-caption {
        left: inherit;
        right:15px;
        text-align: right;
}

.card-default .overlay_area {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 19;
  background:
  linear-gradient(
    to bottom,
    rgba(0, 0, 0, 0),
    rgba(0, 0, 0, 0.3)
  );
}

.card-default .video_container {
    width: 100%;
    height: 100%;
    background: var(--color-primary);
    display: table;
}

.card-default .video_player {
    display: table-cell;
    vertical-align: middle;
}

.card-default .video_player div {
    max-width: 80%;
    max-height: 50%;
    background: var(--color-primary);
    margin: 0 auto;
    border-radius: 0;
}

.card-default .video_player .v-player
, .card-default .video_player .v-player #v-player-iframe
{
    border-radius: 0 !important;
}

.card-default ul.menu-videos li {
    cursor: pointer;
}

.card-default ul.menu-videos li.active span {
    color: var(--color-secondary);
}

.card-default ul
{
  text-align: left;
  position: relative;
  list-style-type: none;
  margin-top: 30px;
  // display: flex;
  // flex-wrap: wrap;
  // justify-content: space-between;
  padding: 0 45px;
}


.card-default ul li
{
  // border-top: 1px solid var(--color-primary-rgba-30);
  // width: calc(50% - 20px);
  margin-bottom: 5px;
  font-size: 0.9rem;
  text-transform: lowercase;
}
.card-default  ul li::before {
  content: "\2713";
  color: var(--color-secondary);
  font-weight: bold;
  display: inline-block;
  width: 1em;
  margin-left: -1em;
  padding-right: 5px;
}

.card-simple  ul li::before {
  // content: "\2713";
  // color: var(--color-secondary);
  // font-weight: bold;
  // display: inline-block;
  // width: 1em;
  // margin-left: -1em;
  // padding-right: 5px;
}

@media screen and (max-width: 40.0625em){
    .card-default-full-screen > .row > .large-3 {
        height: 50vh;
        // max-height: 90vh;
        position: absolute;
    	bottom: 0;
        transition: all 0.25s ease-in;
    }

    .card-default-full-screen > .row > .large-3.expanded-content {
        // min-height: 50vh;
        height: 90vh;
    	position: absolute;
    	bottom: 0;
        transition: all 0.25s ease-out;
    }

    .card-default-full-screen > .row > .large-9 {
        height: 50vh;
    }

    .card-default .card-padding {
      padding: 30px 15px 20px 15px;
    }

    .card-default .description p {
        padding: 10px;
        height: auto;
    }

    .card-default .description .more {
        color: var(--color-primary);
        font-weight: 600;
        text-decoration: underline;
        cursor: pointer;
    }

    .card-default .description .description_short {

    	height: 22vh;
    	overflow: hidden;
    }

    .card-default .description .description_expanded {
    	height: 60vh;
        overflow: hidden;
    }

    .card-default .photo-caption {
    	padding: 0;
    }

    .card-default .card-padding {
    	padding: 15px 15px 10px 15px;
    }

    .card-default h2 {
    	margin-bottom: 10px;
    }
}

</style>
