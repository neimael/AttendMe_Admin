<script setup>

import {mdiPlus, mdiLock} from "@mdi/js";
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
        title="New Admin"
        main
      >
        <router-link to="/admins">
          <BaseButton

            target="_blank"
            :icon="mdiLock"
            label="Show All Admins"
            color="contrast"
            rounded-full
            small
            class="font-bold"
          />
        </router-link>
      </SectionTitleLineWithButton>
      <div class="container w-7/12 mx-auto">
        <CardBox form>
            <FormField>
          <FormField label="FirstName">
            <FormControl type="text" v-model="form.first_name" required/>
          </FormField>
          <FormField label="LastName">
            <FormControl type="text" v-model="form.last_name" required/>
          </FormField>
            </FormField>
            <FormField>
          <FormField label="Email">
            <FormControl type="email" v-model="form.email" required/>
          </FormField>
          <FormField label="Phone Number">
            <FormControl type="phone" v-model="form.phone_number" required/>
          </FormField>
        </FormField>
         
          <FormField label="Images">
          <input type="file"
                id="avatar"
                 class="file-input file-input-bordered file-input-info w-full max-w-xs bg-black text-white"
                
                 />
        </FormField>
          <template #footer>
            <div class="flex justify-center">
            <BaseButtons >  
              
                <div class="flex justify-center mt-6">
              <BaseButton type="submit"  class="buttonStyle" label="Add Admin" @click="addAdmin()"/> 
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
import axios from "axios";


export default {
  data() {
    return {
      form: new Form({
        first_name: "",
        last_name: "",
        phone_number: "",
        email: "",
       
      })
    }
    },
  
  props: [],
  methods: {
    async addAdmin() {

        let data = new FormData();
        data.append('first_name', this.form.first_name);
        data.append('last_name', this.form.last_name);
        data.append('phone_number', this.form.phone_number);
        data.append('email', this.form.email);
        //data.append('avatar', this.form.avatar);
       // data.append('password', this.form.password);
        if(document.getElementById('avatar').files[0]){data.append('avatar', document.getElementById('avatar').files[0]);}
        axios.post('api/add_admin', data).then((response) => {
      if (response.status === 200) {
        swal({
          text: "Admin Added Successfully!",
          icon: "success",
          closeOnClickOutside: false,
        });
        this.$router.go();
      }
    })
    .catch(error => {
      if (error.response && error.response.status === 400) {
        swal({
          text: "Email already exists in the database.",
          icon: "error",
          closeOnClickOutside: false,
        });
      } else {
        console.log(error);
      }
    });
},
   
  }
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

