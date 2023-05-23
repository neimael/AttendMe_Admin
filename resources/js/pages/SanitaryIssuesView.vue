<script setup>
import { mdiPlus, mdiLock,mdiElevatorPassengerOutline,mdiListBoxOutline
}
  from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";

import TableSanitaryIssues from "@/components/TableSanitaryIssues.vue";

import FormControl from "@/components/FormControl.vue";

import CardBox from "@/components/CardBox.vue";
import LayoutAuthenticated from "@/auth/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import BaseButton from "@/components/BaseButton.vue";
import axios from 'axios';
import { computed, ref } from "vue";
import { useMainStore } from "@/stores/main";
import { mdiEye, mdiTrashCan ,mdiHumanEdit,mdiDownload} from "@mdi/js";
import CardBoxModal from "@/components/CardBoxModal.vue";
import TableCheckboxCell from "@/components/TableCheckboxCell.vue";
import BaseLevel from "@/components/BaseLevel.vue";
import BaseButtons from "@/components/BaseButtons.vue";
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

const checked = (isChecked, regulation) => {
  if (isChecked) {
    checkedRows.value.push(regulation);
  } else {
    checkedRows.value = remove(
      checkedRows.value,
      (row) => row.id === regulation.id
    );
  }
};
const exportData = () => {
  axios.get('api/export_sanitaries', { responseType: 'blob' })
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
  link.setAttribute('download', 'sanitaries.xlsx');
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};
const exportToPDF = () => {
  axios.get('api/export_sanitaries_pdf', { responseType: 'blob' })
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
  link.setAttribute('download', 'sanitaries.pdf');
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};



</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <FormControl
      v-model="searchTerm"
      placeholder="Search"
      ctrl-k-focus
      transparent
      borderless
      class="w-full"
      @input="getRegulations"
    />
      <SectionTitleLineWithButton :icon="mdiListBoxOutline" title="Sanitary Issues Regulations" main>
       <!--<router-link to="/add-manual-attendance">
          <BaseButton
            target="_blank"
            :icon="mdiElevatorPassengerOutline"
            label="Add new elevator"
            color="contrast"
            rounded-full
            small
          />
        </router-link>-->
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
        <th>Employee</th>
        <th>Report</th>
        <th>Date</th>
        <th>Certificate</th>
       
       
      </tr>
    </thead>
    <tbody>
      <tr v-for="regulation in paginatedRegulations" :key="regulation.id_presence_regulation">
        <TableCheckboxCell
          v-if="checkable"
          @checked="checked($event, regulation)"
        />
        <td data-label="Id">
          {{ regulation.id_sanitary }}
        </td>
       
        <td data-label="Employee">
          {{regulation.employee.first_name}} {{regulation.employee.last_name}}
        </td>
        <td data-label="report">
          {{ regulation.report }}
        </td>
        
        <td data-label="Date" class="lg:w-1 whitespace-nowrap">
          <small
            class="text-gray-500 dark:text-slate-400"
            :title="regulation.created_at"
            >{{regulation.created_at}}</small
          >
        </td>
        <td data-label="certificate">
  <a class="ml-1"  :href="regulation.certificate" download>
     <BaseButton
              color="success"
              :icon="mdiDownload"
              small
            
            />
  </a>

  <a  class="ml-6" :href="regulation.certificate" target="_blank">
     <BaseButton
              color="info"
              :icon="mdiEye"
              small
            
            />
  </a>
  
</td>
     <!--  <span v-if="regulation.certificate.endsWith('.pdf')">
        <mdi-icon name="file-pdf" />
      </span>
      <span v-else-if="regulation.certificate.endsWith('.png') || regulation.certificate.endsWith('.jpg') || regulation.certificate.endsWith('.jpeg')">
        <mdi-icon name="file-image" />
      </span>
      <span v-else>
        {{ regulation.certificate }}
      </span> -->
   
      
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
  </div>      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
<script>
import Swal from "sweetalert2";

export default {
  name: "SanitaryView",
  data() {
    return {
      regulations: [],
      searchTerm: "",
      Selectedregulation: {},
      currentPage: 0,
      pageSize: 10,
     
    };

  },
  methods: {
    async getRegulations() {
      const params = {
        search: this.searchTerm, // Use debounced search term
      };
      await axios.get('api/sanitaryIssues', { params })
        .then(response => this.regulations = response.data)
        .catch(error => console.log(error))
    },
    
    

  async getRegulation(regulation) {
  try {
    const response = await axios.get(`api/get_sanitary_issue/${regulation.id_presence_regulation}`);
    this.Selectedregulation = response.data;
  } catch (error) {
    console.log(error);
  }
},

  },
  computed: {
    paginatedRegulations: function () {
      const startIndex = this.currentPage * this.pageSize;
      const endIndex = startIndex + this.pageSize;
      return this.regulations.slice(startIndex, endIndex);
    },
    numPages: function () {
      return Math.ceil(this.regulations.length / this.pageSize);
    },
    currentPageHuman: function () {
      return this.currentPage + 1;
    },
    pagesList: function () {
      return Array.from({length: this.numPages}, (v, k) => k);
    }
  },
  mounted() {
    this.getRegulations();
   
  }
};
</script>
