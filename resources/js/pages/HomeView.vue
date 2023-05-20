<script setup>
import { computed, ref, onMounted } from "vue";
import { useMainStore } from "@/stores/main";
import {
  mdiAccountMultiple,
  mdiCartOutline,
  mdiChartTimelineVariant,
  mdiMonitorCellphone,
  mdiReload,
  mdiGithub,
  mdiChartPie,
  mdiElevatorPassengerOutline,
mdiFaceRecognition,
mdiScannerOff,
mdiQrcodeScan
} from "@mdi/js";
import * as chartConfig from "@/components/Charts/chart.config.js";
import LineChart from "@/components/Charts/LineChart.vue";
import SectionMain from "@/components/SectionMain.vue";
import CardBoxWidget from "@/components/CardBoxWidget.vue";
import CardBox from "@/components/CardBox.vue";
import TableAttendance from "@/components/TableAttendance.vue";
import NotificationBar from "@/components/NotificationBar.vue";
import BaseButton from "@/components/BaseButton.vue";
import CardBoxTransaction from "@/components/CardBoxTransaction.vue";
import CardBoxClient from "@/components/CardBoxClient.vue";
import LayoutAuthenticated from "@/auth/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import SectionBannerStarOnGitHub from "@/components/SectionBannerStarOnGitHub.vue";
import MyPieChartComponent from "@/components/my-pie-chart.vue";

const chartData = ref(null);

const fillChartData = () => {
  chartData.value = chartConfig.sampleChartData();
};

onMounted(() => {
  fillChartData();
});

const mainStore = useMainStore();

const clientBarItems = computed(() => mainStore.clients.slice(0, 4));

const transactionBarItems = computed(() => mainStore.history);
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiChartTimelineVariant"
        title="Overview"
        main
      >
        
      </SectionTitleLineWithButton>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-6">
        <CardBoxWidget
         
          trend-type="up"
          color="text-emerald-500"
          :icon="mdiAccountMultiple"
          :number="totalEmployees"
          label="Employees"

        />
        <CardBoxWidget
         
          trend-type="down"
          color="text-blue-500"
          :icon="mdiElevatorPassengerOutline"
          :number="totalElevators"
          label="Elevators"
        />
        <CardBoxWidget
    
          color="text-red-500"
          :icon="mdiQrcodeScan"
          :number="totalPresence"
          label="Today's Presence"
        />
      </div>
    
      <div class="grid grid-cols-2 lg:grid-cols-2 gap-6 mb-6">
        <div v-for="client in latest_regulations" :key="client.id_presence_regulation">
  <CardBoxClient
    :name="client.employee ? (client.employee.first_name + ' ' + client.employee.last_name) : ''"
    :login="client.attendance_day"
    :date="client.issue_type"
    :progress="client.status"
    :image="client.employee && client.employee.avatar ? client.employee.avatar : null"
  />
</div>

</div>


      <!-- <div>
      <my-pie-chart :chart-data="chartData" :options="chartOptions" style="width: 400px; height: 400px;"></my-pie-chart>
    </div> -->

      <!-- <SectionTitleLineWithButton :icon="mdiChartPie" title="Trends overview">
        <BaseButton
          :icon="mdiReload"
          color="whiteDark"
          @click="fillChartData"
        />
      </SectionTitleLineWithButton> -->

      <!-- <CardBox class="mb-6">
        <div v-if="chartData">
          <line-chart :data="chartData" class="h-96" />
        </div>
      </CardBox> -->

      <SectionTitleLineWithButton :icon="mdiAccountMultiple" title="Today's Presence" />


      <CardBox has-table>
        <TableAttendance />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
<script>
import axios from 'axios';
import Swal from "sweetalert2";

export default {
  name: "HomeView",
  data() {
    return {
    totalEmployees: 0,
    totalElevators: 0,
    totalPresence: 0,
    latest_regulations: [],
    
    };

  },

  methods: {
    async getCountEmployees() {
      axios.get('/api/count_employees')
  .then(response => {
    this.totalEmployees = response.data;
  
  })
  .catch(error => {
    console.error(error);
  });
    },
    async getCountElevators() {
      axios.get('/api/count_elevators')
  .then(response => {
    this.totalElevators = response.data;

  })
  .catch(error => {
    console.error(error);
  });
    },
    async getCountPresence() {
      axios.get('/api/count_today_presence')
  .then(response => {
    this.totalPresence = response.data;
  
  })
  .catch(error => {
    console.error(error);
  });
    },
    async getLatestRegulations() {
      axios.get('/api/get_latest_regulations')
  .then(response => {
    this.latest_regulations = response.data;
    console.log(this.latest_regulations); // Check the structure of the response data
  })
  .catch(error => {
    console.error(error);
  });
    }
  

    
    

  

  },
 
  mounted() {
    this.getCountEmployees();
    this.getCountElevators();
    this.getCountPresence();
    this.getLatestRegulations();
  }
};
</script>


