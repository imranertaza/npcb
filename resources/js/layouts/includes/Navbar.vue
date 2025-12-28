<template>
    <nav class="main-header navbar navbar-expand navbar-dark">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <router-link :to="{ name: 'Dashboard' }" class="nav-link">Home</router-link>
                <!-- <a href="index3.html" class="nav-link">Home</a> -->
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <router-link :to="{ name: 'adminProfile' }" class="nav-link">My Profile</router-link>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">

            <!-- Notifications Dropdown Menu -->
            <li class="nav-item">
                <div class="">
                    <button @click="logout" class="btn btn-sm btn-danger nav-link">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            <!-- <li class="nav-item">
      <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
        <i class="fas fa-th-large"></i>
      </a>
    </li> -->
        </ul>
    </nav>
</template>

<script setup>
import UserAvatar from '@/assets/dist/img/user1-128x128.jpg';
import UserAvatar8 from '@/assets/dist/img/user8-128x128.jpg';
import UserAvatar3 from '@/assets/dist/img/user3-128x128.jpg';
import axios from 'axios';

const logout = async () => {
    await axios.post('/api/admin/logout')
    localStorage.clear()
    delete axios.defaults.headers.common['Authorization']
    router.push({ name: 'AdminLogin' })
}
</script>
