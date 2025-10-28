<template>
   <section class="content-header">
    <div class="container-fluid">
      <h1>Admin Profile</h1>
    </div>
  </section>
  <section class="content container">
    <div v-if="user">
      <h2>Welcome, {{ user?.name }}</h2>
      <p>Email: {{ user?.email }}</p>
      <button @click="logout">Logout</button>
    </div>
  </section>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  
  const user = ref(null);
  const router = useRouter();
  
  onMounted(async () => {
    const { data } = await axios.get('/api/admin/profile');
    user.value = data;
  });
  
  const logout = async () => {
    await axios.post('/api/admin/logout');
    localStorage.removeItem('token');
    localStorage.removeItem('role');
    delete axios.defaults.headers.common['Authorization'];
    router.push({name:'AdminLogin'});
  };
  </script>
  