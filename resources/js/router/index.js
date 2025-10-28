import { createRouter, createWebHistory } from "vue-router";
import Home from "../pages/Home.vue";
import AdminDashboard from "../pages/admin/auth/Profile.vue";
import AdminLogin from "../pages/admin/auth/Login.vue";
import AdminRegister from "../pages/admin/auth/Register.vue";
import UserProfile from "../pages/user/auth/Profile.vue";
import UserLogin from "../pages/user/auth/Login.vue";
import UserRegister from "../pages/user/auth/Register.vue";
import Dashboard from "../pages/admin/Dashboard.vue";
import AdminLayout from "@/layouts/AdminLayout.vue";
import Dashboardv1 from "../pages/admin/Dashboardv1.vue";
import Dashboardv3 from "../pages/admin/Dashboardv3.vue";
import RolePermission from "../pages/admin/RolePermission.vue";
import TopNavigation from "../pages/admin/TopNavigation.vue";
import CollapsedSidebar from "../pages/admin/CollapsedSidebar.vue";
import Calendar from "../pages/admin/Calendar.vue";
import Gallery from "../pages/admin/Gallery.vue";
import Invoice from "../pages/admin/Invoice.vue";
import LoginV1 from "../pages/admin/Login-v1.vue";
import { useAuthStore } from "../store/auth";
import NotFound from "../pages/NotFound.vue";
import Unauthorized from "../pages/Unauthorized.vue";
import RolePermissionManager from "../pages/admin/RolePermissionManager.vue";
const routes = [
    { path: "/", name: "home", component: Home },

    {
        path: "/user",
        children: [
            { path: "login", name: "UserLogin", component: UserLogin },
            { path: "register", name: "UserRegister", component: UserRegister },
            {
                path: "profile",
                name: "UserProfile",
                component: UserProfile,
                meta: { requiresAuth: true, role: "user" },
            },
        ],
    },
    {
        path: "/admin",
        children: [
            { path: "login", name: "AdminLogin", component: AdminLogin },
            //   { path: 'register', name: 'AdminRegister', component: AdminRegister },
            {
                path: "dashboard",
                component: AdminLayout, // ← the layout
                meta: { requiresAuth: true, role: "admin" },
                children: [
                    {
                        path: "", // / → Dashboard
                        name: "Dashboard",
                        component: Dashboard,
                        meta: {
                            permission: "manage users",
                        },
                    },
                    {
                        path: "v1", // / → Dashboard
                        name: "DashboardV1",
                        component: Dashboardv1,
                        meta: {
                            permission: "view-dashboard1",
                        },
                    },
                    {
                        path: "v1", // / → Dashboard
                        name: "DashboardV3",
                        component: Dashboardv3,
                        meta: {
                            permission: "view-dashboard3",
                        },
                    },

                    {
                        path: "adminProfile",
                        name: "adminProfile",
                        component: AdminDashboard,
                    },
                    {
                        path: "manage-admins-roles", // / → Dashboard
                        name: "RolePermission",
                        component: RolePermission,
                    },
                    {
                        path: "manage-role-permissions", // / → Dashboard
                        name: "RolePermissionManager",
                        component: RolePermissionManager,
                    },
                    {
                        path: "top-navigation", // / → Dashboard
                        name: "TopNavigation",
                        component: TopNavigation,
                    },
                    {
                        path: "collapsed-sidebar", // / → Dashboard
                        name: "CollapsedSidebar",
                        component: CollapsedSidebar,
                    },
                    {
                        path: "calendar", // / → Dashboard
                        name: "Calendar",
                        component: Calendar,
                    },
                    {
                        path: "gallery", // / → Dashboard
                        name: "Gallery",
                        component: Gallery,
                    },
                    {
                        path: "invoice", // / → Dashboard
                        name: "Invoice",
                        component: Invoice,
                    },
                    {
                        path: "login-v1", // / → Dashboard
                        name: "LoginV1",
                        component: LoginV1,
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
        path: "/:pathMatch(.*)*", // Catch-all route for 404
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
    
    const requiredPermission = to.meta.permission;
    if(requiredPermission)
    {
        await auth.fetchRoleAndPermissions();
    }
    console.log("Required Permission:", requiredPermission);
    if (requiredPermission && !auth.hasPermission(requiredPermission)) {
        return next({ name: "Unauthorized" });
    }

    const token = localStorage.getItem("token");
    const role = localStorage.getItem("role");
    auth.token = token;
    if (to.meta.requiresAuth && !token) {
        next(`/${to.meta.role}/login`);
    } else if (to.meta.role && role !== to.meta.role) {
        next(`/${to.meta.role}/login`);
    } else {
        next();
    }
});

export default router;
