export const actions = {
  async nuxtServerInit({ dispatch, commit, context , error }) {
    // 	await dispatch('team/getMainContact')
    
    await dispatch('organizations/getMain', { slug: 'villa-gonatouki' })
    await dispatch('hotels/getListActivities')
    await dispatch('hotels/getListServices')
    await dispatch('hotels/getListDayTypes')
    await dispatch('hotels/getListRooms')
    await dispatch('hotels/getListAmenities')

		  // await dispatch('organization/getComponents')
    //   await dispatch('organization/getListBy', { 'type.slug':'lien-reseau-social' })
    //   await dispatch('menu/getArticleNosServices', { 'tags.slug': 'nos-services' })
    //   await dispatch('footer/getRecentAccommodations')
    //   await dispatch('footer/getUsefulLinks')
    //   await dispatch('footer/getTags', { isActive: 'true', isLocation: 'false' })
    //   await dispatch('footer/getReviewLinks')
    //   await dispatch('footer/getArticle', { slug: 'lagence' })

  }
}