<script setup>
import { mdiPlus, mdiLock,mdiElevatorPassengerOutline,mdiListBoxOutline
}
  from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";

import TableAttendance from "@/components/TableAttendance.vue";


import CardBox from "@/components/CardBox.vue";
import LayoutAuthenticated from "@/auth/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import BaseButton from "@/components/BaseButton.vue";

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiListBoxOutline" title="Attendance" main>
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
        <TableAttendance />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
