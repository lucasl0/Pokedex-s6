import { defineConfig } from "vite";
import vituum from "vituum";
import eslint from "vite-plugin-eslint";

export default defineConfig({
    base: "./",
    plugins: [
        vituum({
            pages: {
                dir: "./src",
                root: "./",
                normalizeBasePath: true
            },
        }),
        eslint({
            include: "./src/**/*.js",
            failOnError: false,
        }),
    ],
    build: {
        target: "esnext",
        rollupOptions: {
            input: ["src/index.html"],
        },
    },
    define: {
        "import.meta.env.VERSION": JSON.stringify(
            process.env.npm_package_version
        ),
    },
    server: {
        host: true,
        open: true,
        proxy: {
            "/api": {
                target: "https://tyradex.app",
                changeOrigin: true,
                secure: true,
            },
        },
    },
    test: {
        exclude: [
            "**/node_modules/**",
            "**/dist/**",
            "**/cypress/**",
            "**/worklets/**",
            "**/.{idea,git,cache,output,temp}/**",
            "**/e2e/**",
        ],
        environment: 'happy-dom',
        css: false,
        // Reporter HTML Vitest – génère le rapport dans ./vitest-report/
        // Ne pas commiter ce dossier (ajouté dans .gitignore)
        reporters: ['verbose', 'html'],
        outputFile: {
            html: 'vitest-report/index.html',
        },
    },
});
