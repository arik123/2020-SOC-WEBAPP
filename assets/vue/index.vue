<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import EditModal from "./editModal.vue";

export default {
  components: {
    FullCalendar, // make the <FullCalendar> tag available
    EditModal,
  },
  data() {
    return {
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
		dateClick: this.handleDateClick,
		eventClick: this.handleEventClick,
        events: [
          { title: "event 1", date: "2020-11-01", color: '#ff0000', editable: true},
          { title: "event 2", date: "2020-11-02", display: 'background', backgroundColor: '#ff0000', editable: true},
        ],
      },
    };
  },
  methods: {
    handleDateClick: function (arg) {
      this.$refs.editModal.show();
      console.log(arg);
	},
	handleEventClick: function (arg) {
      this.$refs.editModal.show();
      console.log(arg);
    },
  },
};
</script>

<template>
  <div>
    <EditModal ref="editModal" />
    <div class="m-5">
      <FullCalendar :options="calendarOptions" />
    </div>
  </div>
</template>
