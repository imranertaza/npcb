<template>
  <DashboardHeader title="Update Event" />

  <section class="content">
    <div class="container-fluid">
      <div class="row row-cols-1">
        <div class="card card-purple">
          <div class="card-header">
            <h3 class="card-title">Edit Event</h3>
          </div>

          <form @submit.prevent="updateEvent">
            <div class="card-body">
              <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                  <!-- Event Title -->
                  <div class="form-group">
                    <label>Event Title</label>
                    <input v-model="form.title" @input="form.slug = generateSlug(form.title)" type="text" class="form-control" required />
                  </div>

                  <!-- Slug -->
                  <div class="form-group">
                    <label>Slug</label>
                    <input v-model="form.slug" type="text" class="form-control" required />
                  </div>

                  <!-- Description -->
                  <div class="form-group">
                    <label>Description</label>
                    <RichTextEditor v-model="form.description" placeholder="Write event details here..."
                      class="editor" />
                  </div>

                  <!-- Category -->
                  <div class="form-group">
                    <label>Event Category</label>
                    <Multiselect v-model="form.event_category_id" :options="categoriesOptions"
                      :reduce="option => option.value" placeholder="Select Event category" searchable allow-empty />
                  </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                  <!-- File Upload -->
                  <div class="form-group">
                    <label>Upload File (Image/PDF)</label>
                    <Vue3Dropzone v-model="fileUpload" v-model:previews="previews" mode="edit"
                      :allowSelectOnPreview="true" />

                    <!-- Custom Preview -->
                    <div v-if="previewsPdf && previewsPdf.length" class="mt-3">
                      <div v-for="(preview, idx) in previewsPdf" :key="idx">
                        <!-- If PDF -->
                        <div v-if="isPdf(preview)" class="d-flex align-items-center">
                          <i class="fas fa-file-pdf fa-3x text-danger mr-2"></i>
                          <a :href="preview" target="_blank">View PDF Document</a>
                        </div>

                        <!-- If Image -->
                        <img v-else :src="preview" alt="Preview" class="img-fluid rounded border"
                          style="max-height:120px" />
                      </div>
                    </div>
                  </div>

                  <!-- Status -->
                  <div class="form-group">
                    <label>Status</label>
                    <select v-model="form.status" class="custom-select">
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                  </div>

                  <div>
                    <button type="submit" class="btn btn-success btn-block">Update</button>
                    <RouterLink :to="{ name: 'Events' }" class="btn btn-secondary btn-block mt-2">Cancel</RouterLink>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import axios from 'axios';
import { computed, onMounted, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import DashboardHeader from '@/components/DashboardHeader.vue';
import { useToast } from '@/composables/useToast';
import { getImageUrl } from '@/layouts/helpers/helpers';
import Vue3Dropzone from '@jaxtheprime/vue3-dropzone';
import '@jaxtheprime/vue3-dropzone/dist/style.css';
import RichTextEditor from '@/components/RichTextEditor.vue';
import Multiselect from '@vueform/multiselect';
import { generateSlug } from '../../../layouts/helpers/helpers';

const toast = useToast();
const route = useRoute();
const router = useRouter();
// const eventId = route.params.id;
const previews = ref([]);
const previewsPdf = ref([]);
const categories = ref([]);
const isPdf = (filename) => /\.pdf$/i.test(filename);

const form = reactive({
  id: null,
  title: '',
  slug: '',
  description: '',
  file: '',
  event_category_id: '',
  status: '1',
  createdBy: 1,
  updatedBy: null
});

const fileUpload = ref(null);


const categoriesOptions = computed(() => {
  return [
    { label: '-- None --', value: '' },
    ...categories.value.map(cat => ({
      label: cat.category_name,
      value: cat.id
    }))
  ];
});
// Fetch event
const fetchEvent = async () => {
  try {
    const res = await axios.get(`/api/events/${route.params.slug}`);
    Object.assign(form, res.data.data);
    if (form.file && !isPdf(form.file)) {
      previews.value = [getImageUrl(form.file)];
    } else if (form.file && isPdf(form.file)) {
      previewsPdf.value = [getImageUrl(form.file)];
    }
    // console.log({form})
  } catch (err) {
    toast.error('Failed to load event');
    console.error(err);
  }
};

// Fetch categories
const fetchCategories = async () => {
  try {
    const res = await axios.get('/api/events-categories?all=1');
    categories.value = res.data.data;
  } catch (err) {
    toast.error('Failed to load categories');
  }
};

// Update event
const updateEvent = async () => {
  const payload = new FormData();

  for (const key in form) {
    if (key !== 'file') {
      payload.append(key, form[key]);
    }
  }
  if (fileUpload.value && fileUpload.value[0]) {
    payload.append('file', fileUpload.value[0].file);
  }

  try {
    await axios.post(`/api/events/${form.id}?_method=PUT`, payload, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    router.push({ name: 'Events', query: { toast: 'Event updated successfully' } });
  } catch (err) {
    toast.validationError(err);
  }
};
defineProps({
  slug: {
    type: [String, Number],
  }
});

onMounted(() => {
  fetchEvent();
  fetchCategories();
});
</script>
