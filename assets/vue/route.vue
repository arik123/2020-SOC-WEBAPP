<template>
  <div>
	  
		<div v-if="driver===null">
			Som:
			<div @click="driver = false" class="btn btn-outline-primary d-block w-50 mx-auto my-3">
				Spolujazdec
			</div>
			<div  @click="driver = true" class="btn btn-outline-primary d-block w-50 mx-auto my-3">
				Šofér
			</div>
		</div>
		<form action="" method="post"  v-else>
			<input type="hidden" name="driver" :value="(driver)?1:0">
			<button class="btn btn-primary" @click="driver=null">
				Späť
			</button>
			
			<div class="mb-3">
				Vyber začiatok a koniec
				<Map @start="start = true" @end="end=true"/>
			</div>
			<div class="mb-3 w-50 mx-auto">
				<label for="kedy" class="form-label">Kedy</label>
				<input type="datetime-local" class="form-control" name="kedy" id="kedy" v-model="datet">
				<div class="unselectable">
					<div>Opakovanie</div>
					<div>
						<input type="radio" name="repeat" id="repeatNone" v-model="radio" value=0>
						<label for="repeatNone">Vôbec</label>
					</div>
					<div>
						<input type="radio" name="repeat" id="repeatWeekly" v-model="radio" value=1>
						<label for="repeatWeekly">Týždeňne</label>
					</div>
					<div>
						<input type="radio" name="repeat" id="repeatDaily" v-model="radio" value=3>
						<label for="repeatDaily">Denne</label>
					</div>
					<div>
						<input type="radio" name="repeat" id="repeatWorkday" v-model="radio" value=2>
						<label for="repeatWorkday">Pracovné dni</label>
					</div>
				</div>
			</div>
			<div class="mb-3 w-50 mx-auto" v-if="driver">
				<label for="miesta" class="form-label">Počet voľných miest</label>
				<input type="number" class="form-control" name="miesta" id="miesta" v-model="miesta">
				<label for="zachadzka" class="form-label">Zachádzka (km)</label>
				<input type="number" step="any" class="form-control" name="zachadzka" id="zachadzka" v-model="zachadzka">
			</div>
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
			zachadzka: null,
			radio: null
		};
	},
	computed: {
		readyToSubmit: function () {
			return this.start && this.end && this.datet && this.radio
			&& this.driver !== null && ((this.driver)? this.miesta > 0 && this.zachadzka > 0 : true)

		}
	}
}
</script>

<style>

</style>