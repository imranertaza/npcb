<template>
    <DashboardHeader title="Manage Categories" />
  
    <section>
      <div class="row">
        <div class="col-md-12">
          <div v-if="categories.length === 0" class="alert alert-info">
            No categories found.
          </div>
  
          <div v-else>
            <table class="table table-bordered table-hover">
              <thead class="thead-light">
                <tr class="align-middle">
                  <th style="width: 10px">#</th>
                  <th>Name</th>
                  <th>Parent</th>
                  <th>Description</th>
                  <th v-if="authStore.hasPermission('update-categories')">Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(cat, index) in categories" :key="cat.id">
                  <td class="align-middle">{{ index + 1 }}</td>
                  <td class="align-middle">{{ truncateText(cat.category_name, 25) }}</td>
                  <td class="align-middle">
                    {{ cat.parent ? cat.parent.category_name : '-' }}
                  </td>
                  <td class="align-middle">{{ truncateText(cat.description, 50) }}</td>
  
                  <!-- Status dropdown -->
                  <td v-if="authStore.hasPermission('update-categories')" class="align-middle">
                    <select
                      v-model="cat.status"
                      @change="updateStatus(cat)"
                      class="form-control"
                      :class="cat.status == 1 ? 'bg-success text-white' : 'bg-transparent text-dark'"
                    >
                      <option :value="1">Active</option>
                      <option :value="0">Inactive</option>
                    </select>
                  </td>
  
                  <!-- Actions -->
                  <td class="align-middle">
                    <div class="d-flex">
                      <router-link
                        v-if="authStore.hasPermission('view-categories')"
                        :to="{ name: 'ShowCategory', params: { id: cat.id } }"
                        class="btn btn-sm btn-dark"
                      >
                        <i class="fas fa-eye"></i>
                      </router-link>
  
                      <router-link
                        v-if="authStore.hasPermission('edit-categories')"
                        :to="{ name: 'UpdateCategory', params: { id: cat.id } }"
                        class="ml-2 btn btn-sm btn-dark"
                      >
                        <i class="fas fa-pencil-alt"></i>
                      </router-link>
  
                      <button
                        v-if="authStore.hasPermission('delete-categories')"
                        class="ml-2 btn btn-sm btn-danger"
                        @click="confirmDelete(cat)"
                      >
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  import { ref, onMounted, inject } from 'vue';
  import axios from 'axios';
  import DashboardHeader from '../../../components/DashboardHeader.vue';
  import { toast } from 'vue3-toastify';
  import { useAuthStore } from '../../../store/auth';
  import { truncateText } from '../../../layouts/helpers/helpers';
  
  const categories = ref([]);
  const authStore = useAuthStore();
  const $swal = inject('$swal');
  
  // Fetch categories
  onMounted(async () => {
    try {
      const response = await axios.get('/api/categories');
      categories.value = response.data.data; // ApiResponse::success returns { success, message, data }
    } catch (error) {
      toast.error('Error fetching categories');
      console.error(error);
    }
  });
  
  // Update status
  const updateStatus = async (cat) => {
    try {
      const response = await axios.patch(`/api/categories/${cat.id}`, {
        status: cat.status,
      });
      if (cat.status == 1) {
        toast.success('Category activated');
      } else {
        toast.info('Category deactivated');
      }
    } catch (error) {
      toast.error('Failed to update status');
      console.error(error);
    }
  };
  
  // Delete category
  const confirmDelete = async (cat) => {
    const result = await $swal({
      title: `Delete "${cat.category_name}"?`,
      text: 'This action cannot be undone.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel',
      reverseButtons: true,
    });
  
    if (result.isConfirmed) {
      try {
        const response = await axios.delete(`/api/categories/${cat.id}`);
        if (response.data.success) {
          toast.success('Category deleted successfully!');
          categories.value = categories.value.filter((c) => c.id !== cat.id);
        } else {
          toast.error('Failed to delete category.');
        }
      } catch (error) {
        toast.error('Something went wrong.');
        console.error(error);
      }
    } else {
      toast.info('Deletion cancelled.');
    }
  };
  </script>
  