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
        map: null,
        greenIcon: new L.Icon({
          iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
          shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
          iconSize: [25, 41],
          iconAnchor: [12, 41],
          popupAnchor: [1, -34],
          shadowSize: [41, 41]
        }),
        redIcon: new L.Icon({
          iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
          shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
          iconSize: [25, 41],
          iconAnchor: [12, 41],
          popupAnchor: [1, -34],
          shadowSize: [41, 41]
        })
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
    onMapClick: function (e) {
            if(!this.start){
                this.$emit('start')
                this.start = L.marker(e.latlng, {icon: this.greenIcon, draggable:'true'})
                    .addTo(this.map)
                    .on('dragend', (event)=>{
                        let position = event.target.getLatLng();
                        event.target.setLatLng(position,{draggable:'true'});
                    });
            } else if (!this.end) {
                this.$emit('end')
                this.end = L.marker(e.latlng, {icon: this.redIcon, draggable:'true'})
                    .addTo(this.map)
                    .on('dragend', (event)=>{
                        let position = event.target.getLatLng();
                        event.target.setLatLng(position,{draggable:'true'});
                    });
            }

    },
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
    <input
      v-if="!(!start)"
      type="hidden"
      name="start"
      :value="start.getLatLng()"
    />
    <input
      v-if="!(!end)"
      type="hidden"
      name="end"
      :value="end.getLatLng()"
    />
  </div>
</template>
