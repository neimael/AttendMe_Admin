<script setup>
import {mdiPlus, mdiLock,mdiOrderBoolAscendingVariant,mdiTextAccount} from "@mdi/js";
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
        title="New Manual Presence"
        main
      >
        <router-link :to="`/employee-presence/${$route.params.id}`">
            <BaseButton
            target="_blank"
            :icon="mdiTextAccount"
            label="Employee's Presence"
            color="contrast"
            rounded-full
class="font-bold"
            small
          />
        </router-link>
      </SectionTitleLineWithButton>
      <div class="container w-20/20 mx-auto">
        <CardBox form @submit.prevent="submit">
          <h2 class="text-2xl font-semibold text-center text-blue-300">Employee Section</h2>
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
       
        <FormField v-if="informationEmp">
          <FormField>
          <FormField label="First Name">
            <FormControl type="name" v-model="informationEmp.first_name" required/>
          </FormField>
            
          <FormField label="Last Name">
            <FormControl type="name" v-model="informationEmp.last_name" required/>
          </FormField>
        </FormField> 
        <FormField>
          <FormField label="Email">
            <FormControl type="name" v-model="informationEmp.email" required/>
          </FormField>
       
       
          <FormField label="Phone Number">
            <FormControl type="name" v-model="informationEmp.phone_number" required/>
          </FormField>
          </FormField>
          <FormField>
          <FormField label="CIN">
            <FormControl type="name" v-model="informationEmp.cin" required/>
          </FormField>
          <FormField label="Adress">
            <FormControl type="name" v-model="informationEmp.adress" required/>
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
       <h2 class="text-2xl font-semibold text-center text-blue-300">Presence Section</h2>
          <br>
       <FormField>
          <FormField label="Attendance day Day">
            <FormControl type="date" v-model="this.form.attendance_day" required/>
          </FormField>
        
       </FormField>
       <FormField>
          <FormField label="Check In">
            <FormControl type="time" v-model="this.form.check_in" required/>
          </FormField>
          <FormField label="Check Out">
            <FormControl type="time" v-model="this.form.check_out" required/>
          </FormField>
       </FormField>


          <template #footer>
             <div class="flex justify-center">
            <BaseButtons > 
               
              <BaseButton  type="submit" color="info" label="Add Presence" @click="addPresence"/>
             
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
        check_in: "",
        check_out: "",
        attendance_day: "",
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
      const id = this.$route.params.id;
      axios.get(`/api/get_employee/${id}`)
        .then(response => {
          // Handle the API response
          this.informationEmp= response.data;
        })
        .catch(error => {
          // Handle errors
          console.error(error);
        });
    }, 
    addPresence() {
      this.form.id_elevator= this.information.qrcode.id_qr_code;
      this.form.id_employee= this.informationEmp.id;
      console.log(this.form.id_elevator);
      console.log(this.form.id_employee);
      let data = new FormData();
        data.append('check_in', this.form.check_in);
        data.append('check_out', this.form.check_out);
        data.append('attendance_day', this.form.attendance_day);
        data.append('id_elevator', this.form.id_elevator);
        data.append('id_employee', this.form.id_employee);
        if (this.form.check_in >= this.form.check_out) {
    swal({
      text: "Check-in time must be before check-out time.",
      icon: "error",
      closeOnClickOutside: false,
    });
    return; // Exit the method if the condition is not met
  }else if (this.form.check_in == this.form.check_out) {
    swal({
        text: "Check-in time must be before check-out time.",
        icon: "error",
        closeOnClickOutside: false,
        });
        return; // Exit the method if the condition is not met
    }
    else{
      // Make the API call
      axios.post('/api/addManualPresence',this.form).then(() => {
          swal({
            text: "Presence Added Successfully!",
            icon: "success",
            closeOnClickOutside: false,
          });
          this.$router.go();
        }).catch(error => {
          console.log(error);
        });
    }
      },
},
mounted() {
    this.getMissions();
    this.getElevators();
    this.fetchInformation2()
  },

}
</script>


