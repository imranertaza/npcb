<template>
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <router-link :to="{ name: 'Dashboard' }" class="brand-link">
      <img :src="adminLogo" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </router-link>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel -->
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
        <div class="input-group">
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
        <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
          
          <!-- Dashboard -->
          <li class="nav-item" v-if="authStore.hasPermission('view-dashboard')">
            <router-link :to="{ name: 'Dashboard' }" class="nav-link"
              :class="{ active: isRouteActive({ name: 'Dashboard' }) }">
              <i class="fas fa-tachometer-alt nav-icon"></i>
              <p>Dashboard</p>
            </router-link>
          </li>

          <!-- Pages -->
          <li class="nav-item" v-if="authStore.hasPermission('view-pages')" :class="{ 'menu-open': openMenu === 'pages' }">
            <a href="#" class="nav-link" @click.prevent="toggleMenu('pages')">
              <i class="nav-icon fas fa-book"></i>
              <p>Page <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul v-show="openMenu === 'pages'" class="nav nav-treeview">
              <li class="nav-item">
                <router-link :to="{ name: 'Pages' }" class="nav-link" :class="{ active: isRouteActive({ name: 'Pages' }) }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Page List</p>
                </router-link>
              </li>
              <li class="nav-item" v-if="authStore.hasPermission('create-pages')">
                <router-link :to="{ name: 'CreatePage' }" class="nav-link" :class="{ active: isRouteActive({ name: 'CreatePage' }) }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Page</p>
                </router-link>
              </li>
            </ul>
          </li>

          <!-- Posts -->
          <li class="nav-item" v-if="authStore.hasPermission('view-posts')" :class="{ 'menu-open': openMenu === 'posts' }">
            <a href="#" class="nav-link" @click.prevent="toggleMenu('posts')">
              <i class="nav-icon fas fa-blog"></i>
              <p>Post <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul v-show="openMenu === 'posts'" class="nav nav-treeview">
              <li class="nav-item">
                <router-link :to="{ name: 'Posts' }" class="nav-link" :class="{ active: isRouteActive({ name: 'Posts' }) }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Post List</p>
                </router-link>
              </li>
              <li class="nav-item" v-if="authStore.hasPermission('create-posts')">
                <router-link :to="{ name: 'CreatePost' }" class="nav-link" :class="{ active: isRouteActive({ name: 'CreatePost' }) }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Post</p>
                </router-link>
              </li>
            </ul>
          </li>

          <!-- Manage Users -->
          <li class="nav-item" v-if="authStore.hasPermission('view-users')" :class="{ 'menu-open': openMenu === 'users' }">
            <a href="#" class="nav-link" @click.prevent="toggleMenu('users')">
              <i class="nav-icon fas fa-user-cog"></i>
              <p>Manage User <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul v-show="openMenu === 'users'" class="nav nav-treeview">
              <li class="nav-item">
                <router-link :to="{ name: 'RolePermission' }" class="nav-link" :class="{ active: isRouteActive({ name: 'RolePermission' }) }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User List</p>
                </router-link>
              </li>
              <li class="nav-item" v-if="authStore.hasPermission('update-user-role')">
                <router-link :to="{ name: 'RolePermissionManager' }" class="nav-link" :class="{ active: isRouteActive({ name: 'RolePermissionManager' }) }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Role & Permission</p>
                </router-link>
              </li>
            </ul>
          </li>

          <!-- Manage Category -->
          <li class="nav-item" v-if="authStore.hasPermission('view-categories')" :class="{ 'menu-open': openMenu === 'categories' }">
            <a href="#" class="nav-link" @click.prevent="toggleMenu('categories')">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>Manage Category <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul v-show="openMenu === 'categories'" class="nav nav-treeview">
              <li class="nav-item">
                <router-link :to="{ name: 'CategoryIndex' }" class="nav-link" :class="{ active: isRouteActive({ name: 'CategoryIndex' }) }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Category List</p>
                </router-link>
              </li>
              <li class="nav-item" v-if="authStore.hasPermission('create-categories')">
                <router-link :to="{ name: 'CategoryCreate' }" class="nav-link" :class="{ active: isRouteActive({ name: 'CategoryCreate' }) }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Category</p>
                </router-link>
              </li>
            </ul>
          </li>

          <!-- Settings -->
          <li class="nav-item" v-if="authStore.hasPermission('view-settings')" :class="{ 'menu-open': openMenu === 'settings' }">
            <a href="#" class="nav-link" @click.prevent="toggleMenu('settings')">
              <i class="nav-icon fas fa-cog"></i>
              <p>Settings <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul v-show="openMenu === 'settings'" class="nav nav-treeview">
              <li class="nav-item" v-if="authStore.hasPermission('update-settings')">
                <router-link :to="{ name: 'GeneralSettings' }" class="nav-link" :class="{ active: isRouteActive({ name: 'GeneralSettings' }) }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>General Settings</p>
                </router-link>
              </li>
              <li class="nav-item" v-if="authStore.hasPermission('update-settings')">
                <router-link :to="{ name: 'MenuManager' }" class="nav-link" :class="{ active: isRouteActive({ name: 'MenuManager' }) }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Menu Settings</p>
                </router-link>
              </li>
            </ul>
          </li>
        </ul>
      </nav>

      <button @click="logout" class="btn btn-block btn-danger text-left mt-3">
        <i class="fas fa-sign-out-alt nav-icon"></i>
        Logout
      </button>
    </div>
  </aside>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { useAuthStore } from '../../store/auth'
import { useRouter } from 'vue-router'
import axios from 'axios'
import UserAvatar2 from '@/assets/dist/img/user2-160x160.jpg'
import adminLogo from '@/assets/dist/img/AdminLTELogo.png'

const authStore = useAuthStore()
const router = useRouter()

// Track which menu is open
const openMenu = ref(null)

const toggleMenu = (menu) => {
  openMenu.value = openMenu.value === menu ? null : menu
}

const isRouteActive = (route) => {
  return router.currentRoute.value.matched.some(matched => matched.name === route.name)
}

// ✅ Map routes to their parent menu
const routeMenuMap = {
  Pages: 'pages',
  CreatePage: 'pages',
  Posts: 'posts',
  CreatePost: 'posts',
  RolePermission: 'users',
  RolePermissionManager: 'users',
  CategoryIndex: 'categories',
  CategoryCreate: 'categories',
  UpdateCategory: 'categories',
  CategoryShow: 'categories',
  GeneralSettings: 'settings'
}

// ✅ Auto‑open correct menu when route changes
const setActiveMenu = () => {
  const current = router.currentRoute.value.name
  if (current && routeMenuMap[current]) {
    openMenu.value = routeMenuMap[current]
  }
}

onMounted(() => {
  setActiveMenu()
})

// Watch route changes
watch(() => router.currentRoute.value.name, () => {
  setActiveMenu()
})

const logout = async () => {
  await axios.post('/api/admin/logout')
  localStorage.removeItem('token')
  localStorage.removeItem('role')
  delete axios.defaults.headers.common['Authorization']
  router.push({ name: 'AdminLogin' })
}

</script>
