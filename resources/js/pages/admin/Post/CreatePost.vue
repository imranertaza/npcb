<template>
  <DashboardHeader title="Create Post" />
  <section class="content">
    <div class="container-fluid">
      <div class="row row-cols-1">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Create Post</h3>
          </div>

          <form @submit.prevent="submitPost">
            <div class="card-body">
              <!-- Title & Slug -->
              <div class="form-group">
                <label>Post Title</label>
                <input v-model="form.post_title" @input="generateSlug" type="text" class="form-control" required />
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
                <SummernoteEditorVue v-model="form.description" />
              </div>

              <!-- Image & Alt -->
              <div class="form-group">
                <label>Upload Image</label>
                <Vue3Dropzone v-model="imageFile" :allowSelectOnPreview="true" />

                <!-- <input type="file" class="form-control" @change="handleImageUpload" accept="image/*" required /> -->
              </div>
              <div class="form-group">
                <label>Alt Name</label>
                <input v-model="form.alt_name" type="text" class="form-control" />
              </div>

              <!-- Video ID -->
              <div class="form-group">
                <label>Video ID</label>
                <input v-model="form.video_id" type="text" class="form-control" />
              </div>

              <!-- Publish Date -->
              <div class="form-group">
                <label>Publish Date</label>
                <input v-model="form.publish_date" type="date" class="form-control" />
              </div>

              <!-- Status -->
              <div class="form-group">
                <label>Status</label>
                <select v-model="form.status" class="form-control">
                  <option value="1">Published</option>
                  <option value="0">Draft</option>
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
  post_title: '',
  slug: '',
  short_des: '',
  description: '',
  alt_name: '',
  video_id: '',
  publish_date: '',
  status: '1',
  meta_title: '',
  meta_keyword: '',
  meta_description: '',
  image: null
});
// const handleImageUpload = (e) => {
//   imageFile.value = e.target.files[0];
//   form.image = imageFile.value;
// }
const generateSlug = () => {
  form.slug = form.post_title
    .toLowerCase()
    .trim()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');
};

const submitPost = async () => {
  const payload = new FormData();

  // Append form fields
  for (const key in form) {
    if (key !== 'image') {
      payload.append(key, form[key]);
    }
  }
  // Append image file from Dropzone
  if (imageFile.value && imageFile.value[0]) {
    payload.append('image', imageFile.value[0].file);
  }
  try {
    const response = await axios.post('/api/posts', payload, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    toast.success('Post created successfully!');
  } catch (error) {
    toast.error('Failed to create post');
    console.error(error);
  }
};
</script>
