<script setup>
import { computed, ref } from "vue";
import { useMainStore } from "@/stores/main";
import { mdiEye, mdiTrashCan ,mdiHumanEdit} from "@mdi/js";
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

const checked = (isChecked, employee) => {
  if (isChecked) {
    checkedRows.value.push(employee);
  } else {
    checkedRows.value = remove(
      checkedRows.value,
      (row) => row.id === employee.id_employee
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
        <th ></th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Birthday</th>
        <th>CIN</th>
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
    <img :src="employee.avatar" alt="Avatar" style="width: 100%; height: 100%; display: block;">
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
          {{ employee.birthday_date }}
        </td>
        <td data-label="CIN">
          {{ employee.cin }}
        </td>
        <!--<td data-label="Created" class="lg:w-1 whitespace-nowrap">
          <small
            class="text-gray-500 dark:text-slate-400"
            :title="employee.created_at"
            >{{ employee.created_at}}</small
          >
        </td>-->
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons type="justify-start lg:justify-end" no-wrap>
            <BaseButton
              color="info"
              :icon="mdiEye"
              small
              @click="isModalActive = true"
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

export default {
  name: "EmployeeView",
  data() {
    return {
      employees: [],
      currentPage: 0,
      pageSize: 5,
      EMPLOYEE_API_BASE_URL: "http://localhost/AttendMe_Admin/public/api/employees",
    };

  },
  methods: {
    async getEmployees() {
      await axios.get(this.EMPLOYEE_API_BASE_URL)
        .then(response => this.employees = response.data)
        .catch(error => console.log(error))
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