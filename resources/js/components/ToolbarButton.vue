<template>
  <button
    type="button"
    class="inline-flex items-center justify-center w-8 h-8 focus:outline-none focus:ring rounded-lg"
  >
    <slot />
    <Icon v-if="type" solid :type="type" />
  </button>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      required: false,
    },
  },
}
</script>
