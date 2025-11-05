import { createRouter, createWebHistory } from "vue-router";
import Home from "../pages/Home.vue";
import AdminDashboard from "../pages/admin/auth/Profile.vue";
import AdminLogin from "../pages/admin/auth/Login.vue";
import Dashboard from "../pages/admin/Dashboard.vue";
import AdminLayout from "@/layouts/AdminLayout.vue";
import RolePermission from "../pages/admin/RolePermission.vue";
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
                        meta: {
                            permission: "manage users",
                        },
                    },

                    {
                        path: "adminProfile",
                        name: "adminProfile",
                        component: AdminDashboard,
                    },
                    {
                        path: "manage-admins-roles",
                        name: "RolePermission",
                        component: RolePermission,
                    },
                    {
                        path: "manage-role-permissions",
                        name: "RolePermissionManager",
                        component: RolePermissionManager,
                    },
                    {
                        path: "manage-pages",
                        name: "Pages",
                        component: Pages,
                    },
                    {
                        path: "edit-pages/:id",
                        name: "UpdatePages",
                        component: UpdatePages,
                        props: true,
                    },
                    {
                        path: "edit-pages",
                        name: "CreatePage",
                        component: CreatePage,
                    },
                    {
                        path: "manage-posts",
                        name: "Posts",
                        component: Post,
                    },
                    {
                        path: '/posts/:slug',
                        name: 'ShowPost',
                        component: ShowPost,
                        props: true
                      },
                    {
                        path: "edit-posts/:slug",
                        name: "UpdatePost",
                        component: UpdatePost,
                        props: true,
                    },
                    {
                        path: "create-posts",
                        name: "CreatePost",
                        component: CreatePost,
                    },
                    {
                        path: "generale-settings",
                        name: "GeneralSettings",
                        component: GeneralSettings,
                    },
                ],
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
    const auth = useAuthStore();

    const token = localStorage.getItem("token");
    const role = localStorage.getItem("role");
    auth.token = token;
    if (to.meta.requiresAuth && !token) {
        next(`/${to.meta.role}/login`);
    } else if (to.meta.role && role !== to.meta.role) {
        next(`/${to.meta.role}/login`);
    } else {
        const requiredPermission = to.meta.permission;
        if (requiredPermission) {
            await auth.fetchRoleAndPermissions();
        }
        console.log("Required Permission:", requiredPermission);
        if (requiredPermission && !auth.hasPermission(requiredPermission)) {
            return next({ name: "Unauthorized" });
        }
        next();
    }
});

export default router;
