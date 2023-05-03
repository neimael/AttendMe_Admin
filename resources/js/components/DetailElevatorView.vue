<script setup>
import { computed, ref } from "vue";
import { useMainStore } from "@/stores/main";
import { mdiEye, mdiTrashCan ,mdiHumanEdit} from "@mdi/js";
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
     
        <th>Name</th>
        <th>Location</th>
        <th>Area</th>
        <th>Qr Code</th>
       
        <th />
      </tr>
    </thead>
    <tbody>
      <tr v-for="elevator in paginatedElevators" :key="elevator.id_elevator">
        <TableCheckboxCell
          v-if="checkable"
          @checked="checked($event, elevator)"
        />
       
        <td data-label="Name">
          {{ elevator.elevator.name }}
        </td>
        <td data-label="Location">
        {{ elevator.elevator.location.ville }}, {{ elevator.elevator.location.adress }},  {{ elevator.elevator.location.longitude }}-{{ elevator.elevator.location.latitude }}
        </td>
        <td data-label="Area">
          {{ elevator.mission }}
        </td>
        <td data-label="Qr Code"> 
          <div style="width: 100px; height: 100px; overflow: hidden;">
          <img v-if="elevator.qr_code"  :src="'/storage/' + elevator.qr_code" alt="qrcode" >
          <img v-else src="/storage/EmployeeAvatar/default.png" alt="default" class="w-full h-full object-cover">
          </div>
        </td>
        
        
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons type="justify-start lg:justify-end" no-wrap>
         
            <BaseButton
              color="danger"
              :icon="mdiTrashCan"
              small
              @click="isModalDangerActive = true"
            />
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
      elevators: [],
      qrcode:[],
      Selectedelevator: {},
      currentPage: 0,
      pageSize: 10,
      ELEVATOR_API_BASE_URL: "api/elevators",
    
    };

  },
  methods: {
  
    async getElevator() {
    const id = this.$route.params.id;
  try {
    const response = await axios.get(`api/get_elevator/${id}`);
    this.elevators = response.data;
    console.log(this.elevators.id_elevator);
   
  } catch (error) {
    console.log(error);
  }
},
    confirmDelete(elevatorId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this elevator record!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // User clicked the "Yes" button, so proceed with delete request
            axios.delete('api/delete_elevator/' + elevatorId)
                .then(response => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Elevator has been deleted successfully.',
                        icon: 'success'
                    });
                    // Reload the page to reflect the updated admin list
                    location.reload();
                })
                .catch(error => {
                    console.log(error);
                });
        }
    });
}


  },
  computed: {
    paginatedElevators: function () {
      const startIndex = this.currentPage * this.pageSize;
      const endIndex = startIndex + this.pageSize;
      return this.elevators.slice(startIndex, endIndex);
    },
    numPages: function () {
      return Math.ceil(this.elevators.length / this.pageSize);
    },
    currentPageHuman: function () {
      return this.currentPage + 1;
    },
    pagesList: function () {
      return Array.from({length: this.numPages}, (v, k) => k);
    },
    
  },
  mounted() {
    this.getElevator();
  }
};
</script>