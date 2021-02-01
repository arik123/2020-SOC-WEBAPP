import Vue from 'vue'
import route from './vue/map-info.vue'

var vue = new Vue({
	render: h => h(route)
  }).$mount(
	document.getElementById("app")
);