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
            label="Show All Assignments"
            color="contrast"
            rounded-full
            small
            class="font-bold"
          />
        </router-link>
      </SectionTitleLineWithButton>
      <div class="container w-20/20 mx-auto">
        <CardBox form @submit.prevent="submit">
          <h2 class="text-2xl font-semibold text-center text-blue-300">Elevator Section</h2>
          <br>
            <FormField>
          <FormField label="Elevator">
            <FormControl type="name" v-model="selectedElevator" :options="elevators" required/>
          </FormField>
          <FormField label="Area">
            <FormControl type="name" v-model="selectedMission"    :options="missions" required/>
          </FormField>
            </FormField>
            <FormField v-if="information">
          <FormField label="City">
            <FormControl type="name" v-model="information.qrcode.elevator.location.ville" required/>
          </FormField>
          <FormField label="Adress">
            <FormControl type="name" v-model="information.qrcode.elevator.location.adress" required/>
          </FormField>
            </FormField>
            <FormField v-else>
          <FormField label="City">
            <FormControl type="name"  required/>
          </FormField>
          <FormField label="Adress">
            <FormControl type="name" required/>
          </FormField>
            </FormField>
            <BaseDivider/>
           
            <h2 class="text-2xl font-semibold text-center text-blue-300">Employee Section</h2>
          <br>
          <FormField   label="Employee CIN :">
            <FormControl type="name" v-model="selectedEmployee"  :options="employees" required/>
          </FormField> 
        <FormField v-if="informationEmp">
          <FormField>
          <FormField label="First Name">
            <FormControl type="name" v-model="informationEmp.employee.first_name" required/>
          </FormField>
            
          <FormField label="Last Name">
            <FormControl type="name" v-model="informationEmp.employee.last_name" required/>
          </FormField>
        </FormField> 
        <FormField>
          <FormField label="Email">
            <FormControl type="name" v-model="informationEmp.employee.email" required/>
          </FormField>
       
       
          <FormField label="Phone Number">
            <FormControl type="name" v-model="informationEmp.employee.phone_number" required/>
          </FormField>
          </FormField>
          <FormField>
          <FormField label="CIN">
            <FormControl type="name" v-model="informationEmp.employee.cin" required/>
          </FormField>
          <FormField label="Adress">
            <FormControl type="name" v-model="informationEmp.employee.adress" required/>
          </FormField>
       </FormField>
       </FormField>
       <FormField v-else>
          <FormField>
          <FormField label="First Name">
            <FormControl type="name"  required/>
          </FormField>
            
          <FormField label="Last Name">
            <FormControl type="name"  required/>
          </FormField>
        </FormField> 
        <FormField>
          <FormField label="Email">
            <FormControl type="name" required/>
          </FormField>
       
       
          <FormField label="Phone Number">
            <FormControl type="name"  required/>
          </FormField>
          </FormField>
          <FormField>
          <FormField label="CIN">
            <FormControl type="name"  required/>
          </FormField>
          <FormField label="Adress">
            <FormControl type="name"  required/>
          </FormField>
       </FormField>
       </FormField>
       <BaseDivider/>
       <h2 class="text-2xl font-semibold text-center text-blue-300">Assignment Section</h2>
          <br>
       <FormField>
          <FormField label="Start Day">
            <FormControl type="date" v-model="this.form.start_date" required/>
          </FormField>
          <FormField label="End Date">
            <FormControl type="date" v-model="this.form.end_date" required/>
          </FormField>
       </FormField>
       <FormField>
          <FormField label="Check In">
            <FormControl type="time" v-model="this.form.time_in" required/>
          </FormField>
          <FormField label="Check Out">
            <FormControl type="time" v-model="this.form.time_out" required/>
          </FormField>
       </FormField>


          <template #footer>
             <div class="flex justify-center">
            <BaseButtons > 

              <div class="flex justify-center mt-6">
              <BaseButton type="submit"  class="buttonStyle" label="Add Assignment" @click="addAssignments"/> 
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
import axios from 'axios';
import Form from "@/form.js";


export default {
  data() {
    return {
      form: new Form({
        id_elevator: "",
        id_employee: "",
        time_in: "",
        time_out: "",
        start_date: "",
        end_date: "",
     
      }),
   
      missions: [],
      elevators: [],
      employees:[],
      selectedElevator: '',
      selectedMission: '',
      information: null,
      selectedEmployee: '',
      informationEmp:null,
    }
  
  },
  watch: {
   
    selectedMission: {
   
      handler() {
        this.fetchInformation();
      },
    },
    selectedEmployee: {
      handler() {
        this.fetchInformation2();
      },
    },
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
fetchInformation() {
      // Make the API call
      axios.post('/api/information', {
        elevator: this.selectedElevator,
        mission: this.selectedMission,
      })
        .then(response => {
          // Handle the API response
          this.information = response.data;
        })
        .catch(error => {
          // Handle errors
          console.error(error);
        });
    },
    fetchInformation2() {
      // Make the API call
      axios.post('/api/informationEmployee', {
        cin: this.selectedEmployee,
      
      })
        .then(response => {
          // Handle the API response
          this.informationEmp= response.data;
        })
        .catch(error => {
          // Handle errors
          console.error(error);
        });
    },
    addAssignments() {
      this.form.id_elevator= this.information.qrcode.id_qr_code;
      this.form.id_employee= this.informationEmp.employee.id;
      console.log(this.form.id_elevator);
      console.log(this.form.id_employee);
      let data = new FormData();
        data.append('time_in', this.form.time_in);
        data.append('time_out', this.form.time_out);
        data.append('start_date', this.form.start_date);
        data.append('end_date', this.form.end_date);
        data.append('id_elevator', this.form.id_elevator);
        data.append('id_employee', this.form.id_employee);
        if(this.form.start_date == "" || this.form.end_date == "" || this.form.time_in == "" || this.form.time_out == "" || this.form.id_elevator == "" || this.form.id_employee == ""){
          swal({
            text: "Please fill all the fields.",
            icon: "error",
            closeOnClickOutside: false,
          });
          return; // Exit the method if the condition is not met
        }
        if (this.form.start_date >= this.form.end_date) {
          swal({
            text: "Start date must be before end date.",
            icon: "error",
            closeOnClickOutside: false,
          });
          return;
         }
          // Exit the method if the condition is not met
        if (this.form.time_in >= this.form.time_out) {
    swal({
      text: "Time-in time must be before time-out time.",
      icon: "error",
      closeOnClickOutside: false,
    });
    return; // Exit the method if the condition is not met
  }else if (this.form.time_in == this.form.time_out) {
    swal({
        text: "Time-in time must be before time-out time.",
        icon: "error",
        closeOnClickOutside: false,
        });
        return; // Exit the method if the condition is not met
    }
    else{
      // Make the API call
      axios.post('/api/addAsignment',this.form).then(() => {
          swal({
            text: "Assigment Added Successfully!",
            icon: "success",
            closeOnClickOutside: false,
          });
          this.$router.go();
        })  .catch(error => {
    if (error.response && error.response.status === 422) {
      swal({
        text: "Unprocessable Entity: " + error.response.data.message,
        icon: "error",
        closeOnClickOutside: false,
      });
    } else {
      console.log(error);
    }
  });
    }
    },

      
},
mounted() {
    this.getMissions();
    this.getElevators();
    this.getEmployees();
  },

}
</script>
<style scoped>
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


