<template>
  <DashboardHeader title="Update Gallery" />

  <section class="content">
    <div class="container-fluid">
      <div class="row row-cols-1">
        <div class="card card-purple">
          <div class="card-header">
            <h3 class="card-title">Edit Gallery</h3>
          </div>

          <form @submit.prevent="updateGallery">
            <div class="card-body">
              <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                  <!-- Gallery Name -->
                  <div class="form-group">
                    <label>Gallery Name</label>
                    <input v-model="form.name" type="text" class="form-control" required />
                  </div>

                  <!-- Alt Name -->
                  <div class="form-group">
                    <label>Alt Name</label>
                    <input v-model="form.alt_name" type="text" class="form-control" />
                  </div>

                  <!-- Sort Order -->
                  <div class="form-group">
                    <label>Sort Order</label>
                    <input v-model="form.sort_order" type="number" class="form-control" />
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <button type="submit" class="btn btn-success btn-block">Update</button>
                    </div>
                    <div class="col-md-6">
                      <button type="button" class="btn btn-secondary btn-block"
                        @click="router.push({ name: 'Gallery' })">Cancel</button>
                    </div>
                  </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                  <!-- Thumb Upload -->
                  <div class="form-group">
                    <label>Upload Thumbnail</label>
                    <div v-if="form.thumb" class="mb-2">
                      <img :src="getImageUrl(form.thumb)" alt="Gallery Thumb" height="80" class="rounded" />
                    </div>
                    <Vue3Dropzone v-model="imageFile" v-model:previews="previews" mode="edit"
                      :allowSelectOnPreview="true" />
                  </div>
                </div>
              </div>
            </div>
          </form>

          <!-- Gallery Details Section -->
          <div class="card-body border-top">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5>Gallery Images</h5>
              <button class="btn btn-sm btn-primary" @click="showAddModal = true">
                <i class="fas fa-plus"></i> Add Image
              </button>
            </div>

            <div class="row" v-if="form.details?.length > 0">
              <div v-for="detail in form.details" :key="detail.id" class="col-md-3 mb-3">
                <div class="card h-100 shadow-sm">
                  <img v-if="detail.image" :src="getImageUrl(detail.image)" :alt="detail.alt_name || form.name"
                    class="card-img-top object-fit-cover" height="200" />
                  <div class="card-body pt-0">
                    <div class="d-flex justify-content-between mt-2">
                      <small class="d-block text-muted">{{ detail.alt_name || 'No alt text' }}</small>
                      <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-info" @click="openEditModal(detail)">
                          <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" @click="deleteDetail(detail)">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="alert alert-info">No images in this gallery. Add some using the button above.</div>
          </div>

          <!-- Add Image Modal -->
          <div v-if="showAddModal" class="modal fade show d-block" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Add New Gallery Image</h5>
                  <button type="button" class="close" @click="showAddModal = false">
                    <span>&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <label>Alt Name</label>
                    <input v-model="newDetail.alt_name" type="text" class="form-control" />
                  </div>
                  <div class="form-group">
                    <label>Upload Image</label>
                    <Vue3Dropzone v-model="newDetailFile" v-model:previews="newDetailPreviews" />
                  </div>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-secondary" @click="showAddModal = false">Cancel</button>
                  <button class="btn btn-success" @click="addDetail">Add</button>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
  <!-- Edit Detail Modal -->
  <div v-if="showEditModal" class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Image Detail</h5>
          <button type="button" class="close" @click="closeEditModal">
            <span>&times;</span>
          </button>
        </div>
        <form @submit.prevent="saveDetail">
          <div class="modal-body">
            <div class="form-group">
              <label>Alt Text</label>
              <input v-model="editDetail.alt_name" type="text" class="form-control" />
            </div>
            <div class="form-group">
              <label>Sort Order</label>
              <input v-model="editDetail.sort_order" type="number" class="form-control" />
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" @click="closeEditModal">Cancel</button>
            <button class="btn btn-success" type="submit">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</template>

<script setup>
import { ref, reactive, onMounted, inject } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import DashboardHeader from '@/components/DashboardHeader.vue';
import { getImageUrl } from '@/layouts/helpers/helpers';
import Vue3Dropzone from '@jaxtheprime/vue3-dropzone';
import '@jaxtheprime/vue3-dropzone/dist/style.css';
import { useToast } from '@/composables/useToast';

const toast = useToast();
const previews = ref();
const route = useRoute();
const router = useRouter();
const $swal = inject('$swal');
const galleryId = route.params.id;
const showAddModal = ref(false);
const newDetail = reactive({ alt_name: '', sort_order: 0 });
const newDetailFile = ref(null);
const newDetailPreviews = ref([]);
const showEditModal = ref(false);
const editDetail = reactive({ id: null, alt_name: '', sort_order: 0 });


const form = reactive({
  id: null,
  name: '',
  alt_name: '',
  sort_order: 0,
  thumb: '',
  details: [], // gallery details
  createdBy: 1,
  updatedBy: null
});

const imageFile = ref(null);

// Fetch gallery with details
const fetchGallery = async () => {
  try {
    const res = await axios.get(`/api/gallery/${galleryId}`);
    Object.assign(form, res.data.data);
    if (form.thumb) {
      previews.value = [getImageUrl(form.thumb)];
    }
  } catch (err) {
    console.error(err);
    toast.error('Failed to load gallery.');
  }
};

// Update gallery
const updateGallery = async () => {
  const payload = new FormData();

  for (const key in form) {
    if (key !== 'thumb' && key !== 'details') {
      payload.append(key, form[key]);
    }
  }

  if (imageFile.value && imageFile.value[0]) {
    payload.append('thumb', imageFile.value[0].file);
  }

  try {
    await axios.post(`/api/gallery/${form.id}?_method=PUT`, payload, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    router.push({ name: 'Gallery', query: { toast: 'Gallery updated successfully!' } });
  } catch (err) {
    toast.validationError(err);
  }
};

const addDetail = async () => {
  const payload = new FormData();
  payload.append('gallery_id', form.id);
  payload.append('alt_name', newDetail.alt_name);
  payload.append('sort_order', newDetail.sort_order);

  if (newDetailFile.value && newDetailFile.value[0]) {
    payload.append('image', newDetailFile.value[0].file);
  }

  try {
    const res = await axios.post('/api/gallery/details', payload, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    form.details.push(res.data.data); // append new detail to list
    toast.success('Image added successfully!');
    showAddModal.value = false;
    newDetail.alt_name = '';
    newDetailFile.value = null;
    newDetailPreviews.value = [];
  } catch (err) {
    toast.validationError(err);
  }
};
// Delete detail
const deleteDetail = async (detail) => {
  const result = await $swal({
    title: `Delete this image?`,
    text: 'This action cannot be undone.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete',
    cancelButtonText: 'Cancel',
    reverseButtons: true
  });

  if (result.isConfirmed) {
    try {
      await axios.delete(`/api/gallery/details/${detail.id}`);
      toast.success('Image deleted successfully!');
      form.details = form.details.filter(d => d.id !== detail.id);
    } catch (error) {
      toast.validationError(error);
    }
  }
};

const openEditModal = (detail) => {
  editDetail.id = detail.id;
  editDetail.alt_name = detail.alt_name || '';
  editDetail.sort_order = detail.sort_order || 0;
  showEditModal.value = true;
};

const closeEditModal = () => {
  showEditModal.value = false;
  editDetail.id = null;
  editDetail.alt_name = '';
  editDetail.sort_order = 0;
};

const saveDetail = async () => {
  try {
    await axios.patch(`/api/gallery/details/${editDetail.id}`, {
      alt_name: editDetail.alt_name,
      sort_order: editDetail.sort_order
    });

    // Update local state
    const idx = form.details.findIndex(d => d.id === editDetail.id);
    if (idx !== -1) {
      form.details[idx].alt_name = editDetail.alt_name;
      form.details[idx].sort_order = editDetail.sort_order;
    }

    toast.success('Image detail updated successfully!');
    closeEditModal();
  } catch (error) {
    toast.validationError(error);
  }
};

onMounted(() => {
  fetchGallery();
});
</script>
