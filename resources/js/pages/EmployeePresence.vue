<script setup>
import { mdiPlus, mdiLock, mdiElevatorDown, mdiElevatorPassenger, mdiListBox,mdiBadgeAccount
}
from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";
import DetailElevatorView from "@/components/DetailElevatorView.vue";
import CardBox from "@/components/CardBox.vue";
import LayoutAuthenticated from "@/auth/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import BaseButton from "@/components/BaseButton.vue";
import EmployeePresenceTable from "@/components/EmployeePresenceTable.vue"
</script>
<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiBadgeAccount" title="Detail Presence" main>
        <div class="space-x-3">
        <router-link to="/add-manual-presence">
          <BaseButton
            target="_blank"
            :icon="mdiListBox"
            label="Add Manual Presence"
            color="contrast"
            rounded-full
            small
           /> 
          </router-link>
           <router-link to="/employees">
          <BaseButton
        
            target="_blank"
            :icon="mdiListBox"
            label="show Employees"
            color="contrast"
            rounded-full
            small
           />
        </router-link>
      </div>
      </SectionTitleLineWithButton>
      <CardBox has-table>
     <EmployeePresenceTable />
     </CardBox>
     </SectionMain>
  </LayoutAuthenticated>
</template>
