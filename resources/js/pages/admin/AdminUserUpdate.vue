<template>
    <DashboardHeader title="Update User" />
  
    <section class="container py-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <form @submit.prevent="updateUser">
            <div class="form-group mb-3">
              <label>Name</label>
              <input v-model="form.name" type="text" class="form-control" required />
            </div>
  
            <div class="form-group mb-3">
              <label>Email</label>
              <input v-model="form.email" type="email" class="form-control" required />
            </div>
  
            <div class="form-group mb-3">
              <label>Password <small class="text-muted">(leave blank to keep current)</small></label>
              <input v-model="form.password" type="password" class="form-control" />
            </div>
  
            <div class="form-group mb-3">
              <label>Role</label>
              <select v-model="form.role" class="form-control" required>
                <option disabled value="">Select role</option>
                <option v-for="role in roles" :key="role" :value="role" :disabled="role === 'super-admin'">
                  {{ role }}
                </option>
              </select>
            </div>
  
            <div class="text-end">
              <button type="submit" class="btn btn-primary px-4">Update User</button>
              <router-link :to="{ name: 'RolePermission' }" class="btn btn-secondary ms-2">Cancel</router-link>
            </div>
          </form>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import axios from 'axios'
  import { useToast } from '../../composables/useToast'
  import DashboardHeader from '../../components/DashboardHeader.vue'
  
  const toast = useToast()
  const route = useRoute()
  const router = useRouter()
  
  const roles = ref([])
  const form = ref({
    name: '',
    email: '',
    password: '',
    role: ''
  })
  
  // Fetch roles
  const fetchRoles = async () => {
    const res = await axios.get('/api/roles')
    roles.value = res.data.data
  }
  
  // Fetch user by ID
  const fetchUser = async () => {
    try {
      const res = await axios.get(`/api/admins/${route.params.id}`)
      const user = res.data.data
      form.value.name = user.name
      form.value.email = user.email
      form.value.role = user.role
      form.value.password = '' // leave blank
    } catch (error) {
      toast.error('Failed to load user')
    }
  }
  
  // Update user
  const updateUser = async () => {
    try {
      const payload = { ...form.value }
      if (!payload.password) delete payload.password
      const res = await axios.put(`/api/admins/${route.params.id}`, payload)
      if (res.data.success) {
        toast.success('User updated successfully!')
        router.push({ name: 'RolePermission' }) // back to user list
      } else {
        toast.error('Failed to update user.')
      }
    } catch (error) {
      toast.error('Something went wrong.')
    }
  }
  
  onMounted(() => {
    fetchRoles()
    fetchUser()
  })
  </script>
  