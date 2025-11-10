<template>
    <div v-if="pData?.last_page > 1" class="d-flex justify-content-between align-items-center mt-3">
      <!-- Showing info -->
      <div>
        <small>
          Showing {{ pData.from }} to {{ pData.to }} of {{ pData.total }} results
        </small>
      </div>
  
      <!-- Pagination links -->
      <nav aria-label="Page navigation" v-if="pData.links && pData.links.length > 0">
        <ul class="pagination mb-0">
          <li
            v-for="link in pData.links"
            :key="link.label"
            class="page-item"
            :class="{ active: link.active, disabled: !link.url }"
          >
            <button
              class="page-link"
              v-html="link.label"
              :disabled="!link.url"
              @click="changePage(link.page)"
            ></button>
          </li>
        </ul>
      </nav>
    </div>
  </template>
  
  <script setup>
  import { defineProps, defineEmits } from 'vue';
  
  const props = defineProps({
    pData: {
      type: Object,
      required: true, // expects full Laravel pagination object
    },
  });
  
  const emit = defineEmits(['page-change']);
  
  const changePage = (page) => {
    if (page) {
      emit('page-change', page);
    }
  };
  </script>
  