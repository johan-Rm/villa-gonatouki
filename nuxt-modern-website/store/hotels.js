import * as types from './mutation-types'

export const state = () => ({
    rooms: [],
    room: {},
    privatization_offers: [],
    day_types: [],
    activities: [],
    activitiesWithoutImages: {},
    services: [],
    amenities: [],
    current: {
        room: {},
        day_type: [],
        activity: [],
        service: [],
        amenitie: []
    },
    booking: {
        person: {},
        rooms: [],
        room: {},
        datesOfStay: {},
        totalPrice: 0,
        numberOfAdults: 1,
        numberOfChildren: 1,
        details: ""
    }
})

export const mutations = {
    setBookingTotalPrice(state, data) {
        state.booking.totalPrice = data
    },
    setBookingPerson(state, data) {
        state.booking.person = data
    },
    setBookingDetails(state, data) {
        state.booking.details = data
    },
    setBookingDatesOfStay(state, data) {
        state.booking.datesOfStay = data
    },
    setBookingNumberOfAdults(state, data) {
        state.booking.numberOfAdults = data
    },
    setBookingNumberOfChildren(state, data) {
        state.booking.numberOfChildren = data
    },
    setBookingRoom(state, data) {
        state.booking.room = data
    },
    setBookingRoomNumberOfRooms(state, data) {
        state.booking.room.numberOfRooms = data
    },
    setBookingRoomsPrice(state, data) {
        // state.booking.rooms.push(data)
    },
    setBookingRooms(state, data) {
        let index = state.booking.rooms.findIndex(r => r.slug === data.slug)
        // const index = array.indexOf(5);
        if (index > -1) {
          state.booking.rooms.splice(index, 1);
        }
        state.booking.rooms.push(data)
    },
    addBookingRooms(state, data) {
        let index = state.booking.rooms.findIndex(r => r.slug === data.slug)
        if(-1 === index) {
            state.booking.rooms.push(data)
        }
    },
    resetBooking(state, data) {
        state.booking = {
            person: {},
            rooms: [],
            room: {},
            datesOfStay: {},
            totalPrice: 0,
            numberOfAdults: 1,
            numberOfChildren: 1,
            details: ""
        }
    },
    setRoom(state, data) {
        state.room = data
    },
    setRoomNumberOfRooms(state, data) {
        state.room.numberOfRooms = data
    },
    setRoomsPrice(state, data) {
        let index = state.rooms.findIndex(r => r.slug === data.slug)
        if(-1 === index) {
            let index = state.privatization_offers.findIndex(r => r.slug === data.slug)
            state.privatization_offers[index].price = data.price
        } else {
            state.rooms[index].price = data.price
        }
    },
    setRoomPrice(state, data) {
        state.room.price = data
    },
    removeBookingRooms(state, slug) {
        let index = state.booking.rooms.findIndex(r => r.slug === slug)
        state.booking.rooms.splice(index, 1)
    },
    setCurrentRoom(state, data) {
        state.current.room = data.room
    },
    setListDayTypes(state, data) {
        state.day_types = data
    },
    setListRooms(state, data) {
        state.rooms = data.map((v,k) => ({...v, index: k}))
    },
    setPrivatizationOffers(state, data) {
        state.privatization_offers = data
    },
    setListActivities(state, data) {
        state.activities = data
    },
    setListActivitiesWithoutImages(state, data) {
        state.activitiesWithoutImages = data
    },
    setListServices(state, data) { // services
        state.services = data
    },
    setListAmenities(state, data) { // équipements
        state.amenities = data
    },
    setHotelServices(state, data) {
        let results = data.reduce((r, a) => {
            r[a.category.slug] = [
                ...r[a.category.slug] || [],
                a
            ]

            return r
        }, {})

        var slides = []
        var i = 0
        Object.keys(results).forEach(key => {
            slides[i] = {

                'headline' : key,
                'list' : results[key]
            }

            i = i + 1
        })

        state.services = slides
    }
}

const getActivities = () => import('~/data/hotel_activities.json').then(r => r.default || r)
const getServices = () => import('~/data/hotel_services.json').then(r => r.default || r)
const getAmenities = () => import('~/data/hotel_amenities.json').then(r => r.default || r)
const getTypicalDays = () => import('~/data/hotel_typical_days.json').then(r => r.default || r)
const getRooms = () => import('~/data/rooms.json').then(r => r.default || r)
const getPrivatizationOffers = () => import('~/data/rooms.json').then(r => r.default || r)

export const actions = {
    async getListServices({commit}) {
        if('API' === types.DATA_TYPE) {
          await this.$axios.get('/hotel_services').then((response) => {
          }).catch(error => {
              console.log(error)
              console.log('error store api services getHotelServices')
            })
        } else {
            const results = await getServices()
            var data = results["hydra:member"]
            if(typeof data === 'undefined') {
              console.log('error store json hotels getHotelServices => filter()')

              return false
            }
            commit('setHotelServices', data)
        }
    },
    async getListAmenities({commit , context}) {
        if('API' === types.DATA_TYPE) {
            await this.$axios.get('/hotel_amenities')
                .then((response) => {
                    commit('setListAmenities', response.data["hydra:member"])
                }).catch(error => {
                    console.log(error)
                    console.log('error store api hotel getListAmenities')
                }
            )
        } else {
            const results = await getAmenities()
            var data = results["hydra:member"]
            if(typeof data === 'undefined') {
              console.log('error store json hotels getListAmenities => filter()')

              return false
            }
            commit('setListAmenities', data)
        }
    }, // end async getListAmenities
    async getListActivities({commit , context}) {
        if('API' === types.DATA_TYPE) {
            await this.$axios.get('/hotel_activities')
                .then((response) => {
                    commit('setListActivities', response.data["hydra:member"])
                }).catch(error => {
                    console.log(error)
                    console.log('error store api hotel getListActivities')
                }
            )
        } else {
            const results = await getActivities()
            var data = results["hydra:member"].filter(r => (
                    null !== r.description
                    && null !== r.primaryImage
                )
            )
            if(typeof data === 'undefined') {
              console.log('error store json hotels getListActivities => filter()')

              return false
            }
            commit('setListActivities', data)
        }
    }, // end async getListActivities
    async getListActivitiesWithoutImages({commit , context}) {
        if('API' === types.DATA_TYPE) {
            await this.$axios.get('/hotel_activities')
                .then((response) => {
                    commit('setListActivities', response.data["hydra:member"])
                }).catch(error => {
                    console.log(error)
                    console.log('error store api hotel getListActivitiesWithoutImages')
                }
            )
        } else {
            const results = await getActivities()
            var data = results["hydra:member"].filter(r => (
                    null === r.description
                    || null === r.primaryImage
                )
            )
            if(typeof data === 'undefined') {
              console.log('error store json hotels getListActivitiesWithoutImages => filter()')

              return false
            }
            commit('setListActivitiesWithoutImages', data)
        }
    }, // end async getListActivitiesWithoutImages
    async getListDayTypes({commit, context}) {
        if('API' === types.DATA_TYPE) {
            await this.$axios.get('/hotel_typical_days/2')
                .then((response) => {
                    commit('setListDayTypes', response.data)
                }).catch(error => {
                    console.log(error)
                    console.log('error store api hotel getListDayTypes')
                }
            )
        } else {
            const results = await getTypicalDays()
            var data = results["hydra:member"]
            if(typeof data === 'undefined') {
              console.log('error store json hotels getListDayTypes => filter()')

              return false
            }
            commit('setListDayTypes', data)
        }

    }, // end async getListDayTypes
    async getListRooms({commit , context}) {
        if('API' === types.DATA_TYPE) {
            await this.$axios.get('/rooms')
                .then((response) => {
                    commit('setListRooms', response.data["hydra:member"])
                }).catch(error => {
                    console.log(error)
                    console.log('error store api hotel getListRooms')
                }
            )
        } else {
            const results = await getRooms()
            var data = results["hydra:member"].filter(r => (
                    'villa' !== r.category.slug
                )
            )
            if(typeof data === 'undefined') {
              console.log('error store json hotels getListRooms => filter()')

              return false
            }
            commit('setListRooms', data)
        }

    },
    async getPrivatizationOffers({commit , context}) {
        if('API' === types.DATA_TYPE) {
            await this.$axios.get('/rooms')
                .then((response) => {
                    commit('setPrivatizationOffers', response.data["hydra:member"])
                }).catch(error => {
                    console.log(error)
                    console.log('error store api hotel getPrivatizationOffers')
                }
            )
        } else {
            const results = await getPrivatizationOffers()
            var data = results["hydra:member"].filter(r => (
                    'villa' === r.category.slug
                )
            )
            // console.log('setPrivatizationOffers')
            // console.log(data)

            if(typeof data === 'undefined') {
              console.log('error store json hotels getPrivatizationOffers => filter()')

              return false
            }
            commit('setPrivatizationOffers', data)
        }
    }
} // actions
