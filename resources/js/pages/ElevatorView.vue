<script setup>
import { mdiPlus, mdiLock,mdiElevatorPassengerOutline
}
  from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";

import TableElevator from "@/components/TableElevator.vue";


import CardBox from "@/components/CardBox.vue";
import LayoutAuthenticated from "@/auth/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import BaseButton from "@/components/BaseButton.vue";

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiLock" title="Elevators" main>
        <router-link to="/add-elevator">
          <BaseButton
            target="_blank"
            :icon="mdiElevatorPassengerOutline"
            label="Add new elevator"
            color="contrast"
            rounded-full
            small
          />
        </router-link>
      </SectionTitleLineWithButton>

      <CardBox has-table>
        <TableElevator />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
