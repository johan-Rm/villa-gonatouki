export const state = () => ({
  item: {
    image: '',
    category: '',
    title: '',
    description: '',
  },
  list: []
})

export const mutations = {
  setList(state, data) {
    state.list = []
    data.forEach(function (value, key) {
        let list = []
        if(value.hasOwnProperty('list')) {
          list = value.list
        }
        let item = {
          image: value.image,
          category: value.category,
          title: value.title,
          description: value.description,
          pushForward: value.pushForward,
          slug: value.slug,
          videos: value.videos,
          list: list
        }
        state.list.push(item)
    })
  },
  setListArticle(state, data) {
    state.list = []
    data.forEach(function (value, key) {
        let list = []
        if(value.hasOwnProperty('list')) {
          list = value.list
        }
        let item = {
          image: value.image,
          category: value.category,
          title: value.title,
          description: value.description,
          pushForward: value.pushForward,
          slug: value.slug,
          videos: value.videos,
          list: list
        }
        state.list.push({ 'article': item })
    })
  },
  setListActivity(state, data) {
    state.list = []
    data.forEach(function (value, key) {
        let list = []
        if(value.hasOwnProperty('list')) {
          list = value.list
        }
        let item = {
          image: value.image,
          category: value.category,
          title: value.title,
          description: value.description,
          pushForward: value.pushForward,
          slug: value.slug,
          videos: value.videos,
          list: list
        }
        state.list.push({ 'activity': item })
    })
  },
  setListByTwin(state, data) {
    state.list = []
    var array = []
    data.forEach(function(value, i){
      if (i % 2 === 1) {
        let items = []

        let value = data[i]
        let list = []
        if(value.hasOwnProperty('list')) {
          list = value.list
        }
        let currentItem = {
          image: value.image,
          category: value.category,
          title: value.title,
          description: value.description,
          list: list
        }

        let j = i-1
        value = data[j]
        list = []
        if(value.hasOwnProperty('list')) {
          list = value.list
        }
        let previousItem = {
          image: value.image,
          category: value.category,
          title: value.title,
          description: value.description,
          list: list
        }

        items.push(currentItem)
        items.push(previousItem)
        array.push(items)
      }
    })
    state.list = array
  },
  addList(state, data) {
    state.list.unshift(data)
  },
  setItem(state, data) {
    // a faire
    // state.item.image = data.metaTitle
    // state.item.category = data.metaDescription
    // state.item.title = data.url
    // state.item.description = data.name
  }
}
