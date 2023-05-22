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
        title="Update Employee"
        main
      >
        <router-link to="/employees">
          <BaseButton

            target="_blank"
            :icon="mdiAccountMultiple"
            label="Show All Employees"
            color="contrast"
            rounded-full
            class="font-bold"
            small
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
            <FormField label="CIN" >
            <FormControl type="text" v-model="form.cin" required/>
          </FormField>
          <FormField label="Birthday" >
            <FormControl type="date" v-model="form.birthday" required/>
          </FormField>
          </FormField>
            <FormFild>
                <FormField label="Address" >
            <FormControl type="text" v-model="form.adress" required/>
          </FormField>
        </FormFild>
        <br>
          <FormField>
          <FormField label="Images">
          <input type="file"
                id="avatar"
                 class="file-input file-input-bordered file-input-info w-full max-w-xs bg-black text-white"  
              @change="OnFileChange"
        />
        </FormField>
    
        <div style="width: 130px; height: 130px; border-radius: 40%; overflow: hidden;">
           <!--<img v-if="form.avatar" :src="'/storage/AdminAvatar/' + form.avatar" alt="admin" class="w-full h-full object-cover">--> 
            <!--<img v-else src="/storage/AdminAvatar/default.png" alt="default" class="w-full h-full object-cover">-->
        <img v-bind:src="previewImage==null ?  form.avatar?  form.avatar : '/user.png' :previewImage" class="w-full h-full object-cover" />
          </div>
        </FormField>
       
          <template #footer>
            <div class="flex justify-center">
            <BaseButtons >  
              
                
               <div class="flex justify-center mt-6">
              <BaseButton type="submit"  class="buttonStyle" label="Update Employee" @click="updateEmployee"/> 
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
      form: {
        first_name: "",
        last_name: "",
        phone_number: "",
        email: "",
      avatar:"",
        cin: "",
        birthday: "",
        adress: "",
       
      },
    }
    },
  
  props: [],
  methods: {
    getEmployeeById(){
      const id = this.$route.params.id;
      axios.get(`/api/get_employee/${id}`).then((response) => {
  this.form = response.data
})
    },
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
    async updateEmployee() {
  const id = this.$route.params.id;
  const updatedData = {
    first_name: this.form.first_name,
    last_name: this.form.last_name,
    phone_number: this.form.phone_number,
    email: this.form.email,
    cin: this.form.cin,
    adress: this.form.adress,
    birthday: this.form.birthday,
    avatar: this.form.avatar,

  };
  console.log(updatedData);
  // let data = new FormData();
  //     data.append("first_name", this.form.first_name);
  //     data.append("last_name", this.form.last_name);
  //     data.append("email", this.form.email);
  //     data.append("phone_number", this.form.phone_number);
  //     data.append("cin", this.form.cin);
  //     data.append("birthday", this.form.birthday);
  //     data.append("address", this.form.adress);

  //     if (this.form.avatar) {
  //       data.append("avatar", this.avatar);
  //     }

  // Create a new FormData object to send the updated form data
  // const formData = new FormData();
  // Object.keys(updatedData).forEach(key => {
  //   formData.append(key, updatedData[key]);
  // });
  
  // Append the updated avatar image file if it exists
  // if (this.form.avatar instanceof File) {
  //   formData.append('avatar', this.form.avatar);
  // }

  // Make the PUT request with the updated data and the FormData object
  axios.put(`/api/update_employee/${id}`,updatedData)
    .then(() => {
      swal({
        text: "Employee Updated Successfully!",
        icon: "success",
        closeOnClickOutside: false,
      });
      this.$router.go();
    })
    .catch(error => {
      console.log(error);
      swal({
        text: "Error updating employee",
        icon: "error",
        closeOnClickOutside: false,
      });
    });
},

OnFileChange(e) {
  // this.form.avatar = e.target.files[0];
  // let reader = new FileReader();
  // reader.addEventListener("load", () => {
  //   this.previewImage = reader.result;
  // }, false);
  // if (this.form.avatar) {
  //   if (this.form.avatar.type.match('image.*')) {
  //     reader.readAsDataURL(this.form.avatar);
  //   }
  // } else {
  //   this.previewImage = null;
  // }
 
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


}

},
mounted() {
this.getEmployeeById();
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


