import { defineConfig } from "vite";

export default defineConfig({
    build: {
        outDir: "build",
        target: "es2018",
        minify: true,
        cssMinify: true,
        lib: {
            entry: "src/admin.ts",
            name: "DemoPressAdmin",
            formats: ["iife"],
            fileName: () => "admin.js",
            cssFileName: "admin",
        },
    },
});
