<template>
  <div class="wrapper">
    <Navbar />
    <!-- <Sidebar /> -->
     <SidebarVue/>
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
import "@/assets/dist/css/adminlte.min.css";
import SidebarVue from './includes/SidebarVue.vue';

</script>

<style scoped></style>