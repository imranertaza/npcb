<template>
  <DashboardHeader :title="event?.title || 'Event Details'" />

  <section class="content-header">
    <div class="container-fluid">
      <div v-if="event" class="card shadow-sm">
        <!-- Event file preview -->
        <img
          v-if="event.file && isImage(event.file)"
          height="300"
          :src="getImageUrl(event.file)"
          class="card-img-top object-fit-cover"
          loading="lazy"
          :alt="event.title"
        />
        <div class="card-body">
          <h5 class="card-title">{{ event.title }}</h5>
          <p class="card-text" v-html="event.description"></p>

          <!-- If file is not an image, show download link -->
          <div v-if="event.file && !isImage(event.file)" class="mt-3">
            <a :href="getImageUrl(event.file)" target="_blank" class="btn btn-sm btn-outline-primary">
              Download File
            </a>
          </div>
        </div>
      </div>
      <div v-else class="alert alert-warning">Loading event...</div>
    </div>
  </section>
</template>

<script setup>
import DashboardHeader from '@/components/DashboardHeader.vue';
import axios from 'axios';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { getImageUrl } from '@/layouts/helpers/helpers';

const route = useRoute();
const event = ref(null);

// helper to check if file is an image
const isImage = (filePath) => {
  return /\.(jpg|jpeg|png|gif)$/i.test(filePath);
};

onMounted(async () => {
  try {
    const response = await axios.get(`/api/events/${route.params.slug}`);
    event.value = response.data.data;
  } catch (error) {
    console.error('Error fetching event:', error);
  }
});

defineProps({
  slug: {
    type: [String, Number],
  }
});
</script>
