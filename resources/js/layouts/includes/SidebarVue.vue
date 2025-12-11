<template>
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <router-link :to="{ name: 'Dashboard' }" class="brand-link">
      <img :src="adminLogo" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">NPCB</span>
    </router-link>

    <div class="sidebar">
      <!-- User Panel -->
      <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img :src="UserAvatar2" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div> -->
      <!-- SidebarSearch Form -->
      <!-- <div class="form-inline">
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
      </div> -->

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
                  <i class="nav-icon fas fa-folder"></i>
                  <p>Post Category <i class="fas fa-angle-left right"></i></p>
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

          <!-- News -->
          <li class="nav-item" v-if="authStore.hasPermission('view-news')" :class="{ 'menu-open': isOpen('news') }">
            <a href="#" class="nav-link" @click.prevent="toggle('news')">
              <i class="nav-icon fas fa-newspaper"></i>
              <p>News <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" v-show="isOpen('news')">
              <!-- News List -->
              <li class="nav-item">
                <router-link :to="{ name: 'News' }" class="nav-link" :class="{ active: $route.name === 'News' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>News List</p>
                </router-link>
              </li>

              <!-- Create News -->
              <li class="nav-item" v-if="authStore.hasPermission('create-news')">
                <router-link :to="{ name: 'CreateNews' }" class="nav-link"
                  :class="{ active: $route.name === 'CreateNews' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create News</p>
                </router-link>
              </li>

              <!-- Manage News Category (nested inside News) -->
              <li class="nav-item" v-if="authStore.hasPermission('view-news-categories')"
                :class="{ 'menu-open': isOpen('newsCategories') }">
                <a href="#" class="nav-link" @click.prevent="toggle('newsCategories')">
                  <i class="nav-icon fas fa-folder"></i>
                  <p>News Category <i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview" v-show="isOpen('newsCategories')">
                  <!-- News Category List -->
                  <li class="nav-item">
                    <router-link :to="{ name: 'NewsCategoryIndex' }" class="nav-link"
                      :class="{ active: $route.name === 'NewsCategoryIndex' }">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Category List</p>
                    </router-link>
                  </li>
                  <!-- Create News Category -->
                  <li class="nav-item" v-if="authStore.hasPermission('create-news-categories')">
                    <router-link :to="{ name: 'NewsCategoryCreate' }" class="nav-link"
                      :class="{ active: $route.name === 'NewsCategoryCreate' }">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Create Category</p>
                    </router-link>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          <!-- Blog -->
          <li class="nav-item" v-if="authStore.hasPermission('view-blog')" :class="{ 'menu-open': isOpen('blog') }">
            <a href="#" class="nav-link" @click.prevent="toggle('blog')">
              <i class="nav-icon fas fa-newspaper"></i>
              <p>Blog <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" v-show="isOpen('blog')">
              <!-- Blog List -->
              <li class="nav-item">
                <router-link :to="{ name: 'Blog' }" class="nav-link" :class="{ active: $route.name === 'Blog' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Blog List</p>
                </router-link>
              </li>

              <!-- Create Blog -->
              <li class="nav-item" v-if="authStore.hasPermission('create-blog')">
                <router-link :to="{ name: 'CreateBlog' }" class="nav-link"
                  :class="{ active: $route.name === 'CreateBlog' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Blog</p>
                </router-link>
              </li>

              <!-- Manage Blog Category (nested inside Blog) -->
              <li class="nav-item" v-if="authStore.hasPermission('view-blog-categories')"
                :class="{ 'menu-open': isOpen('blogCategories') }">
                <a href="#" class="nav-link" @click.prevent="toggle('blogCategories')">
                  <i class="nav-icon fas fa-folder"></i>
                  <p>Blog Category <i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview" v-show="isOpen('blogCategories')">
                  <!-- Blog Category List -->
                  <li class="nav-item">
                    <router-link :to="{ name: 'BlogCategoryIndex' }" class="nav-link"
                      :class="{ active: $route.name === 'BlogCategoryIndex' }">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Category List</p>
                    </router-link>
                  </li>
                  <!-- Create Blog Category -->
                  <li class="nav-item" v-if="authStore.hasPermission('create-blog-categories')">
                    <router-link :to="{ name: 'BlogCategoryCreate' }" class="nav-link"
                      :class="{ active: $route.name === 'BlogCategoryCreate' }">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Create Category</p>
                    </router-link>
                  </li>
                </ul>
              </li>
            </ul>
          </li>

          <!-- Galleries -->
          <li class="nav-item" v-if="authStore.hasPermission('view-galleries')"
            :class="{ 'menu-open': isOpen('galleries') }">
            <a href="#" class="nav-link" @click.prevent="toggle('galleries')">
              <i class="nav-icon fas fa-images"></i>
              <p>Galleries <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" v-show="isOpen('galleries')">
              <li class="nav-item">
                <router-link :to="{ name: 'Gallery' }" class="nav-link" :class="{ active: $route.name === 'Gallery' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Gallery List</p>
                </router-link>
              </li>
              <li class="nav-item" v-if="authStore.hasPermission('create-galleries')">
                <router-link :to="{ name: 'CreateGallery' }" class="nav-link"
                  :class="{ active: $route.name === 'CreateGallery' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Gallery</p>
                </router-link>
              </li>
            </ul>
          </li>
          <!-- Events (with nested Categories) -->
          <li class="nav-item" v-if="authStore.hasPermission('view-events')" :class="{ 'menu-open': isOpen('events') }">
            <a href="#" class="nav-link" @click.prevent="toggle('events')">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Events <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" v-show="isOpen('events')">
              <!-- Event List -->
              <li class="nav-item">
                <router-link :to="{ name: 'Events' }" class="nav-link" :class="{ active: $route.name === 'Events' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Event List</p>
                </router-link>
              </li>

              <!-- Create Event -->
              <li class="nav-item" v-if="authStore.hasPermission('create-events')">
                <router-link :to="{ name: 'CreateEvent' }" class="nav-link"
                  :class="{ active: $route.name === 'CreateEvent' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Event</p>
                </router-link>
              </li>

              <!-- Nested: Manage Event Category -->
              <li class="nav-item" :class="{ 'menu-open': isOpen('event-categories') }"
                v-if="authStore.hasPermission('view-events-categories')">
                <a href="#" class="nav-link" @click.prevent="toggle('event-categories')">
                  <i class="nav-icon far fa-circle"></i>
                  <p>Event Category <i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview" v-show="isOpen('event-categories')">
                  <li class="nav-item">
                    <router-link :to="{ name: 'EventCategoryIndex' }" class="nav-link"
                      :class="{ active: $route.name === 'EventCategoryIndex' }">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Category List</p>
                    </router-link>
                  </li>
                  <li class="nav-item" v-if="authStore.hasPermission('create-events-categories')">
                    <router-link :to="{ name: 'EventCategoryCreate' }" class="nav-link"
                      :class="{ active: $route.name === 'EventCategoryCreate' }">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Create Category</p>
                    </router-link>
                  </li>
                </ul>
              </li>
            </ul>
          </li>

          <!-- Notices -->
          <li class="nav-item" v-if="authStore.hasPermission('view-notices')"
            :class="{ 'menu-open': isOpen('notices') }">
            <a href="#" class="nav-link" @click.prevent="toggle('notices')">
              <i class="nav-icon fas fa-bullhorn"></i>
              <p>Notices <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" v-show="isOpen('notices')">
              <!-- Notice List -->
              <li class="nav-item">
                <router-link :to="{ name: 'Notices' }" class="nav-link" :class="{ active: $route.name === 'Notices' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Notice List</p>
                </router-link>
              </li>

              <!-- Create Notice -->
              <li class="nav-item" v-if="authStore.hasPermission('create-notices')">
                <router-link :to="{ name: 'CreateNotice' }" class="nav-link"
                  :class="{ active: $route.name === 'CreateNotice' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Notice</p>
                </router-link>
              </li>
            </ul>
          </li>
          <!-- Committee Members -->
          <li class="nav-item" v-if="authStore.hasPermission('manage-committee-members')"
            :class="{ 'menu-open': isOpen('committee-members') }">
            <a href="#" class="nav-link" @click.prevent="toggle('committee-members')">
              <i class="nav-icon fas fa-users"></i>
              <p>Committee Members <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" v-show="isOpen('committee-members')">
              <!-- Committee Member List -->
              <li class="nav-item">
                <router-link :to="{ name: 'CommitteeMembers' }" class="nav-link"
                  :class="{ active: $route.name === 'CommitteeMembers' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Member List</p>
                </router-link>
              </li>

              <!-- Create Committee Member -->
              <li class="nav-item">
                <router-link :to="{ name: 'CreateCommitteeMembers' }" class="nav-link"
                  :class="{ active: $route.name === 'CreateCommitteeMembers' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Member</p>
                </router-link>
              </li>
            </ul>
          </li>
          <!-- Results -->
          <li class="nav-item" v-if="authStore.hasPermission('view-results')"
            :class="{ 'menu-open': isOpen('results') }">
            <a href="#" class="nav-link" @click.prevent="toggle('results')">
              <i class="nav-icon fas fa-poll"></i>
              <p>Results <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" v-show="isOpen('results')">
              <!-- Result List -->
              <li class="nav-item">
                <router-link :to="{ name: 'Results' }" class="nav-link" :class="{ active: $route.name === 'Results' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Result List</p>
                </router-link>
              </li>

              <!-- Create Result -->
              <li class="nav-item" v-if="authStore.hasPermission('create-results')">
                <router-link :to="{ name: 'CreateResult' }" class="nav-link"
                  :class="{ active: $route.name === 'CreateResult' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Result</p>
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

          <!-- Frontend -->
          <li class="nav-item" v-if="authStore.hasPermission('manage-frontend')"
            :class="{ 'menu-open': isOpen('frontend') }">
            <a href="#" class="nav-link" @click.prevent="toggle('frontend')">
              <i class="nav-icon fas fa-desktop"></i>
              <p>
                Frontend
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" v-show="isOpen('frontend')">
              <!-- Slider -->
              <li class="nav-item" v-if="authStore.hasPermission('manage-frontend')">
                <router-link :to="{ name: 'Sliders' }" class="nav-link" :class="{ active: $route.name === 'Sliders' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Slider</p>
                </router-link>
              </li>
              <li class="nav-item" v-if="authStore.hasPermission('manage-frontend')">
                <router-link :to="{ name: 'CreateSlider' }" class="nav-link"
                  :class="{ active: $route.name === 'CreateSlider' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Slider</p>
                </router-link>
              </li>

              <!-- Sections -->
              <li class="nav-item" v-if="authStore.hasPermission('manage-frontend')">
                <router-link :to="{ name: 'Section' }" class="nav-link" :class="{ active: $route.name === 'Section' }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sections</p>
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
    // Pages
    'Pages': ['pages'],
    'CreatePage': ['pages'],
    'UpdatePages': ['pages'],
    'ShowPage': ['pages'],

    // Posts
    'Posts': ['posts'],
    'CreatePost': ['posts'],
    'ShowPost': ['posts'],
    'UpdatePost': ['posts'],

    // Post Categories
    'CategoryIndex': ['posts', 'categories'],
    'UpdateCategory': ['posts', 'categories'],
    'CreateCategory': ['posts', 'categories'],
    'CategoryShow': ['posts', 'categories'],

    // News
    'News': ['news'],
    'CreateNews': ['news'],
    'ShowNews': ['news'],
    'UpdateNews': ['news'],

    // News Categories (nested under News)
    'NewsCategoryIndex': ['news', 'newsCategories'],
    'NewsCategoryCreate': ['news', 'newsCategories'],
    'UpdateNewsCategory': ['news', 'newsCategories'],
    'NewsCategoryShow': ['news', 'newsCategories'],

    // Blogs
    'Blog': ['blog'],
    'CreateBlog': ['blog'],
    'ShowBlog': ['blog'],
    'UpdateBlog': ['blog'],

    // Sliders
    'Sliders': ['frontend', 'sliders'],
    'CreateSlider': ['frontend', 'sliders'],
    'ShowSlider': ['frontend', 'sliders'],
    'UpdateSlider': ['frontend', 'sliders'],
    'Section': ['frontend', 'sections'],
    'CreateSection': ['frontend', 'sections'],
    'ShowSection': ['frontend', 'sections'],
    'UpdateSection': ['frontend', 'sections'],



    // Blog Categories (nested under Blogs)
    'BlogCategoryIndex': ['blog', 'blogCategories'],
    'BlogCategoryCreate': ['blog', 'blogCategories'],
    'UpdateBlogCategory': ['blog', 'blogCategories'],
    'BlogCategoryShow': ['blog', 'blogCategories'],

    // Users / Roles
    'RolePermission': ['users'],
    'RolePermissionManager': ['users'],
    'AdminUserUpdate': ['users'],

    // Settings
    'GeneralSettings': ['settings'],
    'MenuManager': ['settings'],
    'ShowMenu': ['settings'],

    // Galleries
    'Gallery': ['galleries'],
    'CreateGallery': ['galleries'],
    'ShowGallery': ['galleries'],

    // Events
    'Events': ['events'],
    'CreateEvent': ['events'],
    'ShowEvent': ['events'],
    'UpdateEvent': ['events'],

    // Event Categories
    'EventCategoryIndex': ['events', 'event-categories'],
    'EventCategoryCreate': ['events', 'event-categories'],
    'UpdateEventCategory': ['events', 'event-categories'],
    'EventCategoryShow': ['events', 'event-categories'],

    // Notices
    'Notices': ['notices'],
    'CreateNotice': ['notices'],
    'ShowNotice': ['notices'],
    'UpdateNotice': ['notices'],

    // Committee Members
    'CommitteeMembers': ['committee-members'],
    'CreateCommitteeMembers': ['committee-members'],
    'ShowCommitteeMembers': ['committee-members'],
    'UpdateCommitteeMembers': ['committee-members'],


    // Results
    'Results': ['results'],
    'CreateResult': ['results'],
    'ShowResult': ['results'],
    'UpdateResult': ['results'],
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