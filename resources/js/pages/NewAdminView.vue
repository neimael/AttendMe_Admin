<script setup>

import {mdiPlus, mdiLock} from "@mdi/js";
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
        title="New Admin"
        main
      >
        <router-link to="/admins">
          <BaseButton

            target="_blank"
            :icon="mdiLock"
            label="Show Admins"
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
            <FormControl type="text" v-model="first_name" required/>
          </FormField>
          <FormField label="LastName">
            <FormControl type="text" v-model="last_name" required/>
          </FormField>
            </FormField>
            <FormField>
          <FormField label="Email">
            <FormControl type="email" v-model="email" required/>
          </FormField>
          <FormField label="Phone Number">
            <FormControl type="phone" v-model="phone_number" required/>
          </FormField>
        </FormField>
          <FormField label="Password">
            <FormControl v-model="password" required/>
          </FormField>
          <FormField label="Images">
          <input type="file"
                 id="images"
                 name="images"
                 class="file-input file-input-bordered file-input-warning w-full max-w-xs bg-black text-white"
                 @change="onImageSelected"
                 />
        </FormField>
          <template #footer>
            <BaseButtons >  
               <BaseButton  type="submit" color="warning" label="Add" @click="addAdmin()"/>
                <BaseButton  type="reset" color="warning" outline label="Reset"/>

            </BaseButtons>
       
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
      phone_number: "",
      
      email: "",
      password: "",
    }
  },
  props: [],
  methods: {
    async addAdmin() {
      const newAdmin = {
        first_name: this.first_name,
        last_name: this.last_name,
        phone_number: this.phone_number,
        
        email: this.email,
        password: this.password,
      };
      axios.post('api/add_admin',newAdmin )
        .then(() => {
          swal({
            text: "Admin Added Successfully!",
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


