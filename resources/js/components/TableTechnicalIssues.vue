<script setup>
import { computed, ref } from "vue";
import { useMainStore } from "@/stores/main";
import { mdiEye, mdiTrashCan ,mdiHumanEdit,mdiDownload,mdiClose,mdiCheck} from "@mdi/js";
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

const items = computed(() => mainStore.clients);

const isModalActive = ref(false);

const isModalDangerActive = ref(false);

const perPage = ref(5);

const currentPage = ref(0);

const checkedRows = ref([]);

const itemsPaginated = computed(() =>
  items.value.slice(
    perPage.value * currentPage.value,
    perPage.value * (currentPage.value + 1)
  )
);

const numPages = computed(() => Math.ceil(items.value.length / perPage.value));

const currentPageHuman = computed(() => currentPage.value + 1);

const pagesList = computed(() => {
  const pagesList = [];

  for (let i = 0; i < numPages.value; i++) {
    pagesList.push(i);
  }

  return pagesList;
});

const remove = (arr, cb) => {
  const newArr = [];

  arr.forEach((item) => {
    if (!cb(item)) {
      newArr.push(item);
    }
  });

  return newArr;
};

const checked = (isChecked, client) => {
  if (isChecked) {
    checkedRows.value.push(client);
  } else {
    checkedRows.value = remove(
      checkedRows.value,
      (row) => row.id === client.id
    );
  }
};
</script>

<template>
 <CardBoxModal class="flex justify-center items-center h-screen" v-model="isModalActive" title="View Detail Regulation">
  <div class="mt-4 ml-24">

    <p><b>Check In :</b> {{ Selectedregulation.check_in }}</p>
    <p><b>Check Out :</b> {{ Selectedregulation.check_out }}</p>
    <!-- <p><b>Employee's name : </b> {{ Selectedregulation.employee.first_name }} {{ Selectedregulation.employee.last_name }}</p>
    <p><b>Employee's cin :</b>  {{ Selectedregulation.employee.cin }}</p>  -->
    <p><b>Issue Type:</b> {{ Selectedregulation.issue_type }}</p>
    <p><b>Status :</b> {{ Selectedregulation.status }}</p>
    <p><b>Regulation Date :</b> {{ Selectedregulation.regulation_day }}</p>
    <p><b>Report :</b> {{ Selectedregulation.report }}</p>
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
</template>
<script>
import axios from 'axios';
import Swal from "sweetalert2";

export default {
  name: "RegulationView",
  data() {
    return {
      regulations: [],
      Selectedregulation: {},
      currentPage: 0,
      pageSize: 10,
     
    };

  },
  methods: {
    async getRegulations() {
      await axios.get('api/presence_regulations')
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
    const response = await axios.get(`api/get_presence_regulation/${regulation.id_presence_regulation}`);
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
