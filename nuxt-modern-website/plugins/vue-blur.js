// https://github.com/ndelvalle/v-blur
import Vue from 'vue'
import vBlur from 'v-blur'

Vue.use(vBlur)

// Alternatively an options object can be used to set defaults. All of these
// options are not required, example:
// Vue.use(vBlur, {
//   opacity: 0.2,
//   filter: 'blur(1.2px)',
//   transition: 'all .3s linear'
// })