import {defineConfig} from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import path from "path";
import Components from "unplugin-vue-components/vite";
import {PrimeVueResolver} from "unplugin-vue-components/resolvers";

export default defineConfig({
    server: {
        host: "0.0.0.0",
        hmr: {
            clientPort: 5173,
            host: "localhost",
        },
    },
    plugins: [
        vue(),
        Components({
            resolvers: [PrimeVueResolver()],
        }),
        laravel({
            input: ["resources/sass/app.scss", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/js/src"),
        },
    },
});
