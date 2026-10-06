<template>
<div class="card-contact-maps-full-screen">
    <google-map id="map" ref="Map" 
        :center="infoWindowContext.position" 
        :zoom="6" 
        :options="mapOptions"
    >
        <google-map-marker
          :position="infoWindowContext.position"
          @click="toggleInfoWindow(infoWindowContext)"
        />
        <!-- <google-map-infowindow
          :position="infoWindowContext.position"
          :show.sync="showInfo"
          :options="{ maxWidth: 300 }"
          @info-window-clicked="infoClicked"
        >
            <h4>{{ infoWindowContext.title }}</h4>
            <p>{{infoWindowContext.description}}</p>
        </google-map-infowindow> -->
    </google-map>
</div>  
</template>

<script>
import { mapState } from 'vuex'
export default {
    name:'CardContactMapsFullScreen',
    computed: {
        ...mapState({
            page: state => state.pages.item,
            organization: state => state.organizations.item
        })
    },
    data () {
        return {
            showInfo: false,
            infoWindowContext: {
                position: {
                  lat: 31.43451451956385,
                  lng: -9.759336726983388
                },
                title: 'Villa Gonatouki',
                description: 'description'
            },
            mapOptions: {
                zoomControl: false,
                mapTypeControl: false,
                scaleControl: false,
                streetViewControl: false,
                rotateControl: false,
                fullscreenControl: false,
                disableDefaultUi: false,
                styles: [
                //     { elementType: "geometry", stylers: [{ color: "#242f3e" }] },
                //     { elementType: "labels.text.stroke", stylers: [{ color: "#242f3e" }] },
                //     { elementType: "labels.text.fill", stylers: [{ color: "#746855" }] },
                //     {
                //     featureType: "administrative.locality",
                //     elementType: "labels.text.fill",
                //     stylers: [{ color: "#d59563" }],
                //     },
                //     {
                //     featureType: "poi",
                //     elementType: "labels.text.fill",
                //     stylers: [{ color: "#d59563" }],
                //     },
                //     {
                //     featureType: "poi.park",
                //     elementType: "geometry",
                //     stylers: [{ color: "#263c3f" }],
                //     },
                //     {
                //     featureType: "poi.park",
                //     elementType: "labels.text.fill",
                //     stylers: [{ color: "#6b9a76" }],
                //     },
                //     {
                //     featureType: "road",
                //     elementType: "geometry",
                //     stylers: [{ color: "#38414e" }],
                //     },
                //     {
                //     featureType: "road",
                //     elementType: "geometry.stroke",
                //     stylers: [{ color: "#212a37" }],
                //     },
                //     {
                //     featureType: "road",
                //     elementType: "labels.text.fill",
                //     stylers: [{ color: "#9ca5b3" }],
                //     },
                //     {
                //     featureType: "road.highway",
                //     elementType: "geometry",
                //     stylers: [{ color: "#746855" }],
                //     },
                //     {
                //     featureType: "road.highway",
                //     elementType: "geometry.stroke",
                //     stylers: [{ color: "#1f2835" }],
                //     },
                //     {
                //     featureType: "road.highway",
                //     elementType: "labels.text.fill",
                //     stylers: [{ color: "#f3d19c" }],
                //     },
                //     {
                //     featureType: "transit",
                //     elementType: "geometry",
                //     stylers: [{ color: "#2f3948" }],
                //     },
                //     {
                //     featureType: "transit.station",
                //     elementType: "labels.text.fill",
                //     stylers: [{ color: "#d59563" }],
                //     },
                    // {
                    // featureType: "water",
                    // elementType: "geometry",
                    // stylers: [{ color: "#001429" }],
                    // },
                //     {
                //     featureType: "water",
                //     elementType: "labels.text.fill",
                //     stylers: [{ color: "#515c6d" }],
                //     },
                //     {
                //     featureType: "water",
                //     elementType: "labels.text.stroke",
                //     stylers: [{ color: "#17263c" }],
                //     }
                ]
            }
        }
    },
    methods: {
        toggleInfoWindow(context) {
          this.infoWIndowContext = context
          this.showInfo = true
        },
        infoClicked(context) {
        //   console.log(context)
        }
    }
}
</script>
<style scoped>
.card-contact-maps-full-screen {
    height: 100%;
}
</style>
