<script setup>
import { mdiPlus, mdiLock,mdiElevatorPassengerOutline, mdiEye, mdiTrashCan ,mdiHumanEdit}from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";
import CardBox from "@/components/CardBox.vue";
import LayoutAuthenticated from "@/auth/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import BaseButton from "@/components/BaseButton.vue";
import axios from 'axios';
import FormControl from "@/components/FormControl.vue";
import NavBarItemPlain from "@/components/NavBarItemPlain.vue";
import { computed, ref } from "vue";
import { useMainStore } from "@/stores/main";
import CardBoxModal from "@/components/CardBoxModal.vue";
import TableCheckboxCell from "@/components/TableCheckboxCell.vue";
import BaseLevel from "@/components/BaseLevel.vue";
import BaseButtons from "@/components/BaseButtons.vue";
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

const exportData = () => {
  axios.get('api/export_elevators', { responseType: 'blob' })
    .then(response => {
      handleFileDownload(response.data);
    })
    .catch(error => {
      console.error(error);
    });
};

const handleFileDownload = (fileData) => {
  const url = window.URL.createObjectURL(new Blob([fileData]));
  const link = document.createElement('a');
  link.href = url;
  link.setAttribute('download', 'elevators.xlsx');
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};
const exportToPDF = () => {
  axios.get('api/export_elevators_pdf', { responseType: 'blob' })
    .then(response => {
      const fileData = response.data;
      handlePDFDownload(fileData);
    })
    .catch(error => {
      console.error(error);
    });
};
const handlePDFDownload = (fileData) => {
  const url = window.URL.createObjectURL(new Blob([fileData]));
  const link = document.createElement('a');
  link.href = url;
  link.setAttribute('download','elevators.pdf');
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};


</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <NavBarItemPlain use-margin>
        <FormControl
      v-model="searchTerm"
      placeholder="Search"
      ctrl-k-focus
      transparent
      borderless
      class="w-full"
      @input="getElevators"
    />
</NavBarItemPlain>
      <SectionTitleLineWithButton :icon="mdiElevatorPassengerOutline" title="Elevators" main>
        <div class="space-x-3">
        <div class="dropdown dropdown-bottom ml-2">
  <label tabindex="0" class="btn m-1 text-white ml-auto">  <i class="fas fa-download mr-1"></i>Export</label>
  <ul tabindex="0" class="dropdown-content menu p-1 shadow bg-white rounded-box w-40">
    <li class="flex items-center px-0">
      <a @click="exportData" class="text-green-300 px-3 font-bold"><i class="fas fa-file-excel mr-1"></i> To Excel</a>
    </li>
    <li class="border-t my-1"></li>
    <li class="flex items-center px-0">
      <a class="text-red-400 px-3 font-bold" @click="exportToPDF"><i class="fas fa-file-pdf mr-1"></i> To PDF</a>
    </li>
  </ul>
</div>
        <router-link to="/add-elevator">
          <BaseButton
            target="_blank"
            :icon="mdiPlus"
            label="Add New Elevator"
            color="contrast"
            rounded-full
            small
            class="font-bold"
          />
        </router-link>
        </div>
      </SectionTitleLineWithButton>

      <CardBox has-table>
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
        <td data-label="Id">
          {{ elevator.id_elevator }}
        </td>
        <td data-label="Name">
          {{ elevator.name }}
        </td>
        <td data-label="Location">
        {{ elevator.location.ville }} ,{{ elevator.location.adress }} ,  {{ elevator.location.longitude }} / {{ elevator.location.latitude }}
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
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
<script>

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
      searchTerm: "",
    };

  },
  methods: {
    async getElevators() {
      const params = {
        search: this.searchTerm, // Use debounced search term
      };
      await axios.get(this.ELEVATOR_API_BASE_URL, { params })
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
