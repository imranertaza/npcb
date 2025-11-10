<template>
    <DashboardHeader title="🔐 Manage Permission" />
    <div v-for="role in roles" :key="role.name" class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-capitalize flex-grow-1">{{ role.name }}</h5>
            <button class="btn btn-dark btn-sm ms-3" @click="save(role)" :disabled="role.name === 'super-admin'">
                Save Permissions
            </button>
        </div>


        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-2" v-for="perm in allPermissions" :key="perm">
                    <div class="form-check icheck-success d-inline">
                        <input class="" type="checkbox" :id="`${role.name}-${perm}`" v-model="role.permissions"
                            :value="perm" :disabled="role.name === 'super-admin'">
                        <label class="form-check-label text-capitalize" :for="`${role.name}-${perm}`">
                            {{ perm.replace(/[-_]/g, ' ') }}
                        </label>
                    </div>
                </div>
            </div>
            <div v-if="role.name === 'super-admin'" class="text-muted small mt-2">
                Super-admin has all permissions by default and cannot be modified.
            </div>
        </div>
    </div>
</template>

<script setup>
import '@/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css'
import DashboardHeader from '@/components/DashboardHeader.vue'
import { useToast } from '@/composables/useToast'
import axios from 'axios'
import { onMounted, ref } from 'vue'
const roles = ref([])
const allPermissions = ref([])

const toast = useToast();
const fetchRoles = async () => {
    try {
        const res = await axios.get('/api/roles-with-permissions')
        roles.value = res.data.data
    } catch (error) {
        toast.validationError(error)
    }

}

const fetchAllPermissions = async () => {
    try {
        const res = await axios.get('/api/permissions')
        allPermissions.value = res.data.data
    } catch (error) {
        toast.validationError(error)
    }
}

const save = async (role) => {
    if (role.name === 'super-admin') return
    try {
        await axios.put(`/api/roles/${role.name}/permissions`, {
            permissions: role.permissions
        })
        toast.success('Permissions updated successfully!')
    } catch (error) {
        toast.validationError(error)
    }
}

onMounted(() => {
    fetchRoles()
    fetchAllPermissions()
})
</script>