import Vue from 'vue'
import route from './vue/route.vue'

new Vue({
	render: h => h(route)
  }).$mount(
	document.getElementById("app")
);