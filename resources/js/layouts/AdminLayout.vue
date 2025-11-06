<template>
  <div class="wrapper">
    <Navbar />
    <Sidebar />
    <div class="content-wrapper">
      <!-- All child routes render here -->
      <div class="container-fluid py-4">
      <router-view />
      </div>
    </div>
    <Footer />
  </div>
</template>

<script setup>

import Footer from '@/layouts/includes/Footer.vue'
import Navbar from '@/layouts/includes/Navbar.vue'
import Sidebar from '@/layouts/includes/Sidebar.vue'

</script>

<style scoped></style>