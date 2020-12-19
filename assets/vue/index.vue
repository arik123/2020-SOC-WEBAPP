<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import EditModal from "./editModal.vue";
import skLocale from '@fullcalendar/core/locales/sk';

export default {
    components: {
        FullCalendar, // make the <FullCalendar> tag available
        EditModal,
    },
    data() {
        return {
            calendarOptions: {
                plugins: [dayGridPlugin, interactionPlugin],
                locales: [skLocale],
                locale: 'sk',
                initialView: "dayGridMonth",
                dateClick: this.handleDateClick,
                eventClick: this.handleEventClick,
                events: [
                    {
                        title: "event 1",
                        date: "2020-11-01",
                        color: "#ff0000",
                        editable: true,
                    },
                    {
                        title: "event 2",
                        date: "2020-11-02",
                        display: "background",
                        backgroundColor: "#ff0000",
                        editable: true,
                    },
                ]
            }, 
            
        };
    },
    mounted() {
    this.$nextTick(() => {
        document.querySelectorAll(".fc-col-header-cell.fc-day").forEach(element => {
            element.addEventListener('click', (e)=>{
                this.clicked(element)
            });
        });
    });
  },
    methods: {
        clicked: function (e) {
            let Class = e.className.split(' ').filter((e)=>{
                    return e.startsWith('fc-day-')
                })[0];
            console.log(e)
            console.log(Class.split('-')[2])
        },
        handleDateClick: function(arg) {
            this.$refs.editModal.show();
            console.log(arg);
        },
        handleEventClick: function(arg) {
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
