export default function ({ store }) {
	let data = {
		room: {}
	}
  	store.commit('hotels/setCurrentRoom', data)
  	if(true == store.state.organizations.config.isContainerSideDatesOpen) {
  		store.commit(
		  'organizations/setConfigIsContainerSideDatesOpen'
		  , store.state.organizations.config.isContainerSideDatesOpen
		)	
  	}
  	store.commit('organizations/setTransitionShowBreadcrumb', false)
  	store.commit('organizations/setTransitionShowSlide', false)
}

