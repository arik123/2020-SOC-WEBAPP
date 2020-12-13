import Vue from 'vue'
import map from './vue/map.vue'

new Vue({
	render: h => h(map)
  }).$mount(
	document.getElementById("app")
  )