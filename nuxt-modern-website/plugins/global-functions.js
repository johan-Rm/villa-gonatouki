import Vue from 'vue'
var pluralize = require('pluralize')
var slugify = require('slugify')

export default ({ app, store }, inject) => {
    inject('pluralize', pluralize)
    inject('slugify', slugify)
    inject('generateMetaData', (locale, key, entity) => {

      return {}
    })
    inject('generateStructuredData', (locale, key, entity) => {

      return {}
    })
    inject('generateBreadcrumb', (locale, key, entity) => {

      return {}
    })
    inject('getImageSizeByFilterSets', (orientation, format) => {
      let filterSets = store.state.organization.filterSets
      if(filterSets.hasOwnProperty(format)) {
         var sizes = filterSets[format].filters.thumbnail.size

        if('width'){

          return sizes[0]
        }
        if('height' == orientation && sizes.hasOwnProperty(1)) {

          return sizes[1]
        }
      }

      return null
    })
    inject('getImagePathByFilename', (filename, format, orientation) => {

        // orientation ce n'est pas nécéssaire je pense car plugin pour ça

        // let filename = image.filename

        // if(null !== image) {
        //     let format = 'team_square_medium'
        //     if('mobile' == device) {
        //       format = 'team_square_small'
        //     }

        //     let filename = image.filename
        //     if(!this.$device.isMacOS || !this.$device.iOS) {
        //       filename = filename.substr(0, filename.lastIndexOf('.'))
        //       filename = filename + '.webp'
        //     }

        return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + format + process.env.PATH_DEFAULT_MEDIA + filename
          // }

          // return null
    })
    inject('getImagePath', (image, format, orientation) => {

        // orientation ce n'est pas nécéssaire je pense car plugin pour ça

        let filename = image.filename

        // if(null !== image) {
        //     let format = 'team_square_medium'
        //     if('mobile' == device) {
        //       format = 'team_square_small'
        //     }

        //     let filename = image.filename
        //     if(!this.$device.isMacOS || !this.$device.iOS) {
        //       filename = filename.substr(0, filename.lastIndexOf('.'))
        //       filename = filename + '.webp'
        //     }

        return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + format + process.env.PATH_DEFAULT_MEDIA + filename
          // }

          // return null
    })
    inject('getUrlBackgroundImage', (filename, params) => {
        // C'est peut etre pas nécéssaire de modifier l'extension...
        // if(!app.$device.isMacOS || !app.$device.iOS) {
        //   filename = filename.substr(0, filename.lastIndexOf('.'))
        //   filename = filename + '.webp'
        // }

        var size = 'cover'
        if (params.hasOwnProperty('size')) {
          size = params.size
        }

        var repeat = 'no-repeat'
        if (params.hasOwnProperty('repeat')) {
          repeat = params.repeat
        }

        var position = 'center'
        if (params.hasOwnProperty('position')) {
          position = params.position
        }

        let url = process.env.URL_CDN + process.env.PATH_DEFAULT_MEDIA + filename
        if(params.hasOwnProperty('format')) {
          url = process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + params.format + process.env.PATH_DEFAULT_MEDIA + filename
        }

        return {
            'background-image': `url(${url})`,
            'background-repeat': repeat,
            'background-size': size,
            'background-position': position
        }
    })
    inject('generateBookingPrice', (locale, room, dates, numberOfRooms, addOn) => {
      // applique la réduction en fonction du nombre de semaine
      var nbNights = dates.length
      var quota = Math.floor(nbNights / 7)
      if(quota > 0) {
        dates.splice(0, quota)
      }
// console.log('generateBookingPrice')
      var price = 0
      room.offers.forEach(function(offer, i){
        var startDate = new Date(offer.availabilityStart)
        var endDate = new Date(offer.availabilityEnd)
        dates.forEach(function(date) {
          if (date.getTime() > startDate.getTime() && date.getTime() < endDate.getTime()) {
// console.log("room.offers.forEach")
// console.log(price)
            price = offer.price + price
            if(addOn <= i ) {
                price += offer.addOn
              }

            }
        })
      })

      if(numberOfRooms > 1) {
        price = price * numberOfRooms
      }
// console.log(price)
      return price
    })
    // Returns an array of dates between the two dates
    inject('getRangeDatesFrom2Dates', (startDate, endDate) => {
      var dates = [],
      currentDate = startDate,
      addDays = function(days) {
        var date = new Date(this.valueOf())
        date.setDate(date.getDate() + days)

        return date
      }

      while (currentDate <= endDate) {
        dates.push(currentDate)
        currentDate = addDays.call(currentDate, 1)
      }

      return dates
    })
    inject('getRandomInt', (max) => {
      return Math.floor(Math.random() * Math.floor(max));
    })
    inject('sortArrayByTwo', (data) => {
      var arrayByTwo = []
      data.forEach(function(slide, i){
        if (i % 2 === 1) {
          let items = []
          let currentItem = data[i]
          let j = i-1
          let previousItem = data[j]
          items.push(currentItem)
          items.push(previousItem)
          arrayByTwo.push(items)
        }
      })

      return arrayByTwo
    })
    inject('getContentLanguageKey', (slug, fieldName, entityName) => {

      return [ app.i18n.locale, fieldName.toLowerCase(), entityName.toLowerCase(), slug ].join('.')
    })
    inject('getFreeContentLanguageKey', (string) => {
      let locale = app.i18n.locale
      let fieldName = 'text'
      let entityName = 'CreativeWork'
      let slug = slugify(string, { replacement: '-',lower: true })

      return [ locale, fieldName, entityName.toLowerCase(), slug ].join('.')
    })
}
