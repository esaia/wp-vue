import vue from "@vitejs/plugin-vue";
import autoprefixer from "autoprefixer";
import laravel from "laravel-vite-plugin";
import path from "path";
import { defineConfig } from "vite";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/app.ts"],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        tailwindcss(),
    ],

    server: {
        //     // host: '0.0.0.0',

        port: 4040,
        // allowedHosts: true,
        //     host: true,
        //     cors: true,

        //     strictPort: true,
        //     hmr: {
        //         host: "192.168.1.196", // your local IP address
        //     },
    },

    resolve: {
        alias: {
            "@": path.resolve(__dirname, "./resources/js"),
        },
    },
    css: {
        postcss: {
            plugins: [tailwindcss, autoprefixer],
        },
    },
});
