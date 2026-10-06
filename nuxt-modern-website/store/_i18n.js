import * as types from './mutation-types'
import { ROUTES, DEFAULT_LOCALE, LOCALES } from '~/config/router'


// Default module's state
const state = () => ({
  locales: LOCALES,
  currentLocale: DEFAULT_LOCALE,
  defaultLocale: DEFAULT_LOCALE,
  routes: ROUTES
})

export const actions = {
  	setLocale ({ commit }, { locale }) {
    	commit(types.I18N_SET_LOCALE, { locale })
  	}
}

export const getters = {
  currentLocale: state => state.currentLocale
}

export const mutations = {
  [types.I18N_SET_LOCALE] (state, { locale }) {
    state.currentLocale = locale
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
