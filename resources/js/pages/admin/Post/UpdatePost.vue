<template>
  <DashboardHeader title="Update Post" />

  <section class="content">
    <div class="container-fluid">
      <div class="row row-cols-1">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Edit Post</h3>
          </div>

          <form @submit.prevent="updatePost">
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

              <!-- Description -->
              <div class="form-group">
                <label>Description</label>

                <SummernoteEditorVue v-model="form.description" />

              </div>

              <!-- Image Upload -->
              <div class="form-group">
                <label>Upload Image</label>
                <input type="file" class="form-control" @change="handleImageUpload" accept="image/*" />
                <img v-if="form.image" :src="getImageUrl(form.image)" alt="Post Image" height="50"
                  class="mt-2 rounded" />
              </div>

              <!-- Alt Name -->
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
                <input v-model="formattedDate" type="date" class="form-control" />
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
import DashboardHeader from '../../../components/DashboardHeader.vue';
import { toast } from 'vue3-toastify';

const route = useRoute();
const router = useRouter();
const postSlug = route.params.slug;

const form = reactive({
  post_title: '',
  slug: '',
  short_des: '',
  description: '',
  image: '',
  alt_name: '',
  video_id: '',
  publish_date: '',
  status: '1',
  meta_title: '',
  meta_keyword: '',
  meta_description: '',
  createdBy: 1,
  updatedBy: null
});

const imageFile = ref(null);

const generateSlug = () => {
  form.slug = form.post_title
    .toLowerCase()
    .trim()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');
};

const handleImageUpload = (e) => {
  imageFile.value = e.target.files[0];
};

const fetchPost = async () => {
  try {
    const res = await axios.get(`/api/posts/${postSlug}`);
    Object.assign(form, res.data.data);
  } catch (err) {
    toast.error('Failed to load post');
    console.error(err);
  }
};

const updatePost = async () => {
  const payload = new FormData();

  for (const key in form) {
    // ✅ Skip 'image' if it's just a string path
    if (key === 'image' && !(imageFile.value instanceof File)) continue;
    payload.append(key, form[key]);
  }

  // ✅ Append new image file if selected
  if (imageFile.value instanceof File) {
    payload.append('image', imageFile.value);
  }

  try {
    await axios.post(`/api/posts/${postSlug}?_method=PUT`, payload, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    toast.success('Post updated successfully!');
    router.push({ name: 'Posts' });
  } catch (err) {
    toast.error('Failed to update post');
    console.error(err);
  }
};

import { computed } from 'vue';
import SummernoteEditorVue from 'vue3-summernote-editor';
import { getImageUrl } from '../../../layouts/helpers/helpers';

const formattedDate = computed({
  get: () => form.publish_date?.slice(0, 10) || '',
  set: (val) => form.publish_date = val
});

onMounted(() => {
  fetchPost();
});
</script>
