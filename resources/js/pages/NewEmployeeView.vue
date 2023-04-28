<script setup>

import {mdiPlus, mdiLock,mdiAccountMultiple} from "@mdi/js";
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
        title="New Employee"
        main
      >
        <router-link to="/employees">
          <BaseButton

            target="_blank"
            :icon="mdiAccountMultiple"
            label="Show Employees"
            color="contrast"
            rounded-full
            small
          />
        </router-link>
      </SectionTitleLineWithButton>
      <div class="container w-7/12 mx-auto">
        <CardBox form @submit.prevent="submit">
            <FormField>
          <FormField label="FirstName" >
            <FormControl type="text" v-model="first_name" required/>
          </FormField>
          <FormField label="LastName" >
            <FormControl type="text" v-model="last_name" required/>
          </FormField>
            </FormField>
            <FormField>
          <FormField label="Email" >
            <FormControl type="email" v-model="email" required/>
          </FormField>
          <FormField label="Phone Number" >
            <FormControl type="phone" v-model="phone_number" required/>
          </FormField>
        </FormField>
        <FormField>
            <FormField label="CIN" >
            <FormControl type="text" v-model="cin" required/>
          </FormField>
          <FormField label="Birthday" >
            <FormControl type="date" v-model="birthday_date" required/>
          </FormField>
          
        </FormField>
          <FormField label="Password" >
            <FormControl v-model="password" required/>
          </FormField>
          <FormField label="Images" >
          <input type="file"
                 id="images"
                 name="images"
                 class="file-input file-input-bordered file-input-warning w-full max-w-xs bg-black text-white"
                 @change="onImageSelected"
                 />
        </FormField>


          <template #footer>
             <div class="flex justify-center">
            <BaseButtons > 
              
              <BaseButton  type="submit" color="warning" label="Add" @click="addEmployee()"/> 
               <BaseButton  type="reset" color="warning" outline label="Reset"/>
            </BaseButtons>
        </div>
          </template>
        </CardBox>
      </div>
    </SectionMain>


  </LayoutAuthenticated>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      first_name: "",
      last_name: "",
      cin: "",
      birthday_date: "",
      phone_number: "",
      
      email: "",
      password: "",
    }
  },
  props: [],
  methods: {

    async addEmployee() {
      const newEmployee = {
        first_name: this.first_name,
        last_name: this.last_name,
        cin: this.cin,
        birthday_date: this.birthday_date,
        phone_number: this.phone_number,
        
        email: this.email,
        password: this.password,
      };


      axios.post('api/add_employee', newEmployee)
        .then(() => {
          swal({
            text: "Employee Added Successfully!",
            icon: "success",
            closeOnClickOutside: false,
          });
        })
        .catch(error => {
          console.log(error);
        });

    }
  }

}
</script>
