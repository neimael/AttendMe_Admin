<script setup>
import { mdiPlus, mdiLock, mdiExportVariant  } from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";
import TableEmployee from "@/components/TableEmployee.vue";
import CardBox from "@/components/CardBox.vue";
import LayoutAuthenticated from "@/auth/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import BaseButton from "@/components/BaseButton.vue";
import axios from 'axios';

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
      <SectionTitleLineWithButton :icon="mdiLock" title="Employees" main>
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
            label="Add new employee"
            color="contrast"
            rounded-full
            small
          />
        </router-link>
      </SectionTitleLineWithButton>
     
      <CardBox has-table>
        <TableEmployee />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
