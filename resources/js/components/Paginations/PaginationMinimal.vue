<!-- PaginationMinimal.vue -->
<template>
    <div class="d-flex justify-content-between align-items-center mt-3">
      <button
        class="btn btn-outline-secondary btn-sm"
        :disabled="!pagination.prev_page_url"
        @click="fetchPage(currentPage - 1)"
      >
        ← Previous
      </button>
      <span>Page {{ currentPage }} of {{ pagination.last_page }}</span>
      <button
        class="btn btn-outline-secondary btn-sm"
        :disabled="!pagination.next_page_url"
        @click="fetchPage(currentPage + 1)"
      >
        Next →
      </button>
    </div>
  </template>
  
  <script setup>
  defineProps(['pagination', 'currentPage', 'fetchPage']);
  </script>
  