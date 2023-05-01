<script setup>

import {mdiPlus, mdiLock,mdiAccountMultiple} from "@mdi/js";
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
        <CardBox form >
            <FormField>
          <FormField label="FirstName" >
            <FormControl type="text" v-model="form.first_name" required/>
          </FormField>
          <FormField label="LastName" >
            <FormControl type="text" v-model="form.last_name" required/>
          </FormField>
            </FormField>
            <FormField>
          <FormField label="Email" >
            <FormControl type="email" v-model="form.email" required/>
          </FormField>
          <FormField label="Phone Number" >
            <FormControl type="phone" v-model="form.phone_number" required/>
          </FormField>
        </FormField>
        <FormField>
            <FormField label="CIN" >
            <FormControl type="text" v-model="form.cin" required/>
          </FormField>
          <FormField label="Birthday" >
            <FormControl type="date" v-model="form.birthday" required/>
          </FormField>
          
        </FormField>
        <FormField>
        <FormField label="Address" >
            <FormControl type="text" v-model="form.adress" required/>
          </FormField>
          <FormField label="Images" >
          <input type="file"
                 id="avatar"
                 
                 class="file-input file-input-bordered file-input-warning w-full max-w-xs bg-black text-white"
                
                 />
        </FormField>
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
      form: new Form({
        first_name: "",
        last_name: "",
        phone_number: "",
        cin: "",
        birthday: "",
        email: "",
        password: "",
        adress: "",
      })
    }
    },
  
  props: [],
  methods: {
    async addEmployee() {

        let data = new FormData();
        data.append('first_name', this.form.first_name);
        data.append('last_name', this.form.last_name);
        data.append('phone_number', this.form.phone_number);
        data.append('email', this.form.email);
        data.append('cin', this.form.cin);
        data.append('birthday', this.form.birthday);
        data.append('adress', this.form.adress);
        //data.append('avatar', this.form.avatar);
        data.append('password', this.form.password);
        if(document.getElementById('avatar').files[0]){data.append('avatar', document.getElementById('avatar').files[0]);}
        axios.post('api/add_employee', data).then(() => {
          swal({
            text: "Employee Added Successfully!",
            icon: "success",
            closeOnClickOutside: false,
          });
          this.$router.go();
        }).catch(error => {
          console.log(error);
        });
      },
   
  }
}
</script>



