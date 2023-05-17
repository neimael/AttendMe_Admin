<script setup>
import { computed, ref } from "vue";
import { useMainStore } from "@/stores/main";
import { mdiEye, mdiTrashCan ,mdiHumanEdit,mdiDownload,mdiQrcodeScan} from "@mdi/js";
import CardBoxModal from "@/components/CardBoxModal.vue";
import TableCheckboxCell from "@/components/TableCheckboxCell.vue";
import BaseLevel from "@/components/BaseLevel.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import BaseButton from "@/components/BaseButton.vue";

defineProps({
  checkable: Boolean,
});

</script>

<template>
  
  <CardBoxModal v-model="isModalActive" title="Sample modal">
    <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
    <p>This is sample modal</p>
  </CardBoxModal>

  <CardBoxModal
    v-model="isModalDangerActive"
    title="Please confirm"
    button="danger"
    has-cancel
  >
    <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
    <p>This is sample modal</p>
  </CardBoxModal>

 



  <table>
    <thead>
      <tr>
        <th v-if="checkable" />
        <th>Elevator</th>

        <th>Check In</th>
        <th>Check Out</th>
        <th>Selfie</th>
        <th>Qr Code</th>
        <th>Attendance Day</th>
        <th>Status</th>
        <th />
      </tr>
    </thead>
    <tbody>
      <tr v-for="presence in paginatedPresences" :key="presence.id_presence">
        <TableCheckboxCell
          v-if=" checkable"
          @checked="checked($event, client)"
        />
        
        <td data-label="Elevator"  class="text-center font-bold">
          {{ presence.elevator? presence.elevator : '---' }}
        </td>
       
        <td data-label="CheckIn" class="text-center font-bold">
          {{ presence.check_in? presence.check_in : '---' }}
        </td>
        <td data-label="CheckOut" class="text-center font-bold">
          {{ presence.check_out ? presence.check_out : '---' }}
        </td>
        <td data-label="Selfie" class="border-b-0 lg:w-6 before:hidden">
          
          <div style="width: 70px; height: 70px; border-radius: 40%; overflow: hidden;">
            <img v-if="presence.selfie" :src="presence.selfie" alt="selfie" class="w-full h-full object-cover">
            <img v-else src="/user.png" alt="default" class="w-full h-full object-cover">
         </div>

        </td>
        <!-- <td data-label="Status">
        <div v-if="property.status == 'Available'" class="badge badge-success">On Time</div>-->
        <!-- <div  class="badge badge-error">Late</div>
      </td> --> 
        <td data-label="Qrcode">
          <div style="width: 90px; height: 90px; overflow: hidden;">
            <img v-if="presence.qrcode" :src="presence.qrcode" alt="qrcode" class="w-full h-full object-cover">
            <img v-else src="/qrcode.png" alt="qrcode" class="w-full h-full object-cover">
            </div>
        </td>
        <td data-label="Created" class="lg:w-1 whitespace-nowrap">
          <small
            class="text-gray-500 dark:text-slate-400"
            :title="presence.attendance_day"
            >{{ presence.day }}</small>
        </td>
         <td data-label="Status">
        <div v-if="presence.status == 'Absent'" class="badge badge-error font-bold text-white">Absent</div>
        <div v-if="presence.status == 'On Time'" class="badge badge-success font-bold text-white">On Time</div>
        <div v-if="presence.status == 'Late'" class="badge badge-warning font-bold text-white">Late</div>
        
      </td> 
        <td class="before:hidden lg:w-1 ">
          <BaseButtons type="justify-between " no-wrap v-if="presence.qrcode">
          
            <a  :href="presence.qrcode" download  v-if="presence.qrcode">
     <BaseButton
              color="success"
              :icon="mdiDownload"
              small
            
            />
  </a> 
             <a class="ml-3 "  :href="presence.qrcode" target="_blank" v-if="presence.qrcode">
     <BaseButton
              color="warning"
              :icon="mdiQrcodeScan"
              small
            
            />
         </a> 
          </BaseButtons>
        </td>
      </tr>
    </tbody>
  </table>
  <div class="p-3 lg:px-6 border-t border-gray-100 dark:border-slate-800">
    <BaseLevel>
      <BaseButtons>
        <BaseButton
          v-for="page in pagesList"
          :key="page"
          :active="page === currentPage"
          :label="page + 1"
          :color="page === currentPage ? 'lightDark' : 'whiteDark'"
          small
          @click="currentPage = page"
        />
      </BaseButtons>
      <small>Page {{ currentPageHuman }} of {{ numPages }}</small>
    </BaseLevel>
  </div>
</template>
<script>
import axios from 'axios';
import Swal from 'sweetalert2'

export default {
  name: "ElevatorView",
  data() {
    return {
      presences: [],
      currentPage: 0,
      pageSize: 10,    
    };

  },
  methods: {
    getPresenceData() {
        const id = this.$route.params.id; // Replace with the actual employee ID
      
      axios
        .get(`/api/get_employee_presence/${id}`)
        .then(response => {
          this.presences = response.data;
          console.log(this.presences); // Display the retrieved data in the console
        })
        .catch(error => {
          console.error(error);
        });
    },

  },
  computed: {
    paginatedPresences: function () {
      const startIndex = this.currentPage * this.pageSize;
      const endIndex = startIndex + this.pageSize;
      return this.presences.slice(startIndex, endIndex);
    },
    numPages: function () {
      return Math.ceil(this.presences.length / this.pageSize);
    },
    currentPageHuman: function () {
      return this.currentPage + 1;
    },
    pagesList: function () {
      return Array.from({length: this.numPages}, (v, k) => k);
    },
    
  },
  mounted() {
   this.getPresenceData();
  }
};
</script>