<template>
    <div class="wrapper">
      <!-- Preloader -->
      <!-- <Preloader /> -->
  
      <!-- Navbar -->
      <Navbar />
  
      <!-- Sidebar -->
      <Sidebar />
  
      <!-- Content Wrapper -->
      <div class="content-wrapper">
        <!-- All child routes render here -->
        <router-view />
    </div>
  
      <!-- Footer -->
      <Footer />
    </div>
  </template>
  
  <script setup>
  // import '../assets/dist/js/demo.js';
  // import '../assets/dist/js/pages/dashboard2.js';

  import Footer from '@/layouts/includes/d2/Footer.vue'
  import Navbar from '@/layouts/includes/d2/Navbar.vue'
  import Preloader from '@/components/d2/Preloader.vue'
  import Sidebar from '@/layouts/includes/d2/Sidebar.vue'
  
  /* Optional: expose images if you need them in the layout */
  import UserAvatar from '@/assets/dist/img/user1-128x128.jpg'
  import UserAvatar8 from '@/assets/dist/img/user8-128x128.jpg'
  import UserAvatar3 from '@/assets/dist/img/user3-128x128.jpg'
  import UserAvatar2 from '@/assets/dist/img/user2-160x160.jpg'
  import adminLogo from '@/assets/dist/img/AdminLTELogo.png'
  
  </script>
  
  <style scoped>

  /* If you use AdminLTE CSS globally you probably don’t need anything here */
  </style>