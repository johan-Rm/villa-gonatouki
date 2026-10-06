export const state = () => ({
  item: {
    metaTitle: '',
    name: '',
    slug: ''
  },
})

export const mutations = {
  setItem(state, data) {
    state.item.metaTitle = data.metaTitle
    state.item.name = data.name
    state.item.slug = data.slug
  }
}

export const actions = {
  async getOneBy({commit , context}, params) {
    await this.$axios.get('/tags'
      , {
        params
      })
      .then((response) => {
        commit('setItem', response.data['hydra:member'][0])
      }).catch(error => {
          console.log(error)
          console.log('error store tags getOneBy')
        })
  }
}
