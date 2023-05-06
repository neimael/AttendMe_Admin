<script setup>
import { mdiPlus, mdiLock, mdiElevatorDown, mdiElevatorPassenger, mdiListBox,
}
  from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";

import DetailElevatorView from "@/components/DetailElevatorView.vue";

import CardBox from "@/components/CardBox.vue";
import LayoutAuthenticated from "@/auth/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import BaseButton from "@/components/BaseButton.vue";


</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiElevatorPassenger" title="Detail Elevator" main>
        <router-link to="/elevators">
          <BaseButton
            target="_blank"
            :icon="mdiListBox"
            label="show Elevators"
            color="contrast"
            rounded-full
            small
          />
        </router-link>
      </SectionTitleLineWithButton>

      <CardBox has-table>
        <DetailElevatorView />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
