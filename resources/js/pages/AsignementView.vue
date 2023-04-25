<script setup>
import { mdiPlus, mdiLock,
}
  from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";

import TableAsignement from "@/components/TableAsignement.vue";

import CardBox from "@/components/CardBox.vue";
import LayoutAuthenticated from "@/auth/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import BaseButton from "@/components/BaseButton.vue";

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiLock" title="Elevator Assignments" main>
        <router-link to="/add-assignments">
          <BaseButton
            target="_blank"
            :icon="mdiPlus"
            label="Add new assignments"
            color="contrast"
            rounded-full
            small
          />
        </router-link>
      </SectionTitleLineWithButton>

      <CardBox has-table>
        <TableAsignement />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
