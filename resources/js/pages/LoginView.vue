<script setup>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { mdiAccount, mdiLock } from "@mdi/js";
import SectionFullScreen from "@/components/SectionFullScreen.vue";
import CardBox from "@/components/CardBox.vue";
import FormCheckRadio from "@/components/FormCheckRadio.vue";
import FormField from "@/components/FormField.vue";
import FormControlLogin from "@/components/FormControlLogin.vue";
import FormControlPass from "@/components/FormControlPass.vue";
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
const click = (slug) => {
  router.push("/dashboard");
};

const loading = ref(false); // Loading indicator state

const form = reactive({
  login: "",
  password: "",
});

const submit = async () => {
  try {
    loading.value = true; // Show loading indicator

    const response = await axios.post("/api/loginAdmin", {
      email: form.login,
      password: form.password,
    });

    // Handle the response
    console.log(response.data);
    // You can store the admin data in your Vue.js component's data or Vuex store

    // Redirect to the desired route after successful login
    router.push("/dashboard");
  } catch (error) {
    //add swal 
    swal({
      text: "Your credentials are incorrect. Please try again!",
      icon: "error",
      closeOnClickOutside: false,
    });
    // Handle the error
    console.error(error.response.data);
  } finally {
    loading.value = false; // Hide loading indicator
  }
};
</script>


<template>
  <LayoutGuest>
    <SectionFullScreen v-slot="{ cardClass }" class="custom-section">
      <CardBox :class="[cardClass, 'custom-card']" is-form @submit.prevent="submit" >
        <FormField label="Email" style="color:white" >
          <FormControlLogin
            v-model="form.login"
            :icon="mdiAccount"
            name="login"
            autocomplete="email"
            placeholder="Enter your email"
            required
          />
        </FormField>

        <FormField label="Password" style="color:white">
          <FormControlPass
            v-model="form.password"
            type="password"
            :icon="mdiLock"
            name="password"
            placeholder="Enter your password"
            autocomplete="current-password"
            required
          />
        </FormField>

        <template #footer>
          <div class="flex justify-center mt-6">
            <BaseButton
              type="submit"
              label="Login"
              class="buttonStyle"
              :disabled="loading" 
            >
              <!-- Show loading indicator when loading is true -->
              <span v-if="loading" class="spinner"></span>
              <span v-else>Login</span>
            </BaseButton>
          </div>
        </template>
      </CardBox>
    </SectionFullScreen>
  </LayoutGuest>
</template>
<style>
.spinner {
  display: inline-block;
  width: 20px;
  height: 20px;
  border: 3px solid rgba(255, 255, 255, 0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spinner 1s linear infinite;
}

@keyframes spinner {
  to {
    transform: rotate(360deg);
  }
}
.custom-card {
  width: 600px; 
  background: transparent;
  opacity: 1;
  border: 2px solid #d1d0d0;
  border-radius: 5;
  
  /* Adjust the width value as per your requirements */
}
.custom-section {
  background-image: linear-gradient(to right, #eea2a2 0%, #bbc1bf 19%, #57c6e1 42%, #b49fda 79%, #7ac5d8 100%);  background-position: center;
  opacity: 1;
}
.buttonStyle{
  background-color: #8778a3;
  color: white;
  border-radius: 5px;
  width: 50%;
  font-size: 20px;
  font-weight: 500;

  border:none;
  
}
.buttonStyle:hover{
  background-color: #605574;
  color: white;
  border-radius: 5px;

 
  font-size: 20px;
  font-weight: 500;
  border:none;
  
}
</style>
