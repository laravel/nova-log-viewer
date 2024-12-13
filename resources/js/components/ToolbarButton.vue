<template>
  <button
    type="button"
    class="inline-flex items-center justify-center w-8 h-8 focus:outline-none focus:ring rounded-lg"
  >
    <slot />
    <Icon v-if="iconName" type="micro" :name="iconName" />
  </button>
</template>

<script setup>
import { Icon } from 'laravel-nova-ui'

defineProps({
  iconName: { type: String, required: false },
})
</script>
