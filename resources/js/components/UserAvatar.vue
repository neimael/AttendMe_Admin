<script setup>
import { computed } from "vue";

const props = defineProps({
  username: {
    type: String,
    required: true,
  },
  avatar: {
    type: String,
    default: null,
  },
  api: {
    type: String,
    default: "avataaars",
  },
});

const avatar = computed(() => props.avatar);



const username = computed(() => props.username);
</script>

<template>
      <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden;" >
    <img v-bind:src=" avatar ? avatar : '/user.png' "  class="w-full h-full object-cover" />


    <slot />
  </div>
</template>
