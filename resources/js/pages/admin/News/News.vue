<template>
  <DashboardHeader title="Manage News">
    <div class="d-flex justify-content-end">
      <SearchBox @search="onSearch" />
    </div>
  </DashboardHeader>

  <section class="">
    <div class="row">
      <div class="col-md-12">
        <div v-if="news?.data?.length === 0" class="alert alert-info">No news found.</div>

        <div v-else>
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead class="thead-light">
                <tr class="align-middle">
                  <th style="width: 10px">#</th>
                  <th>Title</th>
                  <th>Description</th>
                  <th>Image</th>
                  <th v-if="authStore.hasPermission('publish-news')">Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in news?.data" :key="item.id">
                  <td class="align-middle">{{ index + 1 }}</td>
                  <td class="align-middle">{{ truncateText(item.news_title, 20) }}</td>
                  <td class="align-middle">{{ truncateText(item.short_des, 50) }}</td>
                  <td class="align-middle">
                    <template v-if="item.image">
                      <template v-if="isVideo(item.image)">
                        <a class="btn btn-sm btn-outline-primary" :href="getImageUrl(item.image)" target="_blank"
                          rel="noopener noreferrer">View</a>
                      </template>
                      <template v-else>
                        <img draggable="false" :src="getImageUrl(item.image)" alt="News Image" height="50"
                          class="rounded" />
                      </template>
                    </template>
                  </td>

                  <td v-if="authStore.hasPermission('publish-news')" class="align-middle">
                    <select v-model="item.status" @change="updateStatus(item)" class="custom-select"
                      :class="item.status == 1 ? 'bg-success text-white' : 'bg-transparent text-dark'">
                      <option :value="1">Active</option>
                      <option :value="0">Inactive</option>
                    </select>
                  </td>
                  <td class="align-middle">
                    <div class="d-flex ">
                      <router-link v-if="authStore.hasPermission('view-news')"
                        :to="{ name: 'ShowNews', params: { slug: item.slug } }" class="btn btn-sm btn-outline-dark">
                        <i class="fas fa-eye"></i>
                      </router-link>
                      <router-link v-if="authStore.hasPermission('edit-news')"
                        :to="{ name: 'UpdateNews', params: { slug: item.slug } }"
                        class="ml-2 btn btn-sm btn-outline-info">
                        <i class="fas fa-pencil-alt"></i>
                      </router-link>
                      <button v-if="authStore.hasPermission('delete-news')" class="ml-2 btn btn-sm btn-outline-danger"
                        @click="confirmDelete(item)">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <Pagination :pData="news" @page-change="fetchPage" />
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import axios from 'axios';
import DashboardHeader from '@/components/DashboardHeader.vue';
import { inject, onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Pagination from '@/components/Paginations/Pagination.vue';
import { useToast } from '@/composables/useToast';
import { getImageUrl, truncateText } from '@/layouts/helpers/helpers';
import { useAuthStore } from '@/store/auth';
import SearchBox from '@/components/SearchBox.vue';

const router = useRouter();
const route = useRoute();
const news = ref([]);
const authStore = useAuthStore();
const toast = useToast();
const $swal = inject('$swal');


const isVideo = (file) => {
  if (!file) return false;

  // Handle cases where file might be a full URL or path
  const ext = file.split("?")[0].split(".").pop().toLowerCase();

  const videoExts = ["mp4", "avi", "mov", "wmv", "webm", "mkv"];
  return videoExts.includes(ext);
};
// Fetch news with pagination + search
const fetchPage = async (page = 1, term = "") => {
  try {
    const res = await axios.get(`/api/news?page=${page}&search=${term || ''}`);
    news.value = res.data.data;
  } catch (error) {
    console.error(error);
    toast.error('Failed to load news.');
  }
};

const onSearch = async (term) => {
  fetchPage(1, term);
};

onMounted(async () => {
  try {
    fetchPage();
  } catch (error) {
    console.error('Error fetching news:', error);
  }
  if (route.query.toast) {
    toast.success(route.query.toast);
    setTimeout(() => {
      const q = { ...route.query };
      delete q.toast;
      router.replace({ query: q });
    }, 2000);
  }
});

// Update news status
const updateStatus = async (item) => {
  try {
    const response = await axios.patch(`/api/news/${item.slug}/status`, {
      status: item.status
    });

    if (response.data.data.status == 1) {
      toast.success('News published');
    } else {
      toast.info('News set to draft');
    }
  } catch (error) {
    toast.error('Failed to update status');
    console.error(error);
  }
};

// Delete news
const confirmDelete = async (item) => {
  const result = await $swal({
    title: `Delete "${item.news_title}"?`,
    text: 'This action cannot be undone.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete',
    cancelButtonText: 'Cancel',
    reverseButtons: true
  });

  if (result.isConfirmed) {
    try {
      await axios.delete(`/api/news/${item.slug}`);
      toast.success('News deleted successfully!');
      news.value.data = news.value.data.filter(n => n.id !== item.id);
    } catch (error) {
      toast.validationError(error);
    }
  } else {
    toast.info('Deletion cancelled.');
  }
};
</script>
