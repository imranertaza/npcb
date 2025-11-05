<!-- PaginationClassic.vue -->
<template>
    <nav v-if="pagination.last_page > 1">
      <ul class="pagination">
        <li class="page-item" :class="{ disabled: !pagination.prev_page_url }">
          <button class="page-link" @click="fetchPage(currentPage - 1)">Previous</button>
        </li>
        <li
          v-for="page in pagination.last_page"
          :key="page"
          class="page-item"
          :class="{ active: page === currentPage }"
        >
          <button class="page-link" @click="fetchPage(page)">{{ page }}</button>
        </li>
        <li class="page-item" :class="{ disabled: !pagination.next_page_url }">
          <button class="page-link" @click="fetchPage(currentPage + 1)">Next</button>
        </li>
      </ul>
    </nav>
  </template>
  
  <script setup>
  defineProps(['pagination', 'currentPage', 'fetchPage']);
  </script>
  