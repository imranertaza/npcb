<template>
  <DashboardHeader title="Create Committee Member" />
  <section class="content">
    <div class="container-fluid">
      <div class="row row-cols-1">
        <div class="card card-purple">
          <div class="card-header">
            <h3 class="card-title">Create Committee Member</h3>
          </div>

          <form @submit.prevent="submitMember">
            <div class="card-body">
              <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                  <!-- Name -->
                  <div class="form-group">
                    <label>Name</label>
                    <input v-model="form.name" type="text" class="form-control" required />
                  </div>

                  <!-- Designation -->
                  <div class="form-group">
                    <label>Designation</label>
                    <input v-model="form.designation" type="text" class="form-control" required />
                  </div>

                  <!-- Order -->
                  <div class="form-group">
                    <label>Order</label>
                    <input v-model="form.order" type="number" class="form-control" min="1" />
                  </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                  <!-- Image Upload -->
                  <div class="form-group">
                    <label>Upload Image</label>
                    <Vue3Dropzone v-model="fileUpload" :allowSelectOnPreview="true" />
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
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                    <RouterLink :to="{ name: 'CommitteeMembers' }" class="btn btn-secondary btn-block mt-2">Cancel</RouterLink>
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
import DashboardHeader from '@/components/DashboardHeader.vue';
import Vue3Dropzone from "@jaxtheprime/vue3-dropzone";
import '@jaxtheprime/vue3-dropzone/dist/style.css';
import axios from 'axios';
import { reactive, ref } from 'vue';
import { useToast } from '@/composables/useToast';

const toast = useToast();
const fileUpload = ref(null);

const form = reactive({
  name: '',
  designation: '',
  order: 1,
  status: '1',
  image: null
});

const submitMember = async () => {
  const payload = new FormData();

  for (const key in form) {
    if (key !== 'image') {
      payload.append(key, form[key]);
    }
  }

  if (fileUpload.value && fileUpload.value[0]) {
    payload.append('image', fileUpload.value[0].file);
  }

  try {
    await axios.post('/api/committee-members', payload, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    toast.success('Committee member created successfully!');
  } catch (error) {
    toast.error('Failed to create committee member');
    console.error(error);
  }
};
</script>
