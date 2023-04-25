<script setup>
import { mdiPlus, mdiLock,
}
  from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";

import TableEmployee from "@/components/TableEmployee.vue";

import CardBox from "@/components/CardBox.vue";
import LayoutAuthenticated from "@/auth/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import BaseButton from "@/components/BaseButton.vue";

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiLock" title="Employees" main>
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
