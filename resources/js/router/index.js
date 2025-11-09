import { createRouter, createWebHistory } from "vue-router";
import Home from "../pages/Home.vue";
import AdminDashboard from "../pages/admin/auth/Profile.vue";
import AdminLogin from "../pages/admin/auth/Login.vue";
import Dashboard from "../pages/admin/Dashboard.vue";
import AdminLayout from "@/layouts/AdminLayout.vue";
import RolePermission from "../pages/admin/ManageUser.vue";
import { useAuthStore } from "../store/auth";
import NotFound from "../pages/NotFound.vue";
import Unauthorized from "../pages/Unauthorized.vue";
import RolePermissionManager from "../pages/admin/RolePermissionManager.vue";
import Pages from "../pages/admin/Pages/Pages.vue";
import Post from "../pages/admin/Post/Post.vue";
import UpdatePages from "../pages/admin/Pages/UpdatePages.vue";
import UpdatePost from "../pages/admin/Post/UpdatePost.vue";
import CreatePost from "../pages/admin/Post/CreatePost.vue";
import CreatePage from "../pages/admin/Pages/CreatePage.vue";
import ShowPost from "../pages/admin/Post/ShowPost.vue";
import GeneralSettings from "../pages/admin/Settings/GeneralSettings.vue";
import ShowPage from "../pages/admin/Pages/ShowPage.vue";
import CustomImageDropzone from "../pages/CustomImageDropzone.vue";
import AdminUserUpdate from "../pages/admin/AdminUserUpdate.vue";

const routes = [
    { path: "/", name: "home", component: Home },
    {
        path: "/admin",
        children: [
            { path: "login", name: "AdminLogin", component: AdminLogin },
            {
                path: "dashboard",
                component: AdminLayout,
                meta: { requiresAuth: true, role: "admin" },
                children: [
                    {
                      path: "",
                      name: "Dashboard",
                      component: Dashboard,
                      meta: { permission: "view-dashboard" },
                    },
                    {
                      path: "adminProfile",
                      name: "adminProfile",
                      component: AdminDashboard,
                      meta: { permission: "view-dashboard" },
                    },
                  
                    // Role & Permission Management
                    {
                      path: "manage-admins-roles",
                      name: "RolePermission",
                      component: RolePermission,
                      meta: { permission: "view-users" }, // viewing admins/roles
                    },
                    {
                      path: "admins/:id/edit",
                      name: "AdminUserUpdate",
                      component:AdminUserUpdate,
                      meta: { permission: "update-users" }
                    },                    
                    {
                      path: "manage-role-permissions",
                      name: "RolePermissionManager",
                      component: RolePermissionManager,
                      meta: { permission: "update-user-role" }, // updating role permissions
                    },
                  
                    // Pages CRUD
                    {
                      path: "manage-pages",
                      name: "Pages",
                      component: Pages,
                      meta: { permission: "view-pages" },
                    },
                    {
                      path: "/pages/:slug",
                      name: "ShowPage",
                      component: ShowPage,
                      props: true,
                      meta: { permission: "view-pages" },
                    },
                    {
                      path: "edit-pages/:slug",
                      name: "UpdatePages",
                      component: UpdatePages,
                      props: true,
                      meta: { permission: "edit-pages" },
                    },
                    {
                      path: "create-page",
                      name: "CreatePage",
                      component: CreatePage,
                      meta: { permission: "create-pages" },
                    },
                  
                    // Custom Dropzone Demo
                    {
                      path: "custom",
                      name: "custom",
                      component: CustomImageDropzone,
                      meta: { permission: "update-settings" }, // treat as settings customization
                    },
                  
                    // Posts CRUD
                    {
                      path: "manage-posts",
                      name: "Posts",
                      component: Post,
                      meta: { permission: "view-posts" },
                    },
                    {
                      path: "/posts/:slug",
                      name: "ShowPost",
                      component: ShowPost,
                      props: true,
                      meta: { permission: "view-posts" },
                    },
                    {
                      path: "edit-posts/:slug",
                      name: "UpdatePost",
                      component: UpdatePost,
                      props: true,
                      meta: { permission: "edit-posts" },
                    },
                    {
                      path: "create-posts",
                      name: "CreatePost",
                      component: CreatePost,
                      meta: { permission: "create-posts" },
                    },
                  
                    // Settings
                    {
                      path: "generale-settings",
                      name: "GeneralSettings",
                      component: GeneralSettings,
                      meta: { permission: "update-settings" },
                    },
                  ]
            },
        ],
    },
    {
        path: "/unauthorized",
        name: "Unauthorized",
        component: Unauthorized,
    },
    {
        path: "/:pathMatch(.*)*",
        name: "NotFound",
        component: NotFound,
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  const token = localStorage.getItem("token");
  const role  = localStorage.getItem("role");

  authStore.token = token;
  authStore.role  = role;

  // 1. Require authentication
  if (to.meta.requiresAuth && !token) {
    // redirect to role‑specific login, or fallback
    const loginRoute = to.meta.role ? `/${to.meta.role}/login` : "/login";
    return next(loginRoute);
  }

  // 2. Role mismatch
  if (to.meta.role && role !== to.meta.role) {
    const loginRoute = `/${to.meta.role}/login`;
    return next(loginRoute);
  }

  // 3. Permission check
  const requiredPermission = to.meta.permission;
  if (requiredPermission) {
    try {
      await authStore.fetchRoleAndPermissions();
    } catch (err) {
      // If /api/admin/me fails (Unauthenticated), force login
      const loginRoute = role ? `/${role}/login` : "/login";
      return next(loginRoute);
    }
  }

  if (requiredPermission && !authStore.hasPermission(requiredPermission)) {
    return next({ name: "Unauthorized" });
  }

  // 4. Allow navigation
  next();
});
export default router;
