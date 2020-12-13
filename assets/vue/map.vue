<script>
import 'leaflet/dist/leaflet.css';
import 'leaflet-defaulticon-compatibility/dist/leaflet-defaulticon-compatibility.webpack.css'; // Re-uses images from ~leaflet package
import L from 'leaflet';
import 'leaflet-defaulticon-compatibility';

export default {
  name: "MyAwesomeMap",
  components: {
    FullCalendar, // make the <FullCalendar> tag available
    EditModal,
  },
  data() {
    return {};
  },
  mounted() {
    this.$nextTick(() => {
        let map =  this.$refs.myMap.mapObject;
        map.fitWorld();

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            //TODO CHANGE MAP PROVIDER -- see OSM terms
        }).addTo(map);

        map.locate({setView: true, maxZoom: 14});

        function onLocationFound(e) {
            var radius = e.accuracy;

            L.circle(e.latlng, radius).addTo(map);
        }

        map.on('locationfound', onLocationFound);

        function onLocationError(e) {
            alert(e.message);
        }

        map.on('locationerror', onLocationError);

        let popup = L.popup();

        function onMapClick(e) {
            popup
                .setLatLng(e.latlng)
                .setContent("You clicked the map at " + e.latlng.toString())
                .openOn(map);
        }

        map.on('click', onMapClick);
    });
  },
};
</script>

<template>
  <l-map ref="myMap"> </l-map>
</template>
