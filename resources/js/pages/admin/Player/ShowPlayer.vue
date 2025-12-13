<template>
  <DashboardHeader :title="player?.name || 'Player Details'" />

  <section class="content-header">
    <div class="container-fluid">
      <div v-if="player" class="card shadow-sm">
        <!-- Player image preview -->
        <img v-if="player.image" height="300" :src="getImageUrl(player.image)" class="card-img-top object-fit-cover"
          loading="lazy" :alt="player.name" />

        <div class="card-body">
          <h5 class="card-title">{{ player.name }}</h5>
          <p class="card-text">
            <strong>Sport:</strong> {{ player.sport }} <br />
            <strong>Position:</strong> {{ player.position }} <br />
            <strong>Team:</strong> {{ player.team }} <br />
            <strong>Age:</strong> {{ player.age }} <br />
            <strong>Status:</strong>
            <span :class="player.status == 1 ? 'text-success' : 'text-danger'">
              {{ player.status == 1 ? 'Active' : 'Inactive' }}
            </span>
          </p>
        </div>
      </div>
      <div v-else class="alert alert-warning">Loading player...</div>
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
const player = ref(null);

// helper to check if file is an image

onMounted(async () => {
  try {
    const response = await axios.get(`/api/players/${route.params.slug}`);
    player.value = response.data.data;
  } catch (error) {
    console.error('Error fetching player:', error);
  }
});

defineProps({
  slug: {
    type: [String, Number],
  }
});
</script>
