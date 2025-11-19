<template>
  <DashboardHeader title="Update Page" />

  <section class="content">
    <div class="container-fluid">
      <div class="row row-cols-1">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Edit Page</h3>
          </div>

          <form @submit.prevent="updatePage">
            <div class="card-body">
              <!-- Title & Slug -->
              <div class="form-group">
                <label>Page Title</label>
                <input v-model="form.page_title" @input="generateSlug" type="text" class="form-control" required />
              </div>
              <div class="form-group">
                <label>Slug</label>
                <input v-model="form.slug" type="text" class="form-control" required />
              </div>

              <!-- Short Description -->
              <div class="form-group">
                <label>Short Description</label>
                <input v-model="form.short_des" type="text" class="form-control" required />
              </div>

              <!-- Description -->
              <div class="form-group">
                <label>Description</label>
                <RichTextEditor v-model="form.page_description" placeholder="Write your amazing post here..."
                  class="editor"></RichTextEditor>
              </div>

              <!-- Image Upload -->
              <div class="form-group">
                <label>Upload Image</label>
                <Vue3Dropzone v-model="imageFile" v-model:previews="previews" mode="edit"
                  :allowSelectOnPreview="true" />
              </div>

              <!-- Page Type -->
              <div class="form-group">
                <label>Page Type</label>
                <select v-model="form.page_type" class="custom-select" required>
                  <option value="page">Page</option>
                  <option value="post">Post</option>
                  <option value="video">Video</option>
                  <option value="analyses">Analyses</option>
                </select>
              </div>

              <!-- Status -->
              <div class="form-group">
                <label>Status</label>
                <select v-model="form.status" class="custom-select">
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
                </select>
              </div>

              <!-- SEO Meta -->
              <div class="form-group">
                <label>Meta Title</label>
                <input v-model="form.meta_title" type="text" class="form-control" />
              </div>
              <div class="form-group">
                <label>Meta Keywords</label>
                <input v-model="form.meta_keyword" type="text" class="form-control"
                  placeholder="comma, separated, keywords" />
              </div>
              <div class="form-group">
                <label>Meta Description</label>
                <textarea v-model="form.meta_description" class="form-control"></textarea>
              </div>
            </div>

            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import DashboardHeader from '@/components/DashboardHeader.vue';
import { getImageUrl } from '@/layouts/helpers/helpers';
import Vue3Dropzone from '@jaxtheprime/vue3-dropzone';
import '@jaxtheprime/vue3-dropzone/dist/style.css';
import { useToast } from '@/composables/useToast';
import RichTextEditor from '../../../components/RichTextEditor.vue';

const toast = useToast();
const previews = ref();
const route = useRoute();
const router = useRouter();
const pageSlug = route.params.slug;

const form = reactive({
  page_title: '',
  slug: '',
  short_des: '',
  page_description: '',
  f_image: '',
  page_type: 'page',
  status: 'Active',
  meta_title: '',
  meta_keyword: '',
  meta_description: '',
  temp: 'default.php',
  createdBy: 1,
  updatedBy: null
});

const imageFile = ref(null);

const generateSlug = () => {
  form.slug = form.page_title
    .toLowerCase()
    .trim()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');
};


const fetchPage = async () => {
  try {
    const res = await axios.get(`/api/pages/${pageSlug}`);
    Object.assign(form, res.data.data);
    previews.value = [getImageUrl(form.f_image)];
  } catch (err) {
    console.error(err);
  }
};

const updatePage = async () => {
  const payload = new FormData();

  // Append form fields
  for (const key in form) {
    if (key !== 'f_image') {
      payload.append(key, form[key]);
    }
  }
  // Append image file from Dropzone
  if (imageFile.value && imageFile.value[0]) {
    payload.append('f_image', imageFile.value[0].file);
  }

  try {
    await axios.post(`/api/pages/${form.id}?_method=PUT`, payload, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    router.push({ name: 'Pages', query: { toast: 'Page updated successfully!' } });
  } catch (err) {
    toast.validationError(err);
  }
};

onMounted(() => {
  fetchPage();
});
</script>
