import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import purgecss from "@fullhuman/postcss-purgecss";
import viteCompression from 'vite-plugin-compression';

export default defineConfig(({ command }) => ({
    plugins: [
        laravel([
            "resources/sass/app.scss",
            "resources/js/app.js",
        ]),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        viteCompression({
            algorithm: 'gzip',
            ext: '.gz',
            threshold: 1024, // Compresses files larger than 1KB
            deleteOriginFile: false // Keeps uncompressed versions for compatibility
        }),
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
        },
    },
    css: {
        postcss: {
            // Only strip unused CSS on production builds, never during `vite dev`.
            plugins: command === "build"
                ? [
                    purgecss({
                        content: [
                            "resources/views/**/*.blade.php",
                            "resources/js/**/*.js",
                            "resources/js/**/*.vue",
                            "app/**/*.php",
                        ],
                        // Keep Bootstrap-friendly tokens: `col-md-6`, `is-invalid`, etc.
                        defaultExtractor: (content) =>
                            content.match(/[\w-/:]+(?<!:)/g) || [],
                        safelist: {
                            standard: [
                                "show",
                                "collapse",
                                "collapsing",
                                "fade",
                                "active",
                                "disabled",
                                "toggled",
                                "modal-open",
                                "modal-backdrop",
                                "offcanvas-backdrop",
                                "is-invalid",
                                "is-valid",
                                "invalid-feedback",
                                "fa-times",
                            ],
                            // Bootstrap components toggle these via JS or state.
                            greedy: [
                                /^modal/,
                                /^offcanvas/,
                                /^dropdown/,
                                /^tooltip/,
                                /^bs-tooltip/,
                                /^popover/,
                                /^bs-popover/,
                                /^carousel/,
                                /^toast/,
                                /^alert/,
                                /^nav/,
                                /^pagination/,
                                /^page-/,
                                /^collaps/,
                            ],
                            // Font Awesome / dynamic icon classes.
                            deep: [/^fa-/, /^fas$/, /^far$/, /^fab$/],
                        },
                    }),
                ]
                : [],
        },
    },
}));
