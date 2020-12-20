<template>
  <div>
		<div v-if="driver===null">
			<div @click="driver = false" class="btn btn-outline-primary d-block w-50 mx-auto my-3">
				Chcem sa viezt
			</div>
			<div  @click="driver = true" class="btn btn-outline-primary d-block w-50 mx-auto my-3">
				Chcem viezt
			</div>
		</div>
		<form action="" method="post"  v-else>
			<input type="hidden" name="driver" :value="(driver)?1:0">
			<button class="btn btn-primary" @click="driver=null">
				Späť
			</button>
			
			<div class="mb-3">
				vyber zaciatok a koniec
				<Map @start="start = true" @end="end=true"/>
			</div>
			<div class="mb-3 w-50 mx-auto">
				<label for="kedy" class="form-label">kedy</label>
				<input type="datetime-local" class="form-control" name="kedy" id="kedy" v-model="datet">
			</div>
			<div class="mb-3 w-50 mx-auto" v-if="driver">
				
				<label for="miesta" class="form-label">Počet volych miest</label>
				<input type="number" class="form-control" name="miesta" id="miesta" v-model="miesta">
				<label for="zachadzka" class="form-label">Zachadzka (KM)</label>
				<input type="number" step="any" class="form-control" name="zachadzka" id="zachadzka" v-model="zachadzka">
			</div>
			<!-- TODO: opakovana jazda-->
			<button v-if="readyToSubmit" type="submit" class="btn btn-primary">{{driver?"Zadaj":"Vyhladaj"}}</button>
		</form>
  </div>
</template>

<script>
import Map from './map.vue'
export default {
	components: {
		Map
	},
	data() {
		return {
			driver: null,
			start: false,
			end: false,
			datet: null,
			miesta: null,
			zachadzka: null
		};
	},
	computed: {
		readyToSubmit: function () {
			return this.start && this.end && this.datet 
			&& this.driver !== null && ((this.driver)? this.miesta > 0 && this.zachadzka > 0 : true)

		}
	}
}
</script>

<style>

</style>