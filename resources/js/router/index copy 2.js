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
const routes = [
    { path: "/", name: "home", component: Home },
    {
        path: "/dashboard",
        component: AdminLayout, // ← the layout
        children: [
            {
                path: "", // / → Dashboard
                name: "Dashboard",
                component: Dashboard,
            },
            {
                path: "me", // / → Dashboard
                name: "AdminMe",
                component: AdminDashboard,
            },
        ],
    },
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
                path: "profile",
                name: "adminDashboard",
                component: AdminDashboard,
                meta: { requiresAuth: true, role: "admin" },
            },
        ],
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("token");
    const role = localStorage.getItem("role");

    if (to.meta.requiresAuth && !token) {
        next(`/${to.meta.role}/login`);
    } else if (to.meta.role && role !== to.meta.role) {
        next(`/${to.meta.role}/login`);
    } else {
        next();
    }
});

export default router;
