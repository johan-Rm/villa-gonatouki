<template>
    <div class="page-rooms-full-screen">
        <SliderSwiperRoomsFullScreen :params="mergedParams" :data="data" />
    </div>
</template>

<script>
import { mapState } from 'vuex'
import SliderSwiperRoomsFullScreen from '~/components/theme-full-screen/components/SliderSwiperRoomsFullScreen'
export default {
    name: 'PageRoomsFullScreen',
    props: {
        params: {
            type: Object
        },
        data: {
            type: Object
        }
    },
    components: {
        SliderSwiperRoomsFullScreen
    },
    computed: {
        mergedParams() {
            var rooms = this.data.rooms
            let preventClicksPropagation = true
            let allowTouchMove = (this.$device.isMobile)? true: false
            let speed = (this.$device.isMobile)? 100: 900
            let params = {
                options: {
                    "direction": 'vertical',
                    "speed": speed,
                    "mousewheel": true,
                    "preventClicksPropagation": preventClicksPropagation,
                    "allowTouchMove": allowTouchMove
                }
            }

            return {...this.params, ...params}
        }
    },
    methods: {
        getImagePath: function (image) {
            if(null == image) {

                return null
            }

            return process.env.WEB_HOST + process.env.PATH_DEFAULT_MEDIA + image.filename
        }
    }
}
</script>

<style lang="scss">
.page-rooms-full-screen {
  height:100%;
}

.page-rooms-full-screen .card-page .page-content-top {
    height: 55vh;
    overflow: hidden;
}

.page-rooms-full-screen .card-page .page-content-bottom {
    height: 35vh;
    overflow: hidden;
}

@media screen and (max-width: 40.625em){

}

</style>
