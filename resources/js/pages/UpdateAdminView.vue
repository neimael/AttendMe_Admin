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
        title="Update Admin"
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
            <FormControl type="text" v-model="form.first_name" autocomplete required/>
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
         <FormField>
          <FormField label="Images">
            <input type="file"
                id="avatar"
                 class="file-input file-input-bordered file-input-info w-full max-w-xs bg-black text-white"  
              @change="OnFileChange"
        />
        </FormField>
        
        <div style="width: 130px; height: 130px; border-radius: 40%; overflow: hidden;">
        <img v-bind:src="previewImage==null ? form.avatar ? '/storage/AdminAvatar/' + form.avatar :'/user.png' : previewImage" class="w-full h-full object-cover" />
        </div>

        </FormField>
          <template #footer>
            <div class="flex justify-center">
            <BaseButtons >  
           
                <div class="flex justify-center mt-6">
              <BaseButton type="submit"  class="buttonStyle" label="Update Admin" @click="updateAdmin"/> 
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
      previewImage : null,
      file: null,
      form: new Form({
        first_name: "",
        last_name: "",
        phone_number: "",
        email: "",
        avatar: "",

      })
    }
    },
  
  props: [],
  methods: {
    getStringImage(file) {
    if (file === null) return null;

    return new Promise((resolve, reject) => {
      const reader = new FileReader();

      reader.onload = () => {
        const base64String = reader.result.split(',')[1];
        resolve(base64String);
      };

      reader.onerror = error => {
        reject(error);
      };

      reader.readAsDataURL(file);
    });
  },
    async getAdminById() {
      const id = this.$route.params.id;
      try {
        const response = await axios.get(`/api/get_admin/${id}`);
        this.form = response.data;
        console.log("Response Data:", response.data);
        console.log("Form Data:", this.form);
      } catch (error) {
        console.log("Error:", error);
      }
    },
    async updateAdmin() {
  const id = this.$route.params.id;

  // Create a new object that only contains the fields you want to update
  const updatedData = {
    first_name: this.form.first_name,
    last_name: this.form.last_name,
    phone_number: this.form.phone_number,
    email: this.form.email,
    avatar: this.form.avatar,
    
  };

  // Create a new FormData object to send the updated form data


  // Make the PUT request with the updated data and the FormData object
  axios.put(`/api/update_admin/${id}`, updatedData)
    .then(() => {
      swal({
        text: "Admin Updated Successfully!",
        icon: "success",
        closeOnClickOutside: false,
      });
      console.log(updatedData);
      this.$router.go();
    })
    .catch(error => {
      console.log(error);
      swal({
        text: "Error updating admin",
        icon: "error",
        closeOnClickOutside: false,
      });
    });
},

OnFileChange(e) {
  this.file = e.target.files[0];
  let reader = new FileReader();
  reader.onload = (e) => {
    this.previewImage = reader.result;
    this.getStringImage(this.file)
      .then(base64String => {
        // Do something with the base64String
        console.log(base64String);
        this.form.avatar = base64String;
      })
      .catch(error => {
        // Handle any errors
        console.error(error);
      });
  };
  reader.readAsDataURL(this.file);

},
  
    },
   mounted() {
    console.log("Component mounted");
    this.getAdminById();

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

