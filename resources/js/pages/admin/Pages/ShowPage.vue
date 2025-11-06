<template>
  <DashboardHeader :title="page?.page_title || 'Page Details'" />

  <section class="content-header">
    <div class="container-fluid">
      <div v-if="page" class="card shadow-sm">
        <img
          v-if="page.f_image"
          height="300"
          :src="getImageUrl(page.f_image)"
          class="card-img-top object-fit-cover"
          loading="lazy"
          :alt="page.page_title"
        />
        <div class="card-body">
          <h5 class="card-title">{{ page.page_title }}</h5>
          <p class="card-text" v-html="page.page_description"></p>
        </div>
      </div>
      <div v-else class="alert alert-warning">Loading page...</div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import DashboardHeader from '@/components/DashboardHeader.vue';
import { getImageUrl } from '../../../layouts/helpers/helpers';

const route = useRoute();
const page = ref(null);

onMounted(async () => {
  try {
    const response = await axios.get(`/api/pages/${route.params.slug}`);
    page.value = response.data.data;
  } catch (error) {
    console.error('Error loading page:', error);
  }
});
</script>
