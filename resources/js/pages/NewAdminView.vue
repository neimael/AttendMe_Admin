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
        <CardBox form @submit.prevent="submit">
            <FormField>
          <FormField label="FirstName">
            <FormControl type="name" v-model="name" required/>
          </FormField>
          <FormField label="LastName">
            <FormControl type="name" v-model="name" required/>
          </FormField>
            </FormField>
            <FormField>
          <FormField label="Email">
            <FormControl type="email" v-model="email" required/>
          </FormField>
          <FormField label="Phone Number">
            <FormControl type="phone" v-model="email" required/>
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
             <div class="flex justify-center">
            <BaseButtons > 
                <BaseButton  type="reset" color="warning" outline label="Reset"/>
              <BaseButton  type="submit" color="warning" label="Add" @click="addAdmin"/>
             
            </BaseButtons>
        </div>
          </template>
        </CardBox>
      </div>
    </SectionMain>


  </LayoutAuthenticated>
</template>

