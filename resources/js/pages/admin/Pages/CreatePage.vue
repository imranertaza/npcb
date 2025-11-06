<template>
  <DashboardHeader title="Create Page" />
  <section class="content">
    <div class="container-fluid">
      <div class="row row-cols-1">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Create Page</h3>
          </div>

          <form @submit.prevent="submitPage">
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

              <!-- Description (Summernote) -->
              <div class="form-group">
                <label>Description</label>
                <SummernoteEditorVue v-model="form.page_description" />
              </div>

              <!-- Image-->
              <div class="form-group">
                <label>Upload Image</label>
                <Vue3Dropzone v-model="imageFile" :allowSelectOnPreview="true" accept="image/*" />
              </div>

              <!-- Page Type -->
              <div class="form-group">
                <label>Page Type</label>
                <select v-model="form.page_type" class="form-control" required>
                  <option value="page">Page</option>
                  <option value="post">Post</option>
                  <option value="video">Video</option>
                  <option value="analyses">Analyses</option>
                </select>
              </div>

              <!-- Status -->
              <div class="form-group">
                <label>Status</label>
                <select v-model="form.status" class="form-control">
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
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>

import { reactive, ref } from 'vue';
import axios from 'axios';
import DashboardHeader from '../../../components/DashboardHeader.vue';
import { toast } from 'vue3-toastify';
import SummernoteEditorVue from 'vue3-summernote-editor';

import Vue3Dropzone from "@jaxtheprime/vue3-dropzone";
import '@jaxtheprime/vue3-dropzone/dist/style.css'

const imageFile = ref(null);

const form = reactive({
  page_title: '',
  slug: '',
  short_des: '',
  page_description: '',
  f_image: null,
  page_type: 'page',
  status: 'Active',
  meta_title: '',
  meta_keyword: '',
  meta_description: '',
  temp: 'default.php'
});


const generateSlug = () => {
  form.slug = form.page_title
    .toLowerCase()
    .trim()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');
};

const submitPage = async () => {
  
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
    const response = await axios.post('/api/pages', payload, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    toast.success('Page created successfully!');
  } catch (error) {
    toast.error('Failed to create page');
    console.error(error);
  }
};
</script>
