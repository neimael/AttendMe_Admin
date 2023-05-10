<script setup>
import { reactive } from "vue";
import { useRouter } from "vue-router";
import { mdiAccount, mdiAsterisk } from "@mdi/js";
import SectionFullScreen from "@/components/SectionFullScreen.vue";
import CardBox from "@/components/CardBox.vue";
import FormCheckRadio from "@/components/FormCheckRadio.vue";
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import LayoutGuest from "@/auth/LayoutGuest.vue";
import { useStyleStore } from "@/stores/style.js";
import axios from "axios";
import swal from 'sweetalert';

const styles = ["white", "basic"];

const styleStore = useStyleStore();

styleStore.setDarkMode(false);

const router = useRouter();

const form = reactive({
  login: "",
  pass: "",
  remember: true,
});

const submit = async () => {
  try {
    const response = await axios.post("/api/loginAdmin", {
      email: form.login,
      password: form.pass,
    });

    // Handle the response
    console.log(response.data);
    // You can store the admin data in your Vue.js component's data or Vuex store

    // Redirect to the desired route after successful login
    router.push("/");
  } catch (error) {
    //add swal 
    swal({
            text: "Your Credentials are incorrect \n Please Try again!",
            icon: "error",
            closeOnClickOutside: false,
          });
    // Handle the error
    console.error(error.response.data);
  }
};
</script>


<template>
  <LayoutGuest>
    <SectionFullScreen v-slot="{ cardClass }" class="custom-section">
      <CardBox :class="[cardClass, 'custom-card']" is-form @submit.prevent="submit" >
        <FormField label="Login" help="Please enter your login">
          <FormControl
            v-model="form.login"
            :icon="mdiAccount"
            name="login"
            autocomplete="username"
          />
        </FormField>

        <FormField label="Password" help="Please enter your password">
          <FormControl
            v-model="form.pass"
            :icon="mdiAsterisk"
            type="password"
            name="password"
            autocomplete="current-password"
          />
        </FormField>

        <FormCheckRadio
          v-model="form.remember"
          name="remember"
          label="Remember"
          :input-value="true"
        />

        <template #footer>
          <div class="flex justify-center mt-6">
            <BaseButtons>
              <BaseButton type="submit"  label="Login" class="mr-2" :style="{ backgroundColor: '#87CEFA' }"/>
              <BaseButton  outline label="Back"  />
      
            </BaseButtons>
          </div>
        </template>
      </CardBox>
    </SectionFullScreen>
  </LayoutGuest>
</template>
<style>
.custom-card {
  width: 600px; 
  background: transparent;
  opacity: 1;
  border: 2px solid #d1d0d0;
  border-radius: 5;
  
  /* Adjust the width value as per your requirements */
}
.custom-section {
  background-image: url('/view-lift.jpg');
 /* Replace with the path to your image */
  background-size: cover;
  background-position: center;
  opacity: 1;
}
</style>
