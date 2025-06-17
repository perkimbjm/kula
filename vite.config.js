import { defineConfig } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/filament/app/theme.css",
            ],
            refresh: [
                ...refreshPaths,
                "app/Filament/**",
                "app/Forms/Components/**",
                "app/Livewire/**",
                "app/Infolists/Components/**",
                "app/Providers/Filament/**",
                "app/Tables/Columns/**",
            ],
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ["alpinejs"],
                    filament: [
                        "@filament/forms",
                        "@filament/tables",
                        "@filament/notifications",
                    ],
                },
            },
        },
        chunkSizeWarningLimit: 1000,
        sourcemap: false, // Disable sourcemaps in production for smaller files
        minify: "terser",
        terserOptions: {
            compress: {
                drop_console: true, // Remove console.log in production
                drop_debugger: true,
            },
        },
    },
    server: {
        hmr: {
            host: "localhost",
        },
    },
    // Optimize dependencies pre-bundling
    optimizeDeps: {
        include: ["alpinejs", "@alpinejs/focus"],
        exclude: ["@tailwindcss/forms"],
    },
});
