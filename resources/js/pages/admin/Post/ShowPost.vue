<template>
  <DashboardHeader :title="post?.blog_title || 'Post Details'" />

  <section class="content-header">
    <div class="container-fluid">
      <div v-if="post" class="card shadow-sm">
        <img
          v-if="post.image"
          height="300"
          :src="getImageUrl(post.image)"
          class="card-img-top object-fit-cover"
          loading="lazy"
          :alt="post.alt_name || post.blog_title"
        />
        <div class="card-body">
          <h5 class="card-title">{{ post.blog_title }}</h5>
          <p class="card-text" v-html="post.description"></p>
        </div>
      </div>
      <div v-else class="alert alert-warning">Loading post...</div>
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
const post = ref(null);

onMounted(async () => {
  try {
    const response = await axios.get(`/api/posts/${route.params.slug}`);
    post.value = response.data.data;
  } catch (error) {
    console.error('Error loading post:', error);
  }
});
</script>
