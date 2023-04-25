<script setup>

import {mdiPlus, mdiLock,mdiOrderBoolAscendingVariant} from "@mdi/js";
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
        title="New Assignment"
        main
      >
        <router-link to="/assignment">
          <BaseButton

            target="_blank"
            :icon="mdiOrderBoolAscendingVariant"
            label="Show Assignment"
            color="contrast"
            rounded-full
            small
          />
        </router-link>
      </SectionTitleLineWithButton>
      <div class="container w-7/12 mx-auto">
        <CardBox form @submit.prevent="submit">
            <FormField>
          <FormField label="Employee">
            <FormControl type="name" v-model="name" required/>
          </FormField>
          <FormField label="Elevator">
            <FormControl type="name" v-model="name" required/>
          </FormField>
            </FormField>
            <FormField>
          <FormField label="Start Date">
            <FormControl type="date" v-model="email" required/>
          </FormField>
          <FormField label="End Date">
            <FormControl type="date" v-model="email" required/>
          </FormField>
        </FormField>
        <FormField>
          <FormField label="Start Day">
            <FormControl type="time" v-model="password" required/>
          </FormField>
          <FormField label="End Day">
            <FormControl type="time" v-model="password" required/>
          </FormField>
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

