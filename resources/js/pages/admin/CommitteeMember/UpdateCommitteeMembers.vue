<template>
    <DashboardHeader title="Update Committee Member" />

    <section class="content">
        <div class="container-fluid">
            <div class="row row-cols-1">
                <div class="card card-purple">
                    <div class="card-header">
                        <h3 class="card-title">Edit Committee Member</h3>
                    </div>

                    <form @submit.prevent="updateMember">
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
                                        <Vue3Dropzone v-model="fileUpload" v-model:previews="previews" mode="edit"
                                            :allowSelectOnPreview="true" />
                                        <small class="text-muted">Recommended:265 × 379px</small>

                                        <!-- Preview -->
                                        <div v-if="previews && previews.length" class="mt-3">
                                            <div v-for="(preview, idx) in previews" :key="idx">
                                                <img :src="preview" alt="Preview" class="img-fluid rounded border"
                                                    style="max-height:120px" />
                                            </div>
                                        </div>
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
                                        <button type="submit" class="btn btn-success btn-block">Update</button>
                                        <RouterLink :to="{ name: 'CommitteeMembers' }"
                                            class="btn btn-secondary btn-block mt-2">Cancel</RouterLink>
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
import axios from 'axios';
import { onMounted, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import DashboardHeader from '@/components/DashboardHeader.vue';
import { useToast } from '@/composables/useToast';
import { getImageUrl } from '@/layouts/helpers/helpers';
import Vue3Dropzone from '@jaxtheprime/vue3-dropzone';
import '@jaxtheprime/vue3-dropzone/dist/style.css';

const toast = useToast();
const route = useRoute();
const router = useRouter();
const previews = ref([]);
const form = reactive({
    id: null,
    name: '',
    designation: '',
    image: '',
    order: 1,
    status: '1'
});
const fileUpload = ref(null);

// Fetch committee member
const fetchMember = async () => {
    try {
        const res = await axios.get(`/api/committee-members/${route.params.id}`);
        Object.assign(form, res.data.data);
        if (form.image) {
            previews.value = [getImageUrl(form.image)];
        }
    } catch (err) {
        toast.error('Failed to load committee member');
        console.error(err);
    }
};

// Update committee member
const updateMember = async () => {
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
        await axios.post(`/api/committee-members/${form.id}?_method=PUT`, payload, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        router.push({ name: 'CommitteeMembers', query: { toast: 'Committee member updated successfully' } });
    } catch (err) {
        toast.validationError(err);
    }
};

onMounted(() => {
    form.id = route.params.id;
    fetchMember();
});
</script>
