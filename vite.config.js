import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/js/app.js",
                "resources/css/app.css",
                "resources/js/assets/plugins/fontawesome-free/css/all.min.css",
                "resources/js/assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css",
                "resources/js/assets/dist/css/adminlte.min.css",
                // "resources/js/assets/dist/js/demo.js",
                // "resources/js/assets/dist/js/assets/dist/js/pages/dashboard2.js",
            ],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            "@": "/resources/js", // Adjust if your images are elsewhere
        },
    },
});
