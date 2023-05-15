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

const checked = (isChecked, admin) => {
  if (isChecked) {
    checkedRows.value.push(admin);
  } else {
    checkedRows.value = remove(
      checkedRows.value,
      (row) => row.id === admin.id_admin
    );
  }
};
</script>

<template>
<CardBoxModal class="flex justify-center items-center h-screen" v-model="isModalActive" title="View Detail Admin">
  <div class="w-32 h-32  ml-28 rounded-full overflow-hidden">
    <img v-if="Selectedadmin.avatar" :src="'/storage/AdminAvatar/' + Selectedadmin.avatar" alt="admin" class="w-full h-full object-cover">
    <img v-else src="user.png" alt="default" class="w-full h-full object-cover">
  </div>
  <div class="mt-4 ml-4">
    <p class="font-bold"><b>Name :</b> {{ Selectedadmin.first_name }} {{ Selectedadmin.last_name }}</p>
    
    <p><b>Phone Number:</b> {{ Selectedadmin.phone_number }}</p>
    <p><b>Email :</b> {{ Selectedadmin.email }}</p>
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
        <th />
        <th>Name</th>
        <th>Email</th>
        <th>Phone Number</th>
       
        <th />
      </tr>
    </thead>
    <tbody>
      <tr v-for="admin in paginatedAdmins" :key="admin.id_admin">
        <TableCheckboxCell
          v-if="checkable"
          @checked="checked($event, admin)"
        />
        <td class="border-b-0 lg:w-6 before:hidden">
          <div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden;">
            <img v-if="admin.avatar" :src="'/storage/AdminAvatar/' + admin.avatar" alt="admin" class="w-full h-full object-cover">
            <img v-else src="user.png" alt="default" class="w-full h-full object-cover">
                     </div>
</td>
        <td data-label="Name">
          {{ admin.first_name }} {{ admin.last_name }}
        </td>
        <td data-label="Email">
          {{ admin.email }}
        </td>
        <td data-label="Phone Number">
          {{ admin.phone_number }}
        </td>
       
       
       
        
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons no-wrap>
            <BaseButton
              color="info"
              :icon="mdiEye"
              small
              @click="isModalActive = true ,getAdmin(admin)"
            />
           <BaseButton
            color="success"
            :icon="mdiHumanEdit" 
            small
            :to="'/update-admin/' + admin.id_admin"
           
          />
            <BaseButton
              color="danger"
              :icon="mdiTrashCan"
              small
              v-if="admin.id_admin !== loggedInAdminId" @click="confirmDelete(admin.id_admin)"
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
import Swal from 'sweetalert2'

export default {
  name: "AdminView",
  data() {
    return {
      admins: [],
      Selectedadmin: {},
      currentPage: 0,
      pageSize: 10,
      ADMIN_API_BASE_URL: "api/admins",
      loggedInAdminId: 1 // set to the ID of the currently logged-in admin
    };

  },
  methods: {
    async getAdmins() {
      await axios.get(this.ADMIN_API_BASE_URL)
        .then(response => this.admins = response.data)
        .catch(error => console.log(error))
    },
    async getAdmin(admin) {
  try {
    const response = await axios.get(`api/get_admin/${admin.id_admin}`);
    this.Selectedadmin = response.data;
    console.log(this.Selectedadmin.id_admin);
   
  } catch (error) {
    console.log(error);
  }
},
    confirmDelete(adminId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this admin record!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // User clicked the "Yes" button, so proceed with delete request
            axios.delete('api/delete_admin/' + adminId)
                .then(response => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Admin has been deleted successfully.',
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
}


  },
  computed: {
    paginatedAdmins: function () {
      const startIndex = this.currentPage * this.pageSize;
      const endIndex = startIndex + this.pageSize;
      return this.admins.slice(startIndex, endIndex);
    },
    numPages: function () {
      return Math.ceil(this.admins.length / this.pageSize);
    },
    currentPageHuman: function () {
      return this.currentPage + 1;
    },
    pagesList: function () {
      return Array.from({length: this.numPages}, (v, k) => k);
    },
    
  },
  mounted() {
    this.getAdmins();
  }
};
</script>