<template>
  <DashboardHeader title="Update Section" />

  <section class="content">
    <div class="container-fluid">
      <div class="row row-cols-1">
        <div class="card card-purple">
          <div class="card-header">
            <h3 class="card-title">Edit Section - {{ form.key }}</h3>
          </div>

          <form @submit.prevent="updateNotice">
            <div class="card-body">
              <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                  <!-- Notice Title -->
                  <div class="form-group">
                    <label>Section Title</label>
                    <input v-model="form.title" @input="form.name = generateSlug(form.title)" type="text"
                      class="form-control" required />
                  </div>
                  <!-- Description -->
                  <div class="form-group">
                    <label>Section Content</label>
                    <RichTextEditor v-model="form.content" placeholder="Write notice details here..." class="editor" />
                  </div>
                  <div v-if="form.home_content && form.key == 'about_mission_vision'" class="form-group">
                    <label>Home Page Content </label>
                    <RichTextEditor v-model="form.home_content" placeholder="Write notice details here..."
                      class="editor" />
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
                        <!-- If Image -->
                        <img :src="preview" alt="Preview" class="img-fluid rounded border" style="max-height:120px" />
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
                    <RouterLink :to="{ name: 'Notices' }" class="btn btn-secondary btn-block mt-2">Cancel</RouterLink>
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
import { onMounted, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import DashboardHeader from '@/components/DashboardHeader.vue';
import { useToast } from '@/composables/useToast';
import { getImageUrl } from '@/layouts/helpers/helpers';
import Vue3Dropzone from '@jaxtheprime/vue3-dropzone';
import '@jaxtheprime/vue3-dropzone/dist/style.css';
import RichTextEditor from '@/components/RichTextEditor.vue';
import { generateSlug } from '../../../layouts/helpers/helpers';

const toast = useToast();
const route = useRoute();
const router = useRouter();
const previews = ref([]);
const isPdf = (filename) => /\.pdf$/i.test(filename);

const form = reactive({
  id: null,
  title: '',
  key: '',
  content: '',
  file: '',
  status: '1',
  createdBy: 1,
  updatedBy: null
});

const fileUpload = ref(null);

// Fetch notice
const fetchNotice = async () => {
  try {
    const res = await axios.get(`/api/sections/${route.params.id}`);
    Object.assign(form, res.data.data.data);
    form.key = res.data.data.name;
    form.id = res.data.data.id;
    if (form.image) {
      previews.value = [getImageUrl(form.image)];
    } else if (form.file && isPdf(form.file)) {
      previewsPdf.value = [getImageUrl(form.file)];
    }
  } catch (err) {
    toast.error('Failed to load section data');
    console.error(err);
  }
};

// Update notice
const updateNotice = async () => {
  let data = {};
  if (form.key != 'banner_section') {
    data = {
      title: form.title,
      content: form.content,
      home_content: form?.home_content,
      image: form.image,
    }
  }
  const payload = new FormData();

  payload.append('data', JSON.stringify(data));

  if (fileUpload.value && fileUpload.value[0]) {
    payload.append('image', fileUpload.value[0].file);
  }

  try {
    await axios.post(`/api/sections/${form.id}?_method=PUT`, payload, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    router.push({ name: 'Section', query: { toast: 'Section updated successfully' } });
  } catch (err) {
    toast.validationError(err);
  }
};

defineProps({
  id: {
    type: [String, Number],
  }
});

onMounted(() => {
  fetchNotice();
});
</script>
