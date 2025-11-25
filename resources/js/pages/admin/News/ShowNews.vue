<template>
  <DashboardHeader :title="news?.news_title || 'News Details'" />

  <section class="content-header">
    <div class="container-fluid">
      <div v-if="news" class="card shadow-sm">
        <!-- News image preview -->
        <img v-if="news.image" height="300" :src="getImageUrl(news.image)" class="card-img-top object-fit-cover"
          loading="lazy" :alt="news.alt_name || news.news_title" />
        <div class="card-body">
          <h5 class="card-title">{{ news.news_title }}</h5>
          <p class="card-text" v-html="news.description"></p>

          <!-- Categories -->
          <div v-if="news.categories && news.categories.length" class="mt-3">
            <h6 class="font-weight-bold">Categories</h6>
            <a href="#" v-for="cat in news.categories" :key="cat.id">
              <span class="badge border ml-2">{{ cat.parent?.category_name ? cat.parent.category_name + ' > ' : ''
              }}{{ cat.category_name }}
              </span>
            </a>
          </div>
        </div>
      </div>

      <div v-else class="alert alert-warning">Loading news...</div>
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
const news = ref(null);

onMounted(async () => {
  try {
    const response = await axios.get(`/api/news/${route.params.slug}`);
    news.value = response.data.data;
  } catch (error) {
    console.error('Error fetching news:', error);
  }
});

defineProps({
  slug: {
    type: [String, Number],
  }
});
</script>
