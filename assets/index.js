/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import Vue from 'vue'
import index from './vue/index.vue'

new Vue({
	render: h => h(index)
  }).$mount(
	document.getElementById("app")
  )