<script setup>
import { mdiPlus, mdiLock, mdiExportVariant ,mdiAccountMultiple } from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";

import CardBox from "@/components/CardBox.vue";
import LayoutAuthenticated from "@/auth/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import axios from 'axios';
import FormControl from "@/components/FormControl.vue";
import NavBarItemPlain from "@/components/NavBarItemPlain.vue";
import { ref, watch } from 'vue';

import { useMainStore } from "@/stores/main";
import { mdiEye, mdiTrashCan ,mdiHumanEdit, mdiQrcodeScan,mdiTextAccount} from "@mdi/js";
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

const checked = (isChecked, employee) => {
  if (isChecked) {
    checkedRows.value.push(employee);
  } else {
    checkedRows.value = remove(
      checkedRows.value,
      (row) => row.id === employee.id
    );
  }
};
const exportData = () => {
  axios.get('api/export_employees', { responseType: 'blob' })
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
  link.setAttribute('download', 'employees.xlsx');
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};
const exportToPDF = () => {
  axios.get('api/export_employees_pdf', { responseType: 'blob' })
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
  link.setAttribute('download', 'employees.pdf');
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
      @input="getEmployees"
    />
</NavBarItemPlain>
      <SectionTitleLineWithButton :icon="mdiAccountMultiple" title="Employees" main>
      
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



        <router-link to="/add-employee">
          <BaseButton
            target="_blank"
            :icon="mdiPlus"
            label="Add New Employee"
            color="contrast"
            rounded-full
            small
            class="font-bold"
          />
        </router-link>
        </div>
      </SectionTitleLineWithButton>
     
      <CardBox has-table>
        <CardBoxModal class="flex justify-center items-center h-screen" v-model="isModalActive" title="">
  <div class="text-center">
  <h1 class="text-xl font-bold">Employee Detail</h1>
  </div>
  <div class="w-32 h-32  mx-auto rounded-full overflow-hidden">
    <img v-if="Selectedemployee.avatar" :src=" Selectedemployee.avatar" alt="employee" class="w-full h-full object-cover">
    <img v-else src="user.png" alt="default" class="w-full h-full object-cover">
  </div>
  <div class="mt-4 ml-7">
    <p class="font-bold"><b>Name :</b> {{ Selectedemployee.first_name }} {{ Selectedemployee.last_name }}</p>
    <p><b>CIN :</b>  {{ Selectedemployee.cin }}</p>
    <p><b>Address :</b> {{ Selectedemployee.adress }}</p>
    <p><b>Birthday :</b> {{ Selectedemployee.birthday }}</p>
    <p><b>Phone Number:</b> {{ Selectedemployee.phone_number }}</p>
    <p><b>Email :</b> {{ Selectedemployee.email }}</p>
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
        <th ></th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Birthday</th>
        <th>CIN</th>
        <th>Address</th>
       <!--<th>Created</th>--> 
        <th />
      </tr>
    </thead>
    <tbody>
      <tr v-for="employee in paginatedEmployees" :key="employee.id">
        <TableCheckboxCell
          v-if="checkable"
          @checked="checked($event, employee)"
        />
        <td class="border-b-0 lg:w-6 before:hidden">
          <div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden;">
            <img v-if="employee.avatar" :src="employee.avatar" alt="employee" class="w-full h-full object-cover">
            <img v-else src="user.png" alt="default" class="w-full h-full object-cover">
            </div>
</td>
        <td data-label="Name">
          {{ employee.first_name }} {{ employee.last_name }}
        </td>
        <td data-label="Email">
          {{ employee.email }}
        </td>
        <td data-label="Phone Number">
          {{ employee.phone_number }}
        </td>
        <td data-label="birthday">
          {{ employee.birthday }}
        </td>
        <td data-label="CIN">
          {{ employee.cin }}
        </td>
        <td data-label="Address">
          {{ employee.adress }}
        </td>
        <!--<td data-label="Created" class="lg:w-1 whitespace-nowrap">
          <small
            class="text-gray-500 dark:text-slate-400"
            :title="employee.created_at"
            >{{ employee.created_at}}</small
          >
        </td>-->
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons  no-wrap>

            <BaseButton
              color="info"
              :icon="mdiEye"
              small
              @click="isModalActive = true ,getEmployee(employee)" 
            />
           <BaseButton
            color="success"
            :icon="mdiHumanEdit" 
            small
            :to="'/update-employee/' + employee.id"
           
          />
            <BaseButton
              color="danger"
              :icon="mdiTrashCan"
              small
              @click="confirmDelete(employee.id)"
            />
            <BaseButton
              color="warning"
              :icon="mdiTextAccount"
              small
              :to="'/employee-presence/' +employee.id"
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
  </div>      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
<script>
import Swal from "sweetalert2";
import { debounce } from 'lodash';

export default {
  name: "EmployeeView",
  data() {
    return {
      employees: [],
      Selectedemployee: {},
      currentPage: 0,
      pageSize: 10,
      EMPLOYEE_API_BASE_URL: "http://localhost/AttendMe_Admin/public/api/employees",
      searchTerm: "",
    };

  },
  methods: {
    async getEmployees() {
      const params = {
        search: this.searchTerm, // Use debounced search term
      };
      await axios.get(this.EMPLOYEE_API_BASE_URL, { params })
        .then(response => this.employees = response.data)
        .catch(error => console.log(error))
    },
    async getEmployee(employee) {
  try {
    const response = await axios.get(`/api/get_employee/${employee.id}`);
    this.Selectedemployee = response.data;
    console.log(this.Selectedemployee.cin);
   
  } catch (error) {
    console.log(error);
  }
},
    confirmDelete(employeeId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this employee record!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // User clicked the "Yes" button, so proceed with delete request
            axios.delete('api/delete_employee/' + employeeId)
                .then(response => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Employee has been deleted successfully.',
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
},
showAllInfo() {
      // format your information here as a string
      const info = `
      HHHHHHHHHHHHHH
      `
      // use SweetAlert2 to display the information
      Swal.fire({
        icon: 'info',
        title: 'All Information',
        html: info,
        confirmButtonText: 'OK'
      })
    }

  },
  
  computed: {
   
   
    paginatedEmployees: function () {
      const startIndex = this.currentPage * this.pageSize;
      const endIndex = startIndex + this.pageSize;
      return this.employees.slice(startIndex, endIndex);
    },
    numPages: function () {
      return Math.ceil(this.employees.length / this.pageSize);
    },
    currentPageHuman: function () {
      return this.currentPage + 1;
    },
    pagesList: function () {
      return Array.from({length: this.numPages}, (v, k) => k);
    }
  },
  mounted() {
    this.getEmployees();
  }
};
</script>

<style>
.yellow-button {
  background-color: yellow;
}
</style>