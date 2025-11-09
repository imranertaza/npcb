<template>
  <DashboardHeader title="Manage Pages" />

  <section class="">
    <div class="row">
      <div class="col-md-12">
        <div v-if="pages.length === 0" class="alert alert-info">No pages found.</div>

        <div v-else>
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
              <tr class="align-middle">
                <th style="width: 10px">#</th>
                <th>Title</th>
                <th>Description</th>
                <th>Image</th>
                <th v-if="authStore.hasPermission(publish-pages)">Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(page, index) in pages" :key="page.id">
                <td class="align-middle">{{ index + 1 }}</td>
                <td class="align-middle">{{ truncateText(page.page_title, 20) }}</td>
                <td class="align-middle">{{ truncateText(page.short_des, 50) }}</td>
                <td class="align-middle">
                  <img v-if="page.f_image" :src="getImageUrl(page.f_image)" alt="Page Image" height="50"
                    class="rounded" />
                </td>
                <td v-if="authStore.hasPermission(publish-pages)" class="align-middle">
                  <select v-model="page.status" @change="updateStatus(page)" class="form-control"
                    :class="page.status === 'Active' ? 'bg-success text-white' : 'bg-transparent text-dark'">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                  </select>
                </td>
                <td class="align-middle">
                  <div class="d-flex">
                    <router-link v-if="authStore.hasPermission('view-pages')" :to="{ name: 'ShowPage', params: { slug: page.slug } }" class="btn btn-sm btn-dark">
                      <i class="fas fa-eye"></i>
                    </router-link>
                    <router-link v-if="authStore.hasPermission('edit-pages')" :to="{ name: 'UpdatePages', params: { slug: page.slug } }"
                      class="ml-2 btn btn-sm btn-dark">
                      <i class="fas fa-pencil-alt"></i>
                    </router-link>

                    <button v-if="authStore.hasPermission('delete-pages')" class="ml-2 btn btn-sm btn-danger" @click="confirmDelete(page)">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, inject } from 'vue';
import axios from 'axios';
import DashboardHeader from '../../../components/DashboardHeader.vue';
import { getImageUrl, truncateText } from '../../../layouts/helpers/helpers';
import { useRoute } from 'vue-router';
import { useToast } from '../../../composables/useToast';
import { useAuthStore } from '../../../store/auth';

const toast = useToast()
const authStore =useAuthStore()
const route = useRoute();
const pages = ref([]);
const $swal = inject('$swal');

onMounted(async () => {

  try {
    const response = await axios.get('/api/pages');
    pages.value = response.data.data.data;
  } catch (error) {
    console.error('Error fetching pages:', error);
  }

  if (route.query.toast) {
    toast.success(route.query.toast);
  }
});

const updateStatus = async (page) => {
  try {
    const response = await axios.patch(`/api/pages/${page.slug}/status`, {
      status: page.status
    });

    if (response.data.status === 'Active') {
      toast.success('Page activated');
    } else {
      toast.info('Page deactivated');
    }
  } catch (error) {
    toast.error('Failed to update status');
    console.error(error);
  }
};

const confirmDelete = async (page) => {
  const result = await $swal({
    title: `Delete "${page.page_title}"?`,
    text: 'This action cannot be undone.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete',
    cancelButtonText: 'Cancel',
    reverseButtons: true
  });

  if (result.isConfirmed) {
    try {
      const response = await axios.delete(`/api/pages/${page.slug}`);
      if (response.data.success) {
        toast.success('Page deleted successfully!');
        pages.value = pages.value.filter(p => p.id !== page.id);
      } else {
        toast.error('Failed to delete page.');
      }
    } catch (error) {
      toast.error('Something went wrong.');
      console.error(error);
    }
  } else {
    toast.info('Deletion cancelled.');
  }
};
</script>
