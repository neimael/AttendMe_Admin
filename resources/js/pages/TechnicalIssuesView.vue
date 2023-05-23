<script setup>
import { mdiPlus, mdiLock,mdiElevatorPassengerOutline,mdiListBoxOutline,mdiPrinterPosWrenchOutline
}
  from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";

import TableTechnicalIssues from "@/components/TableTechnicalIssues.vue";

import FormControl from "@/components/FormControl.vue";

import CardBox from "@/components/CardBox.vue";
import LayoutAuthenticated from "@/auth/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import BaseButton from "@/components/BaseButton.vue";
import axios from 'axios';
import { computed, ref } from "vue";
import { useMainStore } from "@/stores/main";
import { mdiEye, mdiTrashCan ,mdiHumanEdit,mdiDownload,mdiClose,mdiCheck} from "@mdi/js";
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
  axios.get('api/export_regulations', { responseType: 'blob' })
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
  link.setAttribute('download', 'regulations.xlsx');
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};
const exportToPDF = () => {
  axios.get('api/export_regulations_pdf', {responseType: 'blob'})
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
  link.setAttribute('download', 'issues.pdf');
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
      :icon="mdiSearchWeb"
      ctrl-k-focus
      transparent
      borderless
      class="w-full"
      @input="getRegulations"
    />
</NavBarItemPlain>
      <SectionTitleLineWithButton :icon="mdiPrinterPosWrenchOutline" title="Technical Issues Regulations" main>
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
        <!-- <router-link to="/add-manual-attendance">
          <BaseButton
            target="_blank"
            :icon="mdiElevatorPassengerOutline"
            label="Add new elevator"
            color="contrast"
            rounded-full
            small
          />
        </router-link>-->
      </SectionTitleLineWithButton>

      <CardBox has-table>
        <CardBoxModal class="flex justify-center items-center h-screen" v-model="isModalActive" >
 <div class="text-center">
    <h1 class="text-xl font-bold"><b>Regulation Detail </b>  </h1> 
 </div> 
   <div class="mt-4 ml-7">
      <div class="w-16 h-16 mx-auto rounded-full overflow-hidden">
    <img v-if="this.Selectedregulation.employee?.avatar" :src="this.Selectedregulation.employee?.avatar" alt="employee" class="w-full h-full object-cover">
    <img v-else src="/user.png" alt="default" class="w-full h-full object-cover">
  </div> 
    <p><b>Employee :</b> {{ Selectedregulation.employee?.first_name }} {{ Selectedregulation.employee?.last_name }}</p>
    <p><b>Check In : </b> {{ Selectedregulation?.check_in }}</p>
    <p><b>Check Out :</b>{{ Selectedregulation?.check_out }} </p>
    <p><b>Employee's cin :</b>  {{ Selectedregulation.employee?.cin }}</p>
    <p><b>Issue Type:</b> {{ Selectedregulation?.issue_type }}</p>
    <p><b>Status :</b> {{ Selectedregulation?.status }}</p>
    <p><b>Regulation Date :</b> {{ Selectedregulation?.attendance_day }}</p>
  
  <p><b>Report :</b>
   
    {{ Selectedregulation.report }}
  </p></div>
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
       
        <th>Employee</th>
        <th>Report</th>
        <th>Check In</th>
        <th>Check Out</th>
        <th>Issue Type</th>
        <th>Status</th>
        <th>Date</th>
        <th />
      </tr>
    </thead>
    <tbody>
      <tr v-for="regulation in paginatedRegulations" :key="regulation.id_presence_regulation">
        <TableCheckboxCell
          v-if="checkable"
          @checked="checked($event, regulation)"
        />
       
        <td data-label="Employee">
          {{regulation.employee.first_name}} {{regulation.employee.last_name}}
        </td>
        <td data-label="Report">
          {{ regulation.report }}
        </td>
       
        <td data-label="Check In">
          {{ regulation.check_in }}
        </td>
        <td data-label="Check Out">
          {{ regulation.check_out }}
        </td>
       
        <td data-label="Type">
          {{ regulation.issue_type }}
        </td>
        <td data-label="Status">
          <div v-if="regulation.status=='Pending'" class="badge badge-warning">Pending</div>
          <div v-else-if="regulation.status=='Approved'" class="badge badge-success">Approved</div>
          <div v-else class="badge badge-error ">Rejected</div>
        </td>
       
       
        
        <td data-label="Created" class="lg:w-1 whitespace-nowrap">
          <small
            class="text-gray-500 dark:text-slate-400"
            :title="regulation.attendance_day"
            >{{ regulation.attendance_day }}</small
          >
        </td>
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons type="justify-start lg:justify-end" no-wrap>
            <BaseButton
              color="info"
              :icon="mdiEye"
              small
              @click="isModalActive = true ,getRegulation(regulation)" 
            />
          
          <BaseButton v-if="regulation.status=='Pending' || regulation.status=='Rejected'"  
            color="success"
            :icon="mdiCheck"
            small
            @click="ApproveRegulation(regulation .id_presence_regulation)"
          />
          <BaseButton v-if="regulation.status=='Pending' || regulation.status=='Approved'" 
            color="warning"
            :icon="mdiClose"
            small
         @click="RejectRegulation(regulation.id_presence_regulation)"   
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
import Swal from "sweetalert2";

export default {
  name: "RegulationView",
  data() {
    return {
      regulations: [],
      Selectedregulation: {},
      currentPage: 0,
      searchTerm: "",
      pageSize: 10,
     
    };

  },
  methods: {
    async getRegulations() {
      const params = {
        search: this.searchTerm, // Use debounced search term
      };
      await axios.get('api/presence_regulations', { params })
        .then(response => this.regulations = response.data)
        .catch(error => console.log(error))
    },
    
    
ApproveRegulation(regulationId){
  axios.put('api/aprove_presence_regulation/' + regulationId)
                .then(response => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Presence Regulation has been Approved successfully.',
                        icon: 'success'
                    });
                    // Reload the page to reflect the updated admin list
                    location.reload();
                })
                .catch(error => {
                    console.log(error);
                });
},
RejectRegulation(regulationId){
  axios.put('api/reject_presence_regulation/' + regulationId)
                .then(response => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Presence Regulation has been Rejected successfully.',
                        icon: 'success'
                    });
                    // Reload the page to reflect the updated admin list
                    location.reload();
                })
                .catch(error => {
                    console.log(error);
                });
},

  async getRegulation(regulation) {
  try {
    const response = await axios.get(`/api/get_presence_regulation/${regulation.id_presence_regulation}`);
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

