<script>
import 'leaflet/dist/leaflet.css';
import 'leaflet-defaulticon-compatibility/dist/leaflet-defaulticon-compatibility.webpack.css'; // Re-uses images from ~leaflet package
import L from 'leaflet';
import 'leaflet-defaulticon-compatibility';
import { LMap } from 'vue2-leaflet';

export default {
  name: "MyAwesomeMap",
  components: {
    LMap
  },
  data() {
    return {
        feat: null,
        start: null,
        end: null,
        map: null
      };
  },
  mounted() {
    this.$nextTick(() => {
        this.map =  this.$refs.myMap.mapObject;
        this.map.fitBounds([//16.74	47.72	22.61	49.62
            [47.72, 16.74],
            [49.62, 22.61]
        ]);;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            //TODO CHANGE MAP PROVIDER -- see OSM terms
        }).addTo(this.map);

        this.map.locate({setView: false, maxZoom: 14});
        this.map.on('locationfound', this.onLocationFound);

        function onLocationError(e) {
            alert("nepodarilo sa nám zistiť polohu");
        }

        this.map.on('locationerror', onLocationError);
        this.map.on('click', this.onMapClick);
    });
  },
  methods: {
    onLocationFound: function (e) {
        var radius = e.accuracy;

        L.circle(e.latlng, radius).addTo(this.map);
    }
  }
};
</script>

<template>
  <div>
    <l-map ref="myMap" style="height: 30vh"></l-map>
  </div>
</template>
