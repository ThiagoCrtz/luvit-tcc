import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/identity.css",
                "resources/js/app.js",
                "resources/js/pages/auth/register.js",
                "resources/js/pages/auth/login.js",
                "resources/js/pages/index.js",
                "resources/js/pages/filmes/show.js",
                "resources/css/pages/games/show.css",
                "resources/js/pages/games/show.js",
                "resources/css/pages/filmes/show.css",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
