import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import fg from "fast-glob";

export default defineConfig({
    plugins: [
        laravel({
            // input: [
            //     "resources/css/app.css",
            //     "resources/css/style.css",
            //     "resources/js/app.js",
            //     "resources/js/auth/login_js.js",
            //     "resources/js/auth/register_js.js",
            //     "resources/js/products/product_feature_js.js",
            //     "resources/js/partials/_nav_js.js",
            //     "resources/js/utils.js",
            //     "resources/js/layouts/base_js.js",
            // ],

            input: [
                "resources/css/app.css",
                "resources/css/style.css",
                ...fg.sync(["resources/js/**/*.js"], { absolute: false }),
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
