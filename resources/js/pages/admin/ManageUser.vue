<!-- src/views/AdminRoleManager.vue -->
<template>
  <DashboardHeader title="Manage User">
    <!-- Trigger Button -->
    <button class="btn btn-success" data-toggle="modal" data-target="#createAdminModal">
      ➕ Add New User
    </button>

    <!-- Modal -->
    <div class="modal fade" id="createAdminModal" tabindex="-1" role="dialog" aria-labelledby="createAdminModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content shadow-sm">
          <div class="modal-header bg-light">
            <h5 class="modal-title" id="createAdminModalLabel">Create New Admin</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <form @submit.prevent="createAdmin">
              <div class="form-group">
                <label>Name</label>
                <input v-model="form.name" type="text" class="form-control" required />
              </div>

              <div class="form-group">
                <label>Email</label>
                <input v-model="form.email" type="email" class="form-control" required />
              </div>

              <div class="form-group">
                <label>Password</label>
                <input v-model="form.password" type="password" class="form-control" required />
              </div>

              <div class="form-group">
                <label>Role</label>
                <select v-model="form.role" class="form-control" required>
                  <option disabled value="">Select role</option>
                  <option v-for="role in roles" :key="role" :value="role" :disabled="role === 'super-admin'">{{
                    role }}</option>
                </select>
              </div>

              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Create Admin</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </DashboardHeader>

  <table class="table table-bordered mt-3">
    <thead class="thead-light">
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Current Role</th>

        <th v-if="authStore.hasPermission('update-users')">Change Role</th>
        <th v-if="authStore.hasPermission('update-users') || authStore.hasPermission('delete-users')">Action</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="admin in admins" :key="admin.id">
        <td>{{ admin.name }}</td>
        <td>{{ admin.email }}</td>
        <td>{{ admin.role }}</td>
        <td v-if="authStore.hasPermission('update-users')">
          <select v-model="admin.newRole" @change="updateRole(admin)" :disabled="admin.role === 'super-admin'"
            class="form-control">
            <option v-for="role in roles" :key="role" :value="role" :disabled="role === 'super-admin'">{{ role }}
            </option>
          </select>
        </td>
        <td v-if="authStore.hasPermission('update-users') || authStore.hasPermission('delete-users')" class="text-center">
          <router-link v-if="authStore.hasPermission('update-users')"
            :to="{ name: 'AdminUserUpdate', params: { id: admin.id } }" class="btn btn-sm btn-info">
            <i class="fas fa-pencil-alt"></i>
          </router-link>
          <button  v-if="authStore.hasPermission('delete-users')" class="btn btn-sm btn-danger ml-2"
            @click="deleteAdmin(admin)" :disabled="admin.role === 'super-admin'">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useToast } from '../../composables/useToast'
import { useRouter } from 'vue-router'
const toast = useToast()
import { inject } from 'vue';
import DashboardHeader from '../../components/DashboardHeader.vue'
import { useAuthStore } from '../../store/auth'
const authStore = useAuthStore()

const $swal = inject('$swal');
const admins = ref([])
const roles = ref([])

const fetchAdmins = async () => {
  const res = await axios.get('/api/admins')
  admins.value = res.data.data.map(admin => ({
    ...admin,
    newRole: admin.role
  }))
}

const fetchRoles = async () => {
  const res = await axios.get('/api/roles')
  roles.value = res.data.data
}

const updateRole = async (admin) => {
  const isUpdate = await axios.put(`/api/admins/${admin.id}/role`, { role: admin.newRole })
  if (isUpdate.data.success) {
    toast.success('Role updated successfully!')
  } else {
    toast.error('Failed to update role.')
  }
  admin.role = admin.newRole
}

const deleteAdmin = async (admin) => {
  if (admin.role === 'super-admin') return;

  const result = await $swal({
    title: `Delete ${admin.name}?`,
    text: 'This action cannot be undone.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete',
    cancelButtonText: 'Cancel',
    reverseButtons: true
  });

  if (result.isConfirmed) {
    try {
      const response = await axios.delete(`/api/admins/${admin.id}`);
      if (response.data.success) {
        toast.success('User deleted successfully!');
        admins.value = admins.value.filter(a => a.id !== admin.id);
      } else {
        toast.error('Failed to delete user.');
      }
    } catch (error) {
      toast.error('Something went wrong.');
    }
  } else {
    toast.info('Deletion cancelled.');
  }
};

const form = ref({
  name: '',
  email: '',
  password: '',
  role: ''
})

const route = useRouter()
const createAdmin = async () => {
  const isCreated = await axios.post('/api/admins', form.value)
  if (isCreated.data.success) {
    toast.success('User created successfully!')
    document.getElementById('createAdminModal').classList.remove('show')
    document.body.classList.remove('modal-open')
    document.getElementsByClassName('modal-backdrop')[0].remove()
    // admins.value = admins.value.push(isCreated.data)
    admins.value = [...admins.value, isCreated.data.data]
  } else {
    toast.error('Failed to create user.')
  }
  form.value = { name: '', email: '', password: '', role: '' }
  // Optionally refresh admin list
}

onMounted(() => {
  fetchAdmins()
  fetchRoles()
})
</script>
