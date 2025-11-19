<template>
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <router-link :to="{ name: 'Dashboard' }" class="brand-link">
      <img :src="adminLogo" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </router-link>

    <div class="sidebar">
      <!-- User Panel -->
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
        <div class="sidebar-search-results">
          <div class="list-group"><a href="#" class="list-group-item">
              <div class="search-title"><strong class="text-light"></strong>N<strong
                  class="text-light"></strong>o<strong class="text-light"></strong> <strong
                  class="text-light"></strong>e<strong class="text-light"></strong>l<strong
                  class="text-light"></strong>e<strong class="text-light"></strong>m<strong
                  class="text-light"></strong>e<strong class="text-light"></strong>n<strong
                  class="text-light"></strong>t<strong class="text-light"></strong> <strong
                  class="text-light"></strong>f<strong class="text-light"></strong>o<strong
                  class="text-light"></strong>u<strong class="text-light"></strong>n<strong
                  class="text-light"></strong>d<strong class="text-light"></strong>!<strong class="text-light"></strong>
              </div>
              <div class="search-path"></div>
            </a></div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" role="menu">

          <!-- Dashboard -->
          <li class="nav-item" v-if="authStore.hasPermission('view-dashboard')">
            <router-link :to="{ name: 'Dashboard' }" class="nav-link" :class="{ active: $route.name === 'Dashboard' }">
              <i class="fas fa-tachometer-alt nav-icon"></i>
              <p>Dashboard</p>
            </router-link>
          </li>

          <!-- Pages -->
          <li class="nav-item" v-if="authStore.hasPermission('view-pages')" :class="{ 'menu-open': isOpen('pages') }">
            <a href="#" class="nav-link" @click.prevent="toggle('pages')">
              <i class="nav-icon fas fa-book"></i>
              <p>Pages <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link :to="{ name: 'Pages' }" class="nav-link" :class="{ active: $route.name === 'Pages' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Page List</p>
                </router-link>
              </li>
              <li class="nav-item" v-if="authStore.hasPermission('create-pages')">
                <router-link :to="{ name: 'CreatePage' }" class="nav-link"
                  :class="{ active: $route.name === 'CreatePage' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Page</p>
                </router-link>
              </li>
            </ul>
          </li>

          <!-- Posts (with nested Categories) -->
          <li class="nav-item" v-if="authStore.hasPermission('view-posts')" :class="{ 'menu-open': isOpen('posts') }">
            <a href="#" class="nav-link" @click.prevent="toggle('posts')">
              <i class="nav-icon fas fa-blog"></i>
              <p>Posts <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" v-show="isOpen('posts')">
              <!-- Post List -->
              <li class="nav-item">
                <router-link :to="{ name: 'Posts' }" class="nav-link" :class="{ active: $route.name === 'Posts' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Post List</p>
                </router-link>
              </li>

              <!-- Create Post -->
              <li class="nav-item" v-if="authStore.hasPermission('create-posts')">
                <router-link :to="{ name: 'CreatePost' }" class="nav-link"
                  :class="{ active: $route.name === 'CreatePost' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Post</p>
                </router-link>
              </li>

              <!-- Nested: Manage Category (inside Posts) -->
              <li class="nav-item" :class="{ 'menu-open': isOpen('categories') }"
                v-if="authStore.hasPermission('view-categories')">
                <a href="#" class="nav-link" @click.prevent="toggle('categories')">
                  <i class="nav-icon far fa-circle"></i>
                  <p>Manage Category <i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview" v-show="isOpen('categories')">
                  <li class="nav-item">
                    <router-link :to="{ name: 'CategoryIndex' }" class="nav-link"
                      :class="{ active: $route.name === 'CategoryIndex' }">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Category List</p>
                    </router-link>
                  </li>
                  <li class="nav-item" v-if="authStore.hasPermission('create-categories')">
                    <router-link :to="{ name: 'CategoryCreate' }" class="nav-link"
                      :class="{ active: $route.name === 'CategoryCreate' }">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Create Category</p>
                    </router-link>
                  </li>
                </ul>
              </li>
            </ul>
          </li>

          <!-- Manage News Category -->
          <li class="nav-item" v-if="authStore.hasPermission('view-news-categories')"
            :class="{ 'menu-open': isOpen('newsCategories') }">
            <a href="#" class="nav-link" @click.prevent="toggle('newsCategories')">
              <i class="nav-icon fas fa-newspaper"></i>
              <p>Manage News Category <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" v-show="isOpen('newsCategories')">

              <!-- News Category List -->
              <li class="nav-item">
                <router-link :to="{ name: 'NewsCategoryIndex' }" class="nav-link"
                  :class="{ active: $route.name === 'NewsCategoryIndex' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>News Category List</p>
                </router-link>
              </li>

              <!-- Create News Category -->
              <li class="nav-item" v-if="authStore.hasPermission('create-news-categories')">
                <router-link :to="{ name: 'NewsCategoryCreate' }" class="nav-link"
                  :class="{ active: $route.name === 'NewsCategoryCreate' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create News Category</p>
                </router-link>
              </li>

            </ul>
          </li>

          <!-- Manage Users -->
          <li class="nav-item" v-if="authStore.hasPermission('view-users')" :class="{ 'menu-open': isOpen('users') }">
            <a href="#" class="nav-link" @click.prevent="toggle('users')">
              <i class="nav-icon fas fa-user-cog"></i>
              <p>Manage Users <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" v-show="isOpen('users')">
              <li class="nav-item">
                <router-link :to="{ name: 'RolePermission' }" class="nav-link"
                  :class="{ active: $route.name === 'RolePermission' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User List</p>
                </router-link>
              </li>
              <li class="nav-item" v-if="authStore.hasPermission('update-user-role')">
                <router-link :to="{ name: 'RolePermissionManager' }" class="nav-link"
                  :class="{ active: $route.name === 'RolePermissionManager' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Role & Permission</p>
                </router-link>
              </li>
            </ul>
          </li>

          <!-- Settings -->
          <li class="nav-item" v-if="authStore.hasPermission('view-settings')"
            :class="{ 'menu-open': isOpen('settings') }">
            <a href="#" class="nav-link" @click.prevent="toggle('settings')">
              <i class="nav-icon fas fa-cog"></i>
              <p>Settings <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" v-show="isOpen('settings')">
              <li class="nav-item" v-if="authStore.hasPermission('update-settings')">
                <router-link :to="{ name: 'GeneralSettings' }" class="nav-link"
                  :class="{ active: $route.name === 'GeneralSettings' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>General Settings</p>
                </router-link>
              </li>
              <li class="nav-item" v-if="authStore.hasPermission('update-settings')">
                <router-link :to="{ name: 'MenuManager' }" class="nav-link"
                  :class="{ active: $route.name === 'MenuManager' }">
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
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import UserAvatar2 from '@/assets/dist/img/user2-160x160.jpg'
import adminLogo from '@/assets/dist/img/AdminLTELogo.png'

const authStore = useAuthStore()
const router = useRouter()
const route = useRoute()

// Track multiple open menus
const openMenus = ref({})

// Toggle menu (supports multiple open)
const toggle = (menu) => {
  openMenus.value[menu] = !openMenus.value[menu]
}

const isOpen = (menu) => {
  return !!openMenus.value[menu]
}

// Auto-open parent menus based on current route
const openParentMenus = () => {
  const map = {
    'Pages': ['pages'],
    'CreatePage': ['pages'],
    'Posts': ['posts'],
    'CreatePost': ['posts'],
    'CategoryIndex': ['posts', 'categories'],
    'CategoryCreate': ['posts', 'categories'],
    'RolePermission': ['users'],
    'RolePermissionManager': ['users'],
    'GeneralSettings': ['settings'],
    'MenuManager': ['settings'],
  }

  const current = route.name
  if (map[current]) {
    map[current].forEach(menu => {
      openMenus.value[menu] = true
    })
  }
}

// Run on mount and route change
onMounted(openParentMenus)
watch(() => route.name, openParentMenus)

const logout = async () => {
  await axios.post('/api/admin/logout')
  localStorage.clear()
  delete axios.defaults.headers.common['Authorization']
  router.push({ name: 'AdminLogin' })
}
</script>