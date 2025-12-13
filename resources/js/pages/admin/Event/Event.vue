<template>
  <DashboardHeader title="Manage Event">
    <div class="d-flex justify-content-end">
      <SearchBox @search="onSearch" />
    </div>
  </DashboardHeader>

  <section>
    <div class="row">
      <div class="col-md-12">
        <div v-if="events?.data?.length === 0" class="alert alert-info">No events found.</div>

        <div v-else>
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead class="thead-light">
                <tr class="align-middle">
                  <th style="width: 10px">#</th>
                  <th>Title</th>
                  <th>File</th>
                  <th v-if="authStore.hasPermission('edit-events')">Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(event, index) in events?.data" :key="event.id">
                  <td class="align-middle">{{ index + 1 }}</td>
                  <td class="align-middle">{{ truncateText(event.title, 20) }}</td>
                  <td class="align-middle">
                    <button v-if="event.featured_image" @click="openImageModal(event.featured_image)"
                      class="btn btn-sm btn-outline-primary">
                      View Image
                    </button>
                  </td>
                  <td v-if="authStore.hasPermission('edit-events')" class="align-middle">
                    <select v-model="event.status" @change="updateStatus(event)" class="custom-select"
                      :class="event.status == 1 ? 'bg-success text-white' : 'bg-transparent text-dark'">
                      <option :value="1">Active</option>
                      <option :value="0">Inactive</option>
                    </select>
                  </td>
                  <td class="align-middle">
                    <div class="d-flex">
                      <router-link v-if="authStore.hasPermission('view-events')"
                        :to="{ name: 'ShowEvent', params: { slug: event.slug } }" class="btn btn-sm btn-outline-dark">
                        <i class="fas fa-eye"></i>
                      </router-link>
                      <router-link v-if="authStore.hasPermission('edit-events')"
                        :to="{ name: 'UpdateEvent', params: { slug: event.slug } }"
                        class="ml-2 btn btn-sm btn-outline-info">
                        <i class="fas fa-pencil-alt"></i>
                      </router-link>
                      <button v-if="authStore.hasPermission('delete-events')" class="ml-2 btn btn-sm btn-outline-danger"
                        @click="confirmDelete(event)">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <!-- Image Modal -->
            <div v-if="showModal" class="modal fade show d-block" tabindex="-1" role="dialog">
              <div class="modal-dialog modal-lg" role="document" @click.stop>
                <div class="modal-content ">
                  <div class="modal-header">
                    <h5 class="modal-title">Event Image</h5>
                    <button type="button" class="close" @click="closeImageModal">
                      <span>&times;</span>
                    </button>
                  </div>
                  <div class="modal-body text-center overflow-scroll " style="height: 80vh; overflow: auto;">
                    <img :src="modalImage" alt="Event Image" class="img-fluid rounded border" />
                  </div>
                </div>
              </div>
            </div>
            <div v-if="showModal" class="modal-backdrop fade show" @click="closeImageModal"></div>

          </div>
          <Pagination :pData="events" @page-change="fetchPage" />
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
const events = ref([]);
const authStore = useAuthStore();
const toast = useToast();
const $swal = inject('$swal');


const showModal = ref(false);
const modalImage = ref('');

const openImageModal = (imagePath) => {
  modalImage.value = getImageUrl(imagePath);
  showModal.value = true;
};

const closeImageModal = () => {
  showModal.value = false;
  modalImage.value = '';
};

// Fetch events with pagination + search
const fetchPage = async (page = 1, term = "") => {
  try {
    const res = await axios.get(`/api/events?page=${page}&search=${term || ''}`);
    events.value = res.data.data;
  } catch (error) {
    console.error(error);
    toast.error('Failed to load events.');
  }
};

const onSearch = async (term) => {
  fetchPage(1, term);
};

onMounted(() => {
  fetchPage();
  if (route.query.toast) {
    toast.success(route.query.toast);
    setTimeout(() => {
      const q = { ...route.query };
      delete q.toast;
      router.replace({ query: q });
    }, 2000);
  }
});

// Update event status
const updateStatus = async (event) => {
  try {
    const response = await axios.patch(`/api/events/${event.slug}/toggle-status`);
    event.status = response.data.status;
    toast.success(response.data.message);
  } catch (error) {
    toast.error('Failed to update status');
    console.error(error);
  }
};

// Delete event
const confirmDelete = async (event) => {
  const result = await $swal({
    title: `Delete "${event.title}"?`,
    text: 'This action cannot be undone.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete',
    cancelButtonText: 'Cancel',
    reverseButtons: true
  });

  if (result.isConfirmed) {
    try {
      await axios.delete(`/api/events/${event.slug}`);
      toast.success('Event deleted successfully!');
      events.value.data = events.value.data.filter(e => e.id !== event.id);
    } catch (error) {
      toast.validationError(error);
    }
  } else {
    toast.info('Deletion cancelled.');
  }
};
</script>
