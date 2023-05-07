<script setup>

import {mdiPlus, mdiLock,mdiOrderBoolAscendingVariant} from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";
import CardBox from "@/components/CardBox.vue";

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
        title="New Assignment"
        main
      >
        <router-link to="/assignments">
          <BaseButton

            target="_blank"
            :icon="mdiOrderBoolAscendingVariant"
            label="Show Assignment"
            color="contrast"
            rounded-full
            small
          />
        </router-link>
      </SectionTitleLineWithButton>
      <div class="container w-20/20 mx-auto">
        <CardBox form @submit.prevent="submit">
          <h2 class="text-2xl font-semibold text-center text-blue-300">Elevator Section</h2>
          <br>
            <FormField>
          <FormField label="Elevator">
            <FormControl type="name" :options="elevators" required/>
          </FormField>
          <FormField label="Area">
            <FormControl type="name" :options="missions" required/>
          </FormField>
            </FormField>
            <FormField>
          <FormField label="City">
            <FormControl type="name" v-model="name" required/>
          </FormField>
          <FormField label="Adress">
            <FormControl type="name" v-model="name" required/>
          </FormField>
            </FormField>
            <BaseDivider/>
           
            <h2 class="text-2xl font-semibold text-center text-blue-300">Employee Section</h2>
          <br>
          <FormField    class="flex flex flex-row items-center justify-center" label="Employee CIN :">
            <FormControl type="name" v-model="name"  :options="employees" class="ml-7 w-80" required/>
          </FormField> 
        
          <FormField>
          <FormField label="First Name">
            <FormControl type="name" v-model="name" required/>
          </FormField>
            
          <FormField label="Last Name">
            <FormControl type="name" v-model="email" required/>
          </FormField>
        </FormField> 
        <FormField>
          <FormField label="Email">
            <FormControl type="name" v-model="email" required/>
          </FormField>
       
       
          <FormField label="Phone Number">
            <FormControl type="name" v-model="password" required/>
          </FormField>
          </FormField>
          <FormField>
          <FormField label="CIN">
            <FormControl type="name" v-model="password" required/>
          </FormField>
          <FormField label="Adress">
            <FormControl type="name" v-model="password" required/>
          </FormField>
       </FormField>
       <BaseDivider/>
       <h2 class="text-2xl font-semibold text-center text-blue-300">Assignment Section</h2>
          <br>
       <FormField>
          <FormField label="Start Day">
            <FormControl type="date" v-model="password" required/>
          </FormField>
          <FormField label="End Date">
            <FormControl type="date" v-model="password" required/>
          </FormField>
       </FormField>
       <FormField>
          <FormField label="Check In">
            <FormControl type="time" v-model="password" required/>
          </FormField>
          <FormField label="Check Out">
            <FormControl type="time" v-model="password" required/>
          </FormField>
       </FormField>


          <template #footer>
             <div class="flex justify-center">
            <BaseButtons > 
                <BaseButton  type="reset" color="info" outline label="Reset"/>
              <BaseButton  type="submit" color="info" label="Add" @click="addAdmin"/>
             
            </BaseButtons>
        </div>
          </template>
        </CardBox>
      </div>
    </SectionMain>


  </LayoutAuthenticated>
</template>
<script>
import axios from 'axios';


export default {
  data() {
    return {
      
   
      missions: [],
      elevators: [],
      employees:[],
    }
  
  },
  methods:{
getMissions(){
  axios.get('/api/missions')
      .then(response => {
        this.missions = response.data.data;
      })
      .catch(error => {
        console.log(error);
      });
},
getElevators(){
  axios.get('/api/getNames')
      .then(response => {
        this.elevators = response.data;
      })
      .catch(error => {
        console.log(error);
      });
},
getEmployees(){
  axios.get('/api/getEmployees')
      .then(response => {
        this.employees = response.data.map(item => item.name);
      })
      .catch(error => {
        console.log(error);
      });
},

},
mounted() {
    this.getMissions();
    this.getElevators();
    this.getEmployees();
  },

}
</script>


