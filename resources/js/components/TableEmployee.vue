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
</script>

<template>
  
  <CardBoxModal class="flex justify-center items-center h-screen" v-model="isModalActive" title="View Detail Employee">
  <div class="w-32 h-32  ml-28 rounded-full overflow-hidden">
    <img v-if="Selectedemployee.avatar" :src=" Selectedemployee.avatar" alt="employee" class="w-full h-full object-cover">
    <img v-else src="user.png" alt="default" class="w-full h-full object-cover">
  </div>
  <div class="mt-4 ml-4">
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
import Swal from "sweetalert2";

export default {
  name: "EmployeeView",
  data() {
    return {
      employees: [],
      Selectedemployee: {},
      currentPage: 0,
      pageSize: 10,
      EMPLOYEE_API_BASE_URL: "http://localhost/AttendMe_Admin/public/api/employees",
    };

  },
  methods: {
    async getEmployees() {
      await axios.get(this.EMPLOYEE_API_BASE_URL)
        .then(response => this.employees = response.data)
        .catch(error => console.log(error))
    },
    async getEmployee(employee) {
  try {
    const response = await axios.get(`api/get_employee/${employee.id}`);
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