<template>
  <!-- Modal -->
<div class="modal fade" id="editModal" data-backdrop="static" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Understood</button>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import {Modal} from "bootstrap"
export default {
	
	mounted() {
		console.log(Modal)
		console.log(document.getElementById('editModal'));
		this.Modal = new Modal(document.getElementById('editModal'))
	},
	data: function () {
		return {
			Modal: null
		}
	},
	methods: {
		hide: function() {
			this.Modal.hide();
		},
		show: function() {
			this.Modal.show();
		},
	}
}
</script>

<style>

</style>