<template>
  <div class="color-mode-picker">
    <ul>
      <li v-for="color of colors" :key="color">
        <!-- <component :is="`icon-${color}`"  @click="$colorMode.preference = color" :class="getClasses(color)"/> -->
      </li>
    </ul>
    <ColorScheme placeholder="..." tag="span">
      Ambiance (test) : <b>{{ $colorMode.preference }}</b>
      <!-- <span style="display:inline-block; font-style: italic; font-size: 10px; font-weight: bold;" v-if="$colorMode.preference === 'system'"><i>- {{ $colorMode.value }}</i> mode detected</span> -->
    </ColorScheme>
  </div>
</template>
<script>
// import IconSystem from '@/assets/icons/system.svg?inline'
// import IconLight from '@/assets/icons/light.svg?inline'
// import IconInversed from '@/assets/icons/dark.svg?inline'
// import IconGrainesDigitales from '@/assets/icons/sepia.svg?inline'
export default {
  name: 'TheColorPicker',
  components: {
    // IconSystem,
    // IconLight,
    // IconInversed,
    // IconGrainesDigitales
  },
  data () {
    return {
      colors: ['system', 'light', 'inversed', 'graines-digitales']
    }
  },
  methods: {
    getClasses (color) {
      // Does not set classes on ssr when preference is system (because we don't know the preference until client-side)
      if (this.$colorMode.unknown) {
        return {}
      }
      return {
        preferred: color === this.$colorMode.preference,
        selected: color === this.$colorMode.value
      }
    }
  }
}
</script>
<style type="text/css" scoped>
.color-mode-picker {
  font-size: 12px;
  color: var(--color-secondary);
  text-align: center;
  padding: 10px 0;
}

.color-mode-picker ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.color-mode-picker ul li {
  display: inline-block;
  padding: 5px;
  cursor: pointer;
}

.color-mode-picker p {
  margin: 0;
  padding-top: 5px;
  padding-bottom: 20px;
}

.color-mode-picker {
  display: none;
}

.feather {
  color: var(--color-secondary);
}

.feather.selected {
  color: #fff;
}

/*********************************************************
***************** RESPONSIVE BREAKPOINTS *****************
*** https://getbootstrap.com/docs/4.1/layout/overview/ ***
**********************************************************/
@media screen and (min-width: 992px) {
  .color-mode-picker {
    display: none;
  }
}
</style>
