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

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiPrinterPosWrenchOutline" title="Technical Issues Regulations" main>
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
