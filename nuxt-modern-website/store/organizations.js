export const state = () => ({
    item: {
      phone: '',
      name: '',
      email: '',
      url: '',
      addresses: [],
      primaryImage: {},
      foundingDate: '',
      numberOfProjects: 0
    },
    render: false,
    list:[],
    config: {
      theme: 'full-screen', //'default',
      bodyClass: 'light-theme _logo-light',
      transition: {
        show: {
          slider: false,
          breadcrumb: false,
          datePicker: false,
          columnService: false
        }
      },
      classContainerSideDatesOpen: "",
      classContainerSideBookingOpen: "",
      isContainerSideDatesOpen: false,
      isContainerSideBookingOpen: false,
      currentDayTypesName: {}
    }
})

export const mutations = {
    setTransitionShowColumnService(state, key) {
        var bool = false
        if(key != 0) {
            bool = true
        }
        state.config.transition.show.columnService = bool
    },
    setRender(state, data) {
      state.render = data
    },
    setTransitionShowSlide(state, data) {
      state.config.transition.show.slider = data
    },
    setTransitionShowBreadcrumb(state, data) {
      state.config.transition.show.breadcrumb = data
    },
    setTransitionShowDatepicker(state, data) {
      state.config.transition.show.datePicker = data
    },
    setConfigIsContainerSideDatesOpen(state, data) {
      state.config.isContainerSideDatesOpen = !data
      state.config.classContainerSideDatesOpen = ""
      if(false == data) {
        state.config.classContainerSideDatesOpen = "container-side-open"
      }
    },
    setConfigIsContainerSideBookingOpen(state, data) {
      state.config.isContainerSideBookingOpen = !data
      state.config.classContainerSideBookingOpen = ""
      if(false == data) {
        state.config.classContainerSideBookingOpen = "container-side-booking-open"
      }
    },
    setConfig(state, data) {
      state.config.theme = data.hasOwnProperty('theme')? data.theme: state.config.theme
      state.config.bodyClass = data.hasOwnProperty('bodyClass')? data.bodyClass: state.config.theme
    },
    setItem(state, data) {
      state.item.name = data.name
      state.item.phone = data.phone
      state.item.email = data.email
      state.item.url = data.url
      state.item.addresses = data.addresses
      state.item.primaryImage = data.primaryImage
      state.item.foundingDate = data.foundingDate
      state.item.numberOfProjects = data.numberOfProjects
    },
    setList(state, data) {
      state.list = []
      data.forEach(function (value, key) {
          let item = {
            url: value.url,
            slug: value.slug
          }
          state.list.push(item)
      })
  },
  setCurrentDayTypesName(state, data) {
    state.config.currentDayTypesName = data
  },

}

export const actions = {
   async getMain({commit}, params) {
    const slug = params.slug
    delete params.slug

    import('~/data/organizations.json').then((result) => {

          commit('setItem', result["hydra:member"].find(r => r.slug === slug))
      }).catch(error => {
          console.log(error)
          console.log('error store json organizations getMain')
      })
  },
    // async getItem({commit , context}) {
    //   import('~/data/organization.json').then((data) => {
    //         commit('setItem', data)
    //     }).catch(error => {
    //         console.log(error)
    //         console.log('error store organization getItem')
    //     })
    // },
    // async getListBy({commit , context}, params) {
    //   import('~/data/organization-lien-reseau-social.json').then((data) => {
    //         commit('setList', data['hydra:member'])
    //     }).catch(error => {
    //         console.log(error)
    //         console.log('error store organization getListBy')
    //     })
    // }
}
