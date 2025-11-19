<template>
  <DashboardHeader title="Create Category" />

  <section class="mt-3">
    <div class="card">
      <div class="card-body">
        <form @submit.prevent="createCategory">
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
            <Multiselect v-model="form.parent_id" :options="categoriesOptions" :reduce="option => option.value"
              placeholder="Select parent category" searchable />
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

          <!-- Image -->
          <div class="mb-3">
            <label class="form-label">Category Image</label>
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
            <v-select v-model="form.side_menu" :options="[
              { label: 'Yes', value: 1 },
              { label: 'No', value: 0 }
            ]" :reduce="option => option.value" />
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
          <button type="submit" class="btn btn-primary">Create</button>
          <router-link :to="{ name: 'CategoryIndex' }" class="ml-2 btn btn-secondary ms-2">Cancel</router-link>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
import { reactive, ref, onMounted, computed } from 'vue';
import axios from 'axios';
import DashboardHeader from '@/components/DashboardHeader.vue';
import Vue3Dropzone from "@jaxtheprime/vue3-dropzone";
import '@jaxtheprime/vue3-dropzone/dist/style.css';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';
import { useToast } from '@/composables/useToast';
const toast = useToast();

const imageFile = ref(null);

const form = reactive({
  category_name: '',
  description: '',
  parent_id: '',
  meta_title: '',
  meta_description: '',
  meta_keyword: '',
  icon_id: '',
  image: null,
  alt_name: '',
  header_menu: 0,
  side_menu: 0,
  sort_order: 0,
  status: 1,
});

const categories = ref([]);

const fetchCategories = async () => {
  try {
    const res = await axios.get('/api/categories?all=1');
    categories.value = res.data.data;
  } catch (error) {
    toast.error('Failed to load categories');
  }
};

const createCategory = async () => {
  const payload = new FormData();

  for (const key in form) {
    if (key !== 'image') {
      payload.append(key, form[key]);
    }
  }

  if (imageFile.value && imageFile.value[0]) {
    payload.append('image', imageFile.value[0].file);
  }

  try {
     await axios.post('/api/categories', payload, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    toast.success('Category created successfully!');
    Object.assign(form, {
      category_name: '',
      description: '',
      parent_id: '',
      meta_title: '',
      meta_description: '',
      meta_keyword: '',
      icon_id: '',
      image: null,
      alt_name: '',
      header_menu: 0,
      side_menu: 0,
      sort_order: 0,
      status: 1,
    });
    imageFile.value = null;
  } catch (error) {
    toast.validationError(error);
  }
};

const categoriesOptions = computed(() => {
  return [
    { label: '-- None --', value: '' },
    ...categories.value.map(cat => ({
      label: cat.category_name,
      value: cat.id
    }))
  ];
});

onMounted(fetchCategories);
</script>

<style>
.v-select {
  background-color: white;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
}

.v-select .vs__dropdown-toggle {
  padding: 0.375rem 0.75rem;
  border: none;
}

.v-select .vs__selected-options {
  padding: 0;
}

.v-select .vs__search {
  padding: 0;
}
</style>
