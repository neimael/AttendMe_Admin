<script setup>

import {mdiPlus, mdiLock,mdiElevatorPassengerOutline} from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";
import CardBox from "@/components/CardBox.vue";
import Form from "@/form.js";
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import BaseDivider from "@/components/BaseDivider.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import LayoutAuthenticated from "@/auth/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import swal from 'sweetalert';

window.Swal = swal;

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiPlus"
        title="New Elevator"
        main
      >
        <router-link to="/elevators">
          <BaseButton

            target="_blank"
            :icon="mdiElevatorPassengerOutline"
            label="Show Elevators"
            color="contrast"
            rounded-full
            small
          />
        </router-link>
      </SectionTitleLineWithButton>
      <div class="container w-7/12 mx-auto">
        <CardBox form>
           
          <FormField label="Name">
            <FormControl type="name" v-model="form.name"  required/>
          </FormField>
          
          <FormField label="Location">
            <div ref="mapContainer" style="height: 344px;"></div>
          </FormField>
          <FormField >
          <FormField label="City">
            <FormControl type="text" :options="cities"  v-model="form.ville" required />
          </FormField>
          <FormField label="Address">
            <FormControl type="text" v-model="form.adress" required />
          </FormField>
        </FormField>
          <div id="map"></div>



          <template #footer>
             <div class="flex justify-center">
            <BaseButtons > 
                <BaseButton  type="reset" color="warning" outline label="Reset"/>
                <BaseButton  type="submit" color="warning" label="Add" @click="addElevator"/>
             
            </BaseButtons>
        </div>
          </template>
        </CardBox>
      </div>
    </SectionMain>


  </LayoutAuthenticated>
</template>
<script>

import {Map, View} from 'ol';
import TileLayer from 'ol/layer/Tile';
import OSM from 'ol/source/OSM';
import Feature from 'ol/Feature';
import Point from 'ol/geom/Point';
import Style from 'ol/style/Style';
import Circle from 'ol/style/Circle';
import Fill from 'ol/style/Fill';
import VectorSource from 'ol/source/Vector';
import VectorLayer from 'ol/layer/Vector';
import {transform} from 'ol/proj';
import {fromLonLat} from "ol/proj";

import axios from 'axios';


export default {
  data() {
    return {
      
      form: new Form({
        name: '',
        longitude: '',
        latitude: '',
        adress: '',
        ville: ''
      }),
      cities: [],
    }
  
  },
  methods:{
getCities(){
  axios.get('/api/cities')
      .then(response => {
        this.cities = response.data.data;
      })
      .catch(error => {
        console.log(error);
      });
},
async addElevator() {
  let data = new FormData();
  data.append('name', this.form.name);
  data.append('adress', this.form.adress);
  data.append('longitude', this.form.longitude);
  data.append('latitude', this.form.latitude);

  axios
    .post('/api/add_elevator', this.form)
    .then(() => {
      swal({
        text: 'Elevator Added Successfully! \n And qrCodes are generated',
        icon: 'success',
        closeOnClickOutside: false,
      });

      this.$router.go();
    })
    .catch(error => {
      if (error.response && error.response.status === 422 && error.response.data.errors.name) {
        swal({
          text: 'Elevator name already exists. Please choose a different name.',
          icon: 'error',
          closeOnClickOutside: false,
        });
      } else {
        console.log(error);
      }
    });
},

   
  mounted() {
    this.getCities();
    // Define the map view
    const view = new View({
      center: fromLonLat([-9.6, 30.4]), // Set center to Agadir coordinates
      zoom: 12, // Increase zoom level to focus on Agadir
      projection: 'EPSG:3857'
    });


    // Define the map layer
    const layer = new TileLayer({
      source: new OSM(),
    });

    // Create the marker feature
    const marker = new Feature({
      geometry: new Point([0, 0]),
    });

    // Create a new style for the marker
    const markerStyle = new Style({
      image: new Circle({
        radius: 10,
        fill: new Fill({
          color: 'red'
        })
      })
    });

    // Set the style of the marker feature
    marker.setStyle(markerStyle);

    // Create the vector layer for the marker
    const vectorLayer = new VectorLayer({
      source: new VectorSource({
        features: [marker],
      }),
    });

    // Create the map object and set it as the view's target
    const map = new Map({
      target: this.$refs.mapContainer,
      layers: [layer, vectorLayer],
      view: view,
    });

    // Add an event listener to the map to move the marker to the clicked location
    map.on('click', (event) => {
      const [longitude, latitude] = transform(event.coordinate, 'EPSG:3857', 'EPSG:4326');
      //fill the location object
      //this.property.location.longitude = longitude;
     // this.property.location.latitude = latitude;
     this.form.longitude = longitude;
     this.form.latitude = latitude;
      console.log(`Latitude: ${latitude}, Longitude: ${longitude}`);
      //console.log("location", this.property.location);
      marker.setGeometry(new Point(event.coordinate));
    });
  },

};
</script>

<style>
#map {
  width: 100%;
  height: 100%;
}
</style>

