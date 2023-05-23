<script setup>
import {mdiPlus, mdiLock,mdiElevatorPassengerOutline, mdiImageEdit, mdiBookEdit, mdiHumanEdit} from "@mdi/js";
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
          :icon="mdiHumanEdit"
          title="Update Elevator"
          main
        >
          <router-link to="/elevators">
            <BaseButton
  
              target="_blank"
              :icon="mdiElevatorPassengerOutline"
              label="Show All Elevators"
              color="contrast"
              rounded-full
              small
              class="font-bold"
            />
          </router-link>
        </SectionTitleLineWithButton>
        <div class="container w-7/12 mx-auto">
          <CardBox form>
             
            <FormField label="Name">
              <FormControl type="text" v-model=elevators.name autocomplete required/>
            </FormField>
            
            <FormField label="Location">
              <div ref="mapContainer" style="height: 344px;" ></div>
            </FormField>
            <FormField >
            <FormField label="City">
              <FormControl type="text" :options="cities" v-model="ville"  />
            </FormField>
            <FormField label="Address">
              <FormControl type="text" v-model="adress"  />
            </FormField>
          </FormField>
            <div id="map"></div>
  
            <template #footer>
              <div class="flex justify-center">
                <BaseButtons> 
                
                  <div class="flex justify-center mt-6">
              <BaseButton type="submit"  class="buttonStyle" label="Update Elevator" @click="UpdateElevator"/> 
           </div>
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
      elevators : [],
      cities: [],
      ville: '',
      adress: '',
      longitude: '',
      latitude: '',
      
    };
  
  },
  props: [],
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
getElevatorById(){
    const id = this.$route.params.id;
    axios.get(`/api/get_elevatoor/${id}`).then((response) => {
        console.log('Response:', response.data);
    this.elevators= response.data[0];
    this.adress = response.data[0].location.adress;
    this.ville = response.data[0].location.ville;
    this.longitude = response.data[0].location.longitude;
    this.latitude = response.data[0].location.latitude;
   // this.elevators.longitude = response.data[0].location.longitude;

    console.log('Elevator:', this.elevators);
   
   // this.initMap();
  }).catch((error) => {
    console.log(error);
  })  
},
async UpdateElevator(){
  const id = this.$route.params.id;
// Create a new object that only contains the fields you want to update
const updateData = {
    name: this.elevators.name,
    longitude: this.longitude,
    latitude: this.latitude,
    adress: this.adress,
    ville: this.ville
  };
  if (!this.elevators.name|| !this.adress || !this.longitude|| !this.latitude || !this.ville) {
    swal({
      text: "Please fill in all the required fields.",
      icon: "error",
      closeOnClickOutside: false,
    });
    return;
  }
axios.put(`/api/update_elevator/${id}`, updateData)
.then((response) => {
      if (response.status === 200) {
          swal({
            text: "Elevator Updated Successfully! \n And qrCodes are regenerated successfully!",
            icon: "success",
            closeOnClickOutside: false,
          });
        }
          this.$router.go();
        }).catch(error => {
      if (error.response && error.response.status === 400) {
        swal({
          text: "Name already exists in the database.",
          icon: "error",
          closeOnClickOutside: false,
        });
      } else {
        console.log(error);
      }
    });
      },
   

  },
   
  mounted() {
    this.getCities();
    this.getElevatorById();
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
     this.elevators.location.longitude = longitude;
      this.elevators.location.latitude = latitude;
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

.buttonStyle{
  background-color: #0099ff;
  color: white;
  border-radius: 5px;
  width: 100%;
  font-size: 20px;
  font-weight: 500;

  border:none;
  
}
.buttonStyle:hover{
  background-color: #0489db;
  color: white;
  border-radius: 5px;

 
  font-size: 20px;
  font-weight: 500;
  border:none;
  
}



</style>

