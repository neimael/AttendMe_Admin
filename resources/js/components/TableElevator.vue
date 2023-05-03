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

const checked = (isChecked, elevator) => {
  if (isChecked) {
    checkedRows.value.push(elevator);
  } else {
    checkedRows.value = remove(
      checkedRows.value,
      (row) => row.id === elevator.id_elevator
    );
  }
};
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
     <th>Id</th>
        <th>Name</th>
        <th>Location</th>
       
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
          {{ elevator.id_elevator }}
        </td>
        <td data-label="Name">
          {{ elevator.name }}
        </td>
        <td data-label="Location">
        {{ elevator.location.ville }},{{ elevator.location.adress }},{{ elevator.location.longitude }}-{{ elevator.location.latitude }}
        </td>
              
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons type="justify-start lg:justify-end" no-wrap>
            <BaseButton
              color="info"
              :icon="mdiEye"
              small
              :to="'/detail-elevator/' + elevator.id_elevator"
            />
           <BaseButton
            color="success"
            :icon="mdiHumanEdit" 
            small
            :to="'/update-elevator/' + elevator.id_elevator"
           
          />
            <BaseButton
              color="danger"
              :icon="mdiTrashCan"
              small
              @click="confirmDelete(elevator.id_elevator)"
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
      ELEVATOR_API_BASE_URL: "api/get_all",
    
    };

  },
  methods: {
    async getElevators() {
      await axios.get(this.ELEVATOR_API_BASE_URL)
        .then(response => this.elevators = response.data)
        
        .catch(error => console.log(error))
      
    },
    async getElevator(elevator) {
  try {
    const response = await axios.get(`api/get_elevator/${elevator.id_elevator}`);
    this.Selectedelevator = response.data;
    console.log(this.Selectedelevator.id_elevator);
   
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
    this.getElevators();
  }
};
</script>