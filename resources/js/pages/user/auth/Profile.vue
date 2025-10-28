<template>
    <div v-if="user">
      <h2>Welcome, {{ user.name }}</h2>
      <p>Email: {{ user.email }}</p>
      <button @click="logout">Logout</button>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  
  const user = ref(null);
  const router = useRouter();
  
  onMounted(async () => {
    const { data } = await axios.get('/api/user/profile');
    user.value = data;
  });
  
  const logout = async () => {
    await axios.post('/api/user/logout');
    localStorage.removeItem('token');
    delete axios.defaults.headers.common['Authorization'];
    router.push('/login');
  };
  </script>
  