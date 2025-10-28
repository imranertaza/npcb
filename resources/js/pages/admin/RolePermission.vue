<!-- src/views/AdminRoleManager.vue -->
<template>
  <div class="container pt-4">
    <div class="d-flex justify-content-between align-items-center">
      <h3>Admin Manager</h3>
      <div>
        <div>
    <!-- Trigger Button -->
    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#createAdminModal">
      ➕ Add New Admin
    </button>

    <!-- Modal -->
    <div class="modal fade" id="createAdminModal" tabindex="-1" role="dialog" aria-labelledby="createAdminModalLabel" aria-hidden="true">
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
                  <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
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
  </div>
  </div>
      <div class="">
        <router-link :to="{ name: 'RolePermissionManager' }" class="btn btn-dark rounded-sm"><span><i class="fa fa-gear"></i> </span> Manage Permissions</router-link>
      </div>
    </div>
    <table class="table table-bordered mt-3">
      <thead class="thead-light">
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Current Role</th>
          <th>Change Role</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="admin in admins" :key="admin.id">
          <td>{{ admin.name }}</td>
          <td>{{ admin.email }}</td>
          <td>{{ admin.role }}</td>
          <td>
            <select v-model="admin.newRole" @change="updateRole(admin)" class="form-control">
              <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
            </select>
          </td>
          <button
        class="btn btn-sm btn-danger"
        @click="deleteAdmin(admin)"
        :disabled="admin.role === 'super-admin'"
      >
        Delete
      </button>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

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
  await axios.put(`/api/admins/${admin.id}/role`, { role: admin.newRole })
  admin.role = admin.newRole
}

const deleteAdmin = async (admin) => {
  if (admin.role === 'super-admin') return

  if (confirm(`Are you sure you want to delete ${admin.name}?`)) {
    await axios.delete(`/api/admins/${admin.id}`)
    admins.value = admins.value.filter(a => a.id !== admin.id)
  }
}
const form = ref({
  name: '',
  email: '',
  password: '',
  role: ''
})


const createAdmin = async () => {
  await axios.post('/api/admins', form.value)
  form.value = { name: '', email: '', password: '', role: '' }
  const modal = bootstrap.Modal.getInstance(document.getElementById('createAdminModal'))
  modal.hide()
  // Optionally refresh admin list
}

onMounted(() => {
  fetchAdmins()
  fetchRoles()
})
</script>
