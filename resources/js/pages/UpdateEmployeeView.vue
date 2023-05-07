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
        title="Update Employee"
        main
      >
        <router-link to="/employees">
          <BaseButton

            target="_blank"
            :icon="mdiLock"
            label="Show Employees"
            color="contrast"
            rounded-full
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
            <FormField>
                <FormField label="Address" >
            <FormControl type="text" v-model="form.adress" required/>
          </FormField>
          <FormField label="Images">
          <input type="file"
                id="avatar"
                 class="file-input file-input-bordered file-input-warning w-full max-w-xs bg-black text-white"  
              @change="OnFileChange"
        />
        </FormField>
    
        <div style="width: 130px; height: 130px; border-radius: 40%; overflow: hidden;">
           <!--<img v-if="form.avatar" :src="'/storage/AdminAvatar/' + form.avatar" alt="admin" class="w-full h-full object-cover">--> 
            <!--<img v-else src="/storage/AdminAvatar/default.png" alt="default" class="w-full h-full object-cover">-->
        <img v-bind:src="previewImage==null ?  form.avatar :previewImage" class="w-full h-full object-cover" />
          </div>
        </FormField>
          <template #footer>
            <div class="flex justify-center">
            <BaseButtons >  
               <BaseButton  type="submit" color="warning" label="Update" @click="updateEmployee"/>
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
      previewImage : null,
      form: new Form({
        first_name: "",
        last_name: "",
        phone_number: "",
        email: "",
        avatar: "",
        cin: "",
        birthday: "",
        adress: "",
       
      })
    }
    },
  
  props: [],
  methods: {
    getEmployeeById(){
      const id = this.$route.params.id;
      axios.get(`api/get_employee/${id}`).then((response) => {
  this.form = response.data
})
    },
    async updateEmployee() {
  const id = this.$route.params.id;

  // Create a new object that only contains the fields you want to update
  const updatedData = {
    first_name: this.form.first_name,
    last_name: this.form.last_name,
    phone_number: this.form.phone_number,
    email: this.form.email,
    cin: this.form.cin,
adress: this.form.adress,
    birthday: this.form.birthday,
    avatar: this.previewImage==null ?  this.form.avatar :this.previewImage,
    
  };

  // Create a new FormData object to send the updated form data


  // Make the PUT request with the updated data and the FormData object
  axios.put(`api/update_employee/${id}`, updatedData)
    .then(() => {
      swal({
        text: "Employee Updated Successfully!",
        icon: "success",
        closeOnClickOutside: false,
      });
      console.log(updatedData);
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
  this.form.avatar = e.target.files[0];
  let reader = new FileReader();
  reader.addEventListener("load", () => {
    this.previewImage = reader.result;
  }, false);
  if (this.form.avatar) {
    if (this.form.avatar.type.match('image.*')) {
      reader.readAsDataURL(this.form.avatar);
    }
  } else {
    this.previewImage = null;
  }
},

},
mounted() {
this.getEmployeeById();
},
 

}
</script>


