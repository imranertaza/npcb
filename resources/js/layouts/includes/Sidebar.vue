<template>
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <router-link :to="{ name: 'Dashboard' }" class="brand-link">
      <img :src="adminLogo" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </router-link>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img :src="UserAvatar2" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
          <li class="nav-item">
            <router-link :to="{ name: 'Dashboard' }" class="nav-link"
              :class="{ active: isRouteActive({ name: 'Dashboard' }) }">
              <i class="fas fa-tachometer-alt nav-icon"></i>
              <p>Dashboard</p>
            </router-link>
          </li>
          <!-- <li class="nav-header">Manage Frontend</li> -->
          <li class="nav-item" :class="{ 'menu-is-opening menu-open': isActiveMenu(['Pages', 'CreatePage']) }">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Page
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link :to="{ name: 'Pages' }" :class="{ active: isRouteActive({ name: 'Pages' }) }"
                  class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Page List</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'CreatePage' }" :class="{ active: isRouteActive({ name: 'CreatePage' }) }"
                  class="nav-link">
                  <i class="nav-icon far fa-circle"></i>
                  <p>
                    Create Page

                  </p>
                </router-link>
              </li>
            </ul>
          </li>
          <li class="nav-item" :class="{ 'menu-is-opening menu-open': isActiveMenu(['Posts', 'CreatePost']) }">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-blog"></i>
              <p>
                Post
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link :to="{ name: 'Posts' }" :class="{ active: isRouteActive({ name: 'Posts' }) }"
                  class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Post List</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'CreatePost' }" :class="{ active: isRouteActive({ name: 'CreatePost' }) }"
                  class="nav-link">
                  <i class="nav-icon far fa-circle"></i>
                  <p>
                    Create Post
                  </p>
                </router-link>
              </li>
            </ul>
          </li>
          <li class="nav-item"
            :class="{ 'menu-is-opening menu-open': isActiveMenu(['RolePermission', 'RolePermissionManager']) }">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-cog"></i>
              <p>
                Manage User
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link :to="{ name: 'RolePermission' }"
                  :class="{ active: isRouteActive({ name: 'RolePermission' }) }" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User List</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'RolePermissionManager' }"
                  :class="{ active: isRouteActive({ name: 'RolePermissionManager' }) }" class="nav-link">
                  <i class="nav-icon far fa-circle"></i>
                  <p>
                    Role & Permission

                  </p>
                </router-link>
              </li>
            </ul>
          </li>
          <li class="nav-item" :class="{ 'menu-is-opening menu-open': isActiveMenu(['GeneralSettings']) }">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Settings
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link :to="{ name: 'GeneralSettings' }"
                  :class="{ active: isRouteActive({ name: 'GeneralSettings' }) }" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>General Settings</p>
                </router-link>
              </li>
            </ul>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</template>
<script setup>
import { useAuthStore } from '../../store/auth';
const auth = useAuthStore();
import UserAvatar2 from '@/assets/dist/img/user2-160x160.jpg';
import adminLogo from '@/assets/dist/img/AdminLTELogo.png';
import { useRouter } from 'vue-router';
const router = useRouter();
const isRouteActive = (route) => {
  return router.currentRoute.value.matched.some(matched => matched.name === route.name);
};
const isActiveMenu = (routes = []) => {
  return routes.includes(router.currentRoute.value.name);
};
</script>