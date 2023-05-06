<script setup>
import { mdiPlus, mdiLock,mdiElevatorPassengerOutline,mdiListBoxOutline,mdiPrinterPosWrenchOutline
}
  from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";

import TableTechnicalIssues from "@/components/TableTechnicalIssues.vue";


import CardBox from "@/components/CardBox.vue";
import LayoutAuthenticated from "@/auth/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import BaseButton from "@/components/BaseButton.vue";
import axios from 'axios';

const exportData = () => {
  axios.get('api/export_issues', { responseType: 'blob' })
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
  link.setAttribute('download', 'issues.xlsx');
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};
const exportToPDF = () => {
  axios.get('api/export_issues_pdf', { responseType: 'blob' })
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
      <SectionTitleLineWithButton :icon="mdiPrinterPosWrenchOutline" title="Technical Issues Regulations" main>
        <div class="dropdown dropdown-bottom ml-2">
  <label tabindex="0" class="btn m-1 text-white ml-auto">Export</label>
  <ul tabindex="0" class="dropdown-content menu p-1 shadow bg-white rounded-box w-40">
    <li class="flex items-center px-0">
      <a @click="exportData" class="text-blue-500 px-0">To Excel</a>
    </li>
    <li class="flex items-center px-0">
      <a class="text-blue-500 px-0" @click="exportToPDF">To PDF</a>
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
        <TableTechnicalIssues/>
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
