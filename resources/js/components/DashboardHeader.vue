<template>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="mb-0">{{ title }}</h3>
      <div class="d-flex gap-3">
        <slot />
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({
    title: {
      type: String,
      required: true
    }
  });
  </script>
  