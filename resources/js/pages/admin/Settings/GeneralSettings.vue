<template>
    <DashboardHeader title="General Settings" />
  
    <section class="container py-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <form @submit.prevent="submitSettings">
            <div class="row g-3">
              <!-- Site Name -->
              <div class="col-md-6">
                <label class="form-label">Site Name</label>
                <input v-model="form.site_name" type="text" class="form-control" />
              </div>
  
              <!-- Site Email -->
              <div class="col-md-6">
                <label class="form-label">Site Email</label>
                <input v-model="form.site_email" type="email" class="form-control" />
              </div>
  
              <!-- Site Phone -->
              <div class="col-md-6">
                <label class="form-label">Site Phone</label>
                <input v-model="form.site_phone" type="text" class="form-control" />
              </div>
              <div class="col-md-6">
                </div> 
                <div class="col-12">
                   <h3 class="mt-3 mb-2">Logos and Icons</h3> 
                </div>
                 <!-- Logo -->
              <div class="col-md-6">
                <label class="form-label">Logo</label>
                <Vue3Dropzone
                  v-model="logoFile"
                  v-model:previews="logoPreview"
                  mode="edit"
                  :allowSelectOnPreview="true"
                  :maxFiles="1"
                />
              </div> 
              <!-- Favicon -->
              <div class="col-md-6">
                <label class="form-label">Favicon</label>
                <Vue3Dropzone
                  v-model="faviconFile"
                  v-model:previews="faviconPreview"
                  mode="edit"
                  :allowSelectOnPreview="true"
                  :maxFiles="1"
                 
                />
              </div>
  
             
            </div>
  
            <div class="mt-4 text-end">
              <button type="submit" class="btn btn-primary px-4">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import DashboardHeader from '../../../components/DashboardHeader.vue';
  import axios from 'axios';
  import { toast } from 'vue3-toastify';
  import Vue3Dropzone from '@jaxtheprime/vue3-dropzone';
  import '@jaxtheprime/vue3-dropzone/dist/style.css';
  import { getImageUrl } from '../../../layouts/helpers/helpers';
  
  const form = ref({
    site_name: '',
    site_email: '',
    site_phone: '',
  });
  
  const faviconFile = ref([]);
  const faviconPreview = ref([]);
  const logoFile = ref([]);
  const logoPreview = ref([]);
  
  const fetchSettings = async () => {
    try {
      const response = await axios.get('/api/settings');
      const data = response.data.data;
  
      data.forEach(setting => {
        if (form.value.hasOwnProperty(setting.label)) {
          form.value[setting.label] = setting.value;
        }
        if (setting.label === 'favicon') {
          faviconPreview.value = [getImageUrl(setting.value)];
        }
        if (setting.label === 'logo') {
          logoPreview.value = [getImageUrl(setting.value)];
        }
      });
    } catch (error) {
      toast.error('Failed to load settings');
      console.error(error);
    }
  };
  
  const submitSettings = async () => {
    const payload = new FormData();
  
    for (const key in form.value) {
      payload.append(key, form.value[key]);
    }
  
    if (faviconFile.value[0]?.file) {
      payload.append('favicon', faviconFile.value[0].file);
    }
    if (logoFile.value[0]?.file) {
      payload.append('logo', logoFile.value[0].file);
    }
//   console.log([...payload]);
    try {
      await axios.post('/api/settings/update', payload, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
      toast.success('Settings updated successfully');
    } catch (err) {
      toast.error('Failed to update settings');
      console.error(err);
    }
  };
  
  onMounted(() => {
    fetchSettings();
  });
  </script>
  