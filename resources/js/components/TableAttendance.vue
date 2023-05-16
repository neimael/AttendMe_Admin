<script setup>
import { computed, ref } from "vue";
import { useMainStore } from "@/stores/main";
import { mdiEye, mdiTrashCan ,mdiHumanEdit, mdiQrcode, mdiQrcodeScan,mdiDownload} from "@mdi/js";
import CardBoxModal from "@/components/CardBoxModal.vue";
import TableCheckboxCell from "@/components/TableCheckboxCell.vue";
import BaseLevel from "@/components/BaseLevel.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import BaseButton from "@/components/BaseButton.vue";
import UserAvatar from "@/components/UserAvatar.vue";


defineProps({
  checkable: Boolean,
});

const mainStore = useMainStore();
const isModalActive = ref(false);
const isModalDangerActive = ref(false);
const checkedRows = ref([]);
const remove = (arr, cb) => {
  const newArr = [];

  arr.forEach((item) => {
    if (!cb(item)) {
      newArr.push(item);
    }
  });

  return newArr;
};

const checked = (isChecked, presence) => {
  if (isChecked) {
    checkedRows.value.push(presence);
  } else {
    checkedRows.value = remove(
      checkedRows.value,
      (row) => row.id === presence.id_presence
    );
  }
};
</script>

<template>
  <CardBoxModal class="flex justify-center items-center h-screen" v-model="isModalActive" title="View Detail Attendance" v-if="Selectedpresence">
  <div class="w-32 h-32  ml-28 rounded-full overflow-hidden">
    <img v-if="Selectedpresence.selfie" :src=" Selectedpresence.selfie" alt="employee" class="w-full h-full object-cover">
    <img v-else src="user.png" alt="default" class="w-full h-full object-cover">
  </div>
  <div class="mt-4 ml-4">
    <p>
  <b>Elevator:</b>
  {{ this.Selectedpresence.qrcodes?.elevator?.name }} at
  {{ this.Selectedpresence.qrcodes?.mission }} in
  {{ this.Selectedpresence.qrcodes?.elevator?.location?.ville }}
</p>
    <p><b>Employee :</b>     {{ this.Selectedpresence.employee?.first_name }} {{ this.Selectedpresence.employee?.last_name}}</p> 
    <p><b>CheckIn :</b> {{ Selectedpresence.check_in }}</p> 
    <p><b>CheckOut :</b> {{ Selectedpresence.check_out }}</p>
    <p><b>Attendance Day:</b> {{ Selectedpresence.attendance_day }}</p>
    <div class="w-32 h-32  ml-28 overflow-hidden">
    <img v-if="Selectedpresence.qrcode" :src=" Selectedpresence.qrcode" alt="qrcode" class="w-full h-full object-cover">
    <img v-else src="user.png" alt="default" class="w-full h-full object-cover">
  </div>
  </div>

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

  <div v-if="checkedRows.length" class="p-3 bg-gray-100/50 dark:bg-slate-800">
    <span
      v-for="checkedRow in checkedRows"
      :key="checkedRow.id"
      class="inline-block px-2 py-1 rounded-sm mr-2 text-sm bg-gray-100 dark:bg-slate-700"
    >
      {{ checkedRow.name }}
    </span>
  </div>


  <table>
    <thead>
      <tr>
        <th v-if="checkable" />
        
        <th>Elevator</th>
  
        <th>Employee</th>
        <th>Check In</th>
        <th>Check Out</th>
        <th>Selfie</th>
       
        <th>Qr Code</th>
        
        <th>Attendance Day</th>
        <th />
      </tr>
    </thead>
    <tbody>
      <tr v-for="presence in paginatedPresences" :key="presence.id_presence">
        <TableCheckboxCell
          v-if="checkable"
          @checked="checked($event, client)"
        />
        
        <td data-label="Elevator">
          {{ presence.qrcodes.elevator.name}} at  {{ presence.qrcodes.mission}} in {{ presence.qrcodes.elevator.location.ville}}
        </td>
        <td data-label="Employee">
          {{ presence.employee.first_name }} {{ presence.employee.last_name}}
        </td>
        <td data-label="CheckIn">
          {{ presence.check_in }}
        </td>
        <td data-label="CheckOut">
          {{ presence.check_out ? presence.check_out : '---' }}
        </td>
        <td data-label="Selfie" class="border-b-0 lg:w-6 before:hidden">
          
          <div style="width: 70px; height: 70px; border-radius: 40%; overflow: hidden;">
            <img v-if="presence.selfie" :src="presence.selfie" alt="selfie" class="w-full h-full object-cover">
            <img v-else src="user.png" alt="default" class="w-full h-full object-cover">
                     </div>

        </td>
        <!-- <td data-label="Status">
        <div v-if="property.status == 'Available'" class="badge badge-success">On Time</div>-->
        <!-- <div  class="badge badge-error">Late</div>
      </td> --> 
        <td data-label="Qrcode">
          <div style="width: 90px; height: 90px; overflow: hidden;">
            <img v-if="presence.qrcode" :src="presence.qrcode" alt="qrcode" class="w-full h-full object-cover">
            <p v-else > Not yet </p>
            </div>
        </td>
        <td data-label="Created" class="lg:w-1 whitespace-nowrap">
          <small
            class="text-gray-500 dark:text-slate-400"
            :title="presence.attendance_day"
            >{{ presence.attendance_day }}</small>
        </td>
        <td class="before:hidden lg:w-1 ">
          <BaseButtons type="justify-between " no-wrap>
            <BaseButton
              color="info"
              :icon="mdiEye"
              small
              @click="isModalActive = true ,getPresence(presence)" 
            />
            <a  :href="presence.qrcode" download>
     <BaseButton
              color="success"
              :icon="mdiDownload"
              small
            
            />
  </a> 
             <a class="ml-3 "  :href="presence.qrcode" target="_blank">
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
  name: "PresenceView",
  data() {
    return {
      presences: [],
      Selectedpresence: {},
      currentPage: 0,
      pageSize: 10,
      Presence_API_BASE_URL: "api/presences",
      loggedInAdminId: 1 // set to the ID of the currently logged-in admin
    };

  },
  methods: {
    async getPresences() {
      await axios.get(this.Presence_API_BASE_URL)
        .then(response => 
        this.presences = response.data,
      
        )
        .catch(error => console.log(error))
    },
    async getPresence(presence){
      await axios.get(`/api/get_presence/${presence.id_presence}`)
      .then(response => {
        this.Selectedpresence = response.data
        console.log(response.data.qrcodes)
        console.log(this.Selectedpresence.qrcodes.elevator.name)
      })
      .catch(error => console.log(error))
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
    this.getPresences();
  }
};
</script>