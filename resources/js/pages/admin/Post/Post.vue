<template>
  <DashboardHeader title="Manage Post" />

  <section class="">
    <div class="row">
      <div class="col-md-12">
        <div v-if="posts.length === 0" class="alert alert-info">No posts found.</div>

        <div v-else>
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
              <tr class="align-middle">
                <th style="width: 10px">#</th>
                <th>Title</th>
                <th>Description</th>
                <th>Image</th>
                <th v-if="authStore.hasPermission('publish-posts')">Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(post, index) in posts" :key="post.id">
                <td class="align-middle">{{ index + 1 }}</td>
                <td class="align-middle">{{ truncateText(post.post_title, 20) }}</td>
                <td class="align-middle">{{ truncateText(post.short_des, 50) }}</td>
                <td class="align-middle">
                  <img v-if="post.image" draggable="false" :src="getImageUrl(post.image)" alt="Post Image" height="50" class="rounded" />
                </td>
                <td v-if="authStore.hasPermission('publish-posts')" class="align-middle">
                  <select v-model="post.status" @change="updateStatus(post)" class=" form-control"
                    :class="post.status == 1 ? 'bg-success text-white' : 'bg-transparent text-dark'">
                    <option :value="1">Published</option>
                    <option :value="0">Draft</option>
                  </select>
                </td>
                <td class="align-middle">
                  <div class="d-flex ">
                  <router-link v-if="authStore.hasPermission('view-posts')" :to="{ name: 'ShowPost', params: { slug: post.slug } }" class="btn btn-sm btn-dark">
                    <i class="fas fa-eye"></i>
                  </router-link>
                  <router-link v-if="authStore.hasPermission('edit-posts')" :to="{ name: 'UpdatePost', params: { slug: post.slug } }" class="ml-2 
                  btn btn-sm btn-dark">
                    <i class="fas fa-pencil-alt"></i>
                  </router-link>
                  <button v-if="authStore.hasPermission('delete-posts')" class="ml-2 btn btn-sm btn-danger" @click="confirmDelete(post)">
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
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DashboardHeader from '../../../components/DashboardHeader.vue';
import { toast } from 'vue3-toastify';
const route = useRoute();
const posts = ref([]);
const authStore =useAuthStore()

onMounted(async () => {
  try {
    const response = await axios.get('/api/posts');
    posts.value = response.data.data.data;
    console.log(posts.value)
  } catch (error) {
    console.error('Error fetching posts:', error);
  }
  if (route.query.toast) {
    toast.success(route.query.toast);
  }
});

const updateStatus = async (post) => {
  try {
    const response = await axios.patch(`/api/posts/${post.slug}/status`, {
      status: post.status
    });

    if (response.data.status === '1') {
      toast.success('Post published');
    } else {
      toast.info('Post set to draft');
    }
  } catch (error) {
    toast.error('Failed to update status');
    console.error(error);
  }
};
import { inject } from 'vue';
import { getImageUrl, truncateText } from '../../../layouts/helpers/helpers';
import { useRoute } from 'vue-router';
import { useAuthStore } from '../../../store/auth';
const $swal = inject('$swal');

const confirmDelete = async (post) => {
  const result = await $swal({
    title: `Delete "${post.post_title}"?`,
    text: 'This action cannot be undone.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete',
    cancelButtonText: 'Cancel',
    reverseButtons: true
  });

  if (result.isConfirmed) {
    try {
      const response = await axios.delete(`/api/posts/${post.slug}`);
      if (response.data.success) {
        toast.success('Post deleted successfully!');
        posts.value = posts.value.filter(p => p.id !== post.id);
      } else {
        toast.error('Failed to delete post.');
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
