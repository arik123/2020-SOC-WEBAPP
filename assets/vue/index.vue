<script>
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

export default {
  components: {
    FullCalendar // make the <FullCalendar> tag available
  },
  data() {
    return {
      calendarOptions: {
        plugins: [ dayGridPlugin, interactionPlugin ],
        initialView: 'dayGridMonth',
        dateClick: this.handleDateClick,
        events: [
          { title: 'event 1', date: '2020-11-01' },
          { title: 'event 2', date: '2020-11-02' }
        ]
      }
    }
  },
  methods: {
    handleDateClick: function(arg) {
	  alert('date click! ' + arg.dateStr)
	  console.log(arg)
    }
  }
}
</script>
<template>
<div>
<FullCalendar :options="calendarOptions" />
</div>
  
</template>