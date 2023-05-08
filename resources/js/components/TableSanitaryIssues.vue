<script setup>
import { computed, ref } from "vue";
import { useMainStore } from "@/stores/main";
import { mdiEye, mdiTrashCan ,mdiHumanEdit,mdiDownload} from "@mdi/js";
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
  </div>
</template>
<script>
import axios from 'axios';
import Swal from "sweetalert2";

export default {
  name: "SanitaryView",
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
      await axios.get('api/sanitaryIssues')
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
