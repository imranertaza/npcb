<template>
  <DashboardHeader title="Edit Category" />

  <section class="mt-3">
    <div class="card">
      <div class="card-body">
        <form v-if="form" @submit.prevent="updateCategory">
          <!-- Category Name -->
          <div class="mb-3">
            <label class="form-label">Category Name</label>
            <input v-model="form.category_name" type="text" class="form-control" required />
          </div>

          <!-- Description -->
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea v-model="form.description" class="form-control"></textarea>
          </div>

          <!-- Parent Category -->
          <div class="mb-3">
            <label class="form-label">Parent Category</label>
            <select v-model="form.parent_id" class="custom-select">
              <option value="">-- None --</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.category_name }}
              </option>
            </select>
          </div>

          <!-- Meta Title -->
          <div class="mb-3">
            <label class="form-label">Meta Title</label>
            <input v-model="form.meta_title" type="text" class="form-control" />
          </div>

          <!-- Meta Description -->
          <div class="mb-3">
            <label class="form-label">Meta Description</label>
            <input v-model="form.meta_description" type="text" class="form-control" />
          </div>

          <!-- Meta Keyword -->
          <div class="mb-3">
            <label class="form-label">Meta Keyword</label>
            <input v-model="form.meta_keyword" type="text" class="form-control" />
          </div>

          <!-- Icon ID -->
          <div class="mb-3">
            <label class="form-label">Icon ID</label>
            <input v-model="form.icon_id" type="number" class="form-control" />
          </div>

          <!-- Image Upload (Vue3Dropzone) -->
          <div class="mb-3">
            <label class="form-label">Category Image</label>
            <!-- Show existing image preview -->
            <div v-if="form.image" class="mb-2">
              <img :src="getImageUrl(form.image)" alt="Category Image" height="80" class="rounded" />
            </div>
            <Vue3Dropzone v-model="imageFile" :allowSelectOnPreview="true" />
          </div>

          <!-- Alt Name -->
          <div class="mb-3">
            <label class="form-label">Alt Name</label>
            <input v-model="form.alt_name" type="text" class="form-control" />
          </div>

          <!-- Header Menu -->
          <div class="mb-3">
            <label class="form-label">Show in Header Menu</label>
            <select v-model="form.header_menu" class="custom-select">
              <option :value="1">Yes</option>
              <option :value="0">No</option>
            </select>
          </div>

          <!-- Side Menu -->
          <div class="mb-3">
            <label class="form-label">Show in Side Menu</label>
            <select v-model="form.side_menu" class="custom-select">
              <option :value="1">Yes</option>
              <option :value="0">No</option>
            </select>
          </div>

          <!-- Sort Order -->
          <div class="mb-3">
            <label class="form-label">Sort Order</label>
            <input v-model="form.sort_order" type="number" class="form-control" />
          </div>

          <!-- Status -->
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select v-model="form.status" class="custom-select">
              <option :value="1">Active</option>
              <option :value="0">Inactive</option>
            </select>
          </div>

          <!-- Buttons -->
          <button type="submit" class="btn btn-primary">Update</button>
          <router-link :to="{ name: 'CategoryIndex' }" class="ml-2 btn btn-secondary ms-2">Cancel</router-link>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';
import DashboardHeader from '@/components/DashboardHeader.vue';
import Vue3Dropzone from "@jaxtheprime/vue3-dropzone";
import '@jaxtheprime/vue3-dropzone/dist/style.css';
import { getImageUrl } from '@/layouts/helpers/helpers';
import { useToast } from '@/composables/useToast';
const toast = useToast();
const route = useRoute();
const form = ref(null);
const categories = ref([]);
const imageFile = ref(null);

const fetchCategory = async () => {
  try {
    const res = await axios.get(`/api/categories/${route.params.id}`);
    form.value = res.data.data;
  } catch (error) {
    toast.error('Failed to load category');
  }
};

const fetchCategories = async () => {
  try {
    const res = await axios.get('/api/categories');
    categories.value = res.data.data;
  } catch (error) {
    toast.error('Failed to load categories');
  }
};

const updateCategory = async () => {
  const payload = new FormData();

  for (const key in form.value) {
    if (key !== 'image') {
      payload.append(key, form.value[key] ?? '');
    }
  }

  if (imageFile.value && imageFile.value[0]) {
    payload.append('image', imageFile.value[0].file);
  }

  try {
    const res = await axios.post(`/api/categories/${route.params.id}?_method=PUT`, payload, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    form.value = res.data.data;
    toast.success('Category updated successfully!');
  } catch (error) {
    toast.validationError(error);
  }
};

onMounted(() => {
  fetchCategory();
  fetchCategories();
});
</script>