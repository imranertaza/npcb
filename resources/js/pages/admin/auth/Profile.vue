<template>
  <DashboardHeader title="Admin Profile" />

  <section class="">
    <div class="row container-fluid">
      <div v-if="user">
        <h4>Welcome, {{ user?.name }}</h4>
        <p>Email: {{ user?.email }}</p>
        <button class="btn btn-danger" @click="logout">Logout</button>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import DashboardHeader from '@/components/DashboardHeader.vue';
import { useToast } from '../../../composables/useToast';

const user = ref(null);
const router = useRouter();
const toast = useToast();
onMounted(async () => {
  try {
    const { data } = await axios.get('/api/admin/profile');
    user.value = data;
  } catch (error) {
    toast.validationError(error);
  }

});

const logout = async () => {
  try {
    await axios.post('/api/admin/logout');
    localStorage.removeItem('token');
    localStorage.removeItem('role');
    delete axios.defaults.headers.common['Authorization'];
    router.push({ name: 'AdminLogin' });
  } catch (error) {
    toast.validationError(error);
  }
};
</script>