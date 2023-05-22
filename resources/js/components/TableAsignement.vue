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
      (row) => row.id === employee.id
    );
  }
};
</script>


<template>
 <CardBoxModal class="flex justify-center items-center h-screen" v-model="isModalActive" v-if="this.Selectedassignment">
  <div class="text-center">
  <h1 class="text-xl font-bold">Assignment Detail</h1>
  </div>
  <div class="mt-4 mx-auto">
    <div class="w-16 h-16 mx-auto rounded-full overflow-hidden">
    <img v-if="this.Selectedassignment.employee?.avatar" :src="this.Selectedassignment.employee?.avatar" alt="employee" class="w-full h-full object-cover">
    <img v-else src="/user.png" alt="default" class="w-full h-full object-cover">
  </div> 
  <p>
    <b>Employee :</b>  
       {{ this.Selectedassignment.employee?.first_name }} {{ this.Selectedassignment.employee?.last_name}}
  </p> 
 <p>
  <b>Elevator:</b>
  {{ this.Selectedassignment.qrcode?.elevator?.name }} at
  {{ this.Selectedassignment.qrcode?.mission }} in
  {{ this.Selectedassignment.qrcode?.elevator?.location?.ville }}
</p>
      <p><b>CheckIn :</b> {{ this.Selectedassignment.time_in }}</p> 
    <p><b>CheckOut :</b> {{ this.Selectedassignment.time_out }}</p>
    <p><b>Period:</b> From {{ this.Selectedassignment.start_date }} To  {{ this.Selectedassignment.end_date }}</p>
    
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
        <th>Start Date</th>
        <th>End Date</th>
        <th>Check In</th>
        <th>Check Out</th>
    
        <th />
      </tr>
    </thead>
    <tbody>
      <tr v-for="assignment in paginatedAssignment" :key="assignment.id_assignment_elevator">
        <TableCheckboxCell
          v-if="checkable"
          @checked="checked($event, assignment)"
        />
       <td data-label="Elevator">
        {{ assignment.qrcode.elevator.location.ville }}  {{ assignment.qrcode.elevator.location.adress }}  {{ assignment.qrcode.elevator.name }} {{ assignment.qrcode.mission }}
        </td>
        <td data-label="Employee">
          {{ assignment.employee.first_name }} {{ assignment.employee.last_name }}
        </td>
        
        <td data-label="Start Date">
          {{ assignment.start_date }}
        </td>
        <td data-label="End Date">
          {{ assignment.end_date }}
        </td>
        <td data-label="Check in">
          {{ assignment.time_in }}
        </td>
        <td data-label="check out">
          {{ assignment.time_out }}
        </td>
        
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons type="justify-start lg:justify-end" no-wrap>
            <BaseButton
              color="info"
              :icon="mdiEye"
              small
              @click="isModalActive = true, getAssignment(assignment)"   
            />
           <BaseButton
            color="success"
            :icon="mdiHumanEdit" 
            small
            :to="'/update-assignment/' + assignment.id_assignment_elevator"
            
           
          />
            <BaseButton
              color="danger"
              :icon="mdiTrashCan"
              small
              @click="confirmDelete(assignment.id_assignment_elevator)"
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
  name: "AsignmentView",
  data() {
    return {
      assignments: [],
      Selectedassignment: {},
      currentPage: 0,
      pageSize: 10,

    };

  },
  methods: {
    async getAssignments() {
      await axios.get('api/assignmentElevator')
        .then(response => this.assignments = response.data)
        .catch(error => console.log(error))
    },
    confirmDelete(assignementId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this assignment record!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // User clicked the "Yes" button, so proceed with delete request
            axios.delete('api/delete_asignment/' + assignementId)
                .then(response => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Assignment has been deleted successfully.',
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
async getAssignment(assignment) {
  try {
    const response = await axios.get(`/api/getAssignment/${assignment.id_assignment_elevator}`);
    this.Selectedassignment = response.data[0];
    console.log(this.Selectedassignment.id_assignment_elevator);
   
  } catch (error) {
    console.log(error);
  }
},
  

    
  },
  computed: {
    paginatedAssignment: function () {
      const startIndex = this.currentPage * this.pageSize;
      const endIndex = startIndex + this.pageSize;
      return this.assignments.slice(startIndex, endIndex);
    },
    numPages: function () {
      return Math.ceil(this.assignments.length / this.pageSize);
    },
    currentPageHuman: function () {
      return this.currentPage + 1;
    },
    pagesList: function () {
      return Array.from({length: this.numPages}, (v, k) => k);
    }
  },
  mounted() {
    this.getAssignments();
   
  }
};
</script>