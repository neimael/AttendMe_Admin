<script setup>

import {
  mdiAccount,
  mdiMail,
  mdiAsterisk,
  mdiFormTextboxPassword,
  mdiGithub,
  mdiLock
} from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";
import CardBox from "@/components/CardBox.vue";
import BaseDivider from "@/components/BaseDivider.vue";
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import FormControlPass from "@/components/FormControlPass.vue";
import FormFilePicker from "@/components/FormFilePicker.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import UserCard from "@/components/UserCard.vue";
import LayoutAuthenticated from "@/auth/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import UserAvatarCurrentUser from "@/components/UserAvatarCurrentUser.vue";
import BaseLevel from "@/components/BaseLevel.vue";
</script>

<template >
  <LayoutAuthenticated>
    <SectionMain>
     

      <CardBox class="mb-6" >
    <BaseLevel type="justify-around lg:justify-center">
      <!-- <UserAvatarCurrentUser class="lg:mx-12" /> -->
      <div style="width: 300px; height: 300px; border-radius: 50%; overflow: hidden;" v-if="form">
            <img v-bind:src="previewImage==null ?  form.avatar ? '/storage/AdminAvatar/' + form.avatar : '/user.png' : previewImage"  class="w-full h-full object-cover" />
            </div>
      <div class="space-y-3 text-center md:text-left lg:mx-12">
    
        <h1 class="text-2xl" v-if="form">
          Hello, {{form.first_name }} {{ form.last_name }} <b> </b> !
        </h1>
          
      </div>
    </BaseLevel>
  </CardBox>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <CardBox is-form @submit.prevent="updateAdmin">
          <FormField label="Avatar" help="Max 500kb">
            <FormField>
          <input type="file"
                id="avatar"
                 class="file-input file-input-bordered file-input-info w-full max-w-xs bg-black text-white"
                 name="form.avatar"
                 @change="OnFileChange"
                 />
        </FormField>
          </FormField>

          <FormField label="First Name" v-if="form">
            <FormControl
            v-model="form.first_name"
              :icon="mdiAccount"
              name="username"
              required
              autocomplete="username"
            />
          </FormField>
          <FormField label="Last Name" v-if="form" >
            <FormControl
              v-model="form.last_name"
              :icon="mdiAccount"
              name="username"
              required
              autocomplete="username"
            />
          </FormField>
          <FormField label="E-mail" v-if="form" >

            <FormControl 
              v-model="form.email"
              :icon="mdiMail"
              type="email"
              name="email"
              required
              autocomplete="email"
            />

          </FormField>
          <FormField label="Phone Number" v-if="form" >
            <FormControl
             v-model="form.phone_number"
              :icon="mdiMail"
              
              name="phone_number"
              required
              autocomplete="email"
            />
          </FormField>

          <template #footer>
            <div class="flex justify-center mt-6">
              <BaseButton type="submit"  class="buttonStyle" label="Submit" /> 
           </div>
          </template>
        </CardBox>

        <CardBox is-form @submit.prevent="updatePassword" v-if="form">
          <FormField
            label="Current password"
            
          >
            <FormControl
            v-model="password"
              class="password-input"
              type="password"
            :icon="mdiLock"
            name="password"
            placeholder="Enter your password"
            autocomplete="current-password"
            required
            />
          </FormField>

          <BaseDivider />

          <FormField label="New password" >
            <FormControl
             v-model="new_password"
            class="password-input"
              type="password"
            :icon="mdiLock"
            name="password"
            placeholder="Enter your new password"
            autocomplete="current-password"
            required
            />
            
          </FormField>

          <FormField
            label="Confirm password"
            
          >
            <FormControl
            v-model="confirm_password"
            class="password-input"
            type="password"
            :icon="mdiLock"
            name="password"
            placeholder="Confirm your password"
            autocomplete="current-password"
            required
            />
            
          </FormField>

          <template #footer>
            <div class="flex justify-center mt-6">
              <BaseButton type="submit"  class="buttonStyle" label="Submit" />
              
            
           </div>
          </template>
        </CardBox>
      </div>
    </SectionMain>
  </LayoutAuthenticated>
</template>
<script>
import axios from "axios";
import Form from "@/form.js";
import bcrypt from 'bcryptjs';

export default {
  data() {
    return {
      form: null, // Initialize the form object as null
      userInformation: null,
      token: "", 
      previewImage : null,
      password:null,
      new_password:null,// Initialize the token as empty
      confirm_password:null,
      file:null,
    };
  },
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
    getAdmin(csrfToken) {
      // Retrieve the token from the session
      this.token = "{{ session('admin_token') }}";

      if (!this.token) {
        // Handle the case when the token is missing
        console.log("Authentication token is missing.");
        return;
      }

      axios
        .get("/api/getAuthenticatedAdmin", {
          headers: {
            "X-CSRF-TOKEN": csrfToken,
            Authorization: `Bearer ${this.token}`,
          },
        })
        .then((response) => {
          // Handle the response data
          this.userInformation = response.data.admin;
          this.form = new Form(response.data.admin);
        
        })
        .catch((error) => {
          console.log(error);
        });
    },


    async updateAdmin(csrfToken) {
      this.token = "{{ session('admin_token') }}";
      if (!this.token) {
        // Handle the case when the token is missing
        console.log("Authentication token is missing.");
        return;
      }


  // Create a new object that only contains the fields you want to update
  const updatedData = {
    first_name: this.form.first_name,
    last_name: this.form.last_name,
    phone_number: this.form.phone_number,
    email: this.form.email,
    // avatar: this.form.avatar ? this.form.avatar.name : "",
  };
  if (this.file) {
    const base64String = await this.getStringImage(this.file);
    updatedData.avatar = base64String;
  }

  // Create a new FormData object to send the updated form data


  // Make the PUT request with the updated data and the FormData object
  await axios.put(`/api/update_profile/${this.token}`, updatedData,{
          headers: {
            "X-CSRF-TOKEN": csrfToken,
            Authorization: `Bearer ${this.token}`,
        
          
          },
        })
    
  .then(() => {

      swal({
        text: " Profile Updated Successfully!",
        icon: "success",
        closeOnClickOutside: false,
      });
      this.form= new Form(updatedData);
      console.log('form',this.form);
      console.log('update',updatedData);
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
async updatePassword(csrfToken) {
      this.token = "{{ session('admin_token') }}";
      if (!this.token) {
        // Handle the case when the token is missing
        console.log("Authentication token is missing.");
        return;
      }
      console.log('form',this.form.password);
      console.log('pass',this.password);
  //compare the entred password with the current password
bcrypt.compare(this.password, this.form.password)
.then((isMatch) => {
    if (isMatch) {
      if(this.new_password == this.password){
        swal({
          text: "New password must be different from the current password.",
          icon: "error",
          closeOnClickOutside: false,
        });
        return;
      }
      else{
      if (this.new_password.length < 6) {
          swal({
            text: "New password must be at least 6 characters long.",
            icon: "error",
            closeOnClickOutside: false,
          });
          return;
        }
      if (this.new_password !== this.confirm_password) {
        swal({
          text: "New password and confirm password do not match.",
          icon: "error",
          closeOnClickOutside: false,
        });
        return;
      }
      else{
        // Create a new object that only contains the fields you want to update

      const updatedData = {
        password: this.new_password,
      };

  // Create a new FormData object to send the updated form data


  // Make the PUT request with the updated data and the FormData object
   axios.put(`/api/update_password/${this.token}`, updatedData,{
          headers: {
            "X-CSRF-TOKEN": csrfToken,
            Authorization: `Bearer ${this.token}`,
        
          
          },
        })
    
  .then(() => {

      swal({
        text: " Password Updated Successfully!",
        icon: "success",
        closeOnClickOutside: false,
      });
      this.form= new Form(updatedData);
      console.log('update',updatedData.password);
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
  }
}
    } else {
      // Passwords don't match
      swal({
          text: "Current Password is incorrect.",
          icon: "error",
          closeOnClickOutside: false,
        });
        return;
      // Handle the incorrect password case
    }
  })
  .catch((error) => {
    // Handle the error that occurred during comparison
    console.error(error);
  });

    
},
  },
  mounted() {
    // Initialize the form object
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    this.getAdmin(csrfToken);
  },
};
</script>
<style scoped>
.buttonStyle{
  background-color: #0099ff;
  color: white;
  border-radius: 5px;
  width: 50%;
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