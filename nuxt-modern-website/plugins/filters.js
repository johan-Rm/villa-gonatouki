import Vue from 'vue'

export function truncate(string, value, more = true) {
    if(string.length > value){
        string = (string || '').substring(0, value)
        const lastIndex = string.lastIndexOf(' ')

        return (more)? string.substring(0, lastIndex) + ' […]': string.substring(0, lastIndex)
    }
    else{

        return string
    }
}
Vue.filter('truncate', truncate)

export function camelCase(string) {
    return string.toLowerCase()
        .replace( /[-_]+/g, ' ')
        .replace( /[^\w\s]/g, '')
        .replace( / (.)/g, function($1) { return $1.toUpperCase(); })
        .replace( / /g, '' )
}
Vue.filter('camelCase', camelCase)

export function addColor(string) {
    return '<span class="color-red">' + string + '</span>'
}
Vue.filter('addColor', addColor)

export function ucFirst(string) {

    return string.charAt(0).toUpperCase() + string.slice(1);
}
Vue.filter('ucFirst', ucFirst)
