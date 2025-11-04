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
const routes = [
    { path: "/", name: "home", component: Home },
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

    

    const token = localStorage.getItem("token");
    const role = localStorage.getItem("role");
    auth.token = token;
    if (to.meta.requiresAuth && !token) {
        next(`/${to.meta.role}/login`);
    } else if (to.meta.role && role !== to.meta.role) {
        next(`/${to.meta.role}/login`);
    } else {
        const requiredPermission = to.meta.permission;
    if(requiredPermission)
    {
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
