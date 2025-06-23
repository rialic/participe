import { defineConfig } from 'vite'
import { fileURLToPath } from 'url'
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify'
import laravel from 'laravel-vite-plugin'
import svgLoader from 'vite-svg-loader'

export default defineConfig({
    server: {
        https: {
          key: './certs/frontend-key.pem',
          cert: './certs/frontend.pem'
        }
    },
    css: {
        preprocessorOptions: {
          scss: {
            api: 'modern-compiler',
          },
        },
    },
    plugins: [
        svgLoader(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        vuetify(
            {
                styles: {
                    configFile: '/resources/scss/style.scss',
                },
            }
        ),
        laravel({
            input: ['resources/js/main.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
            '@images': fileURLToPath(new URL('./resources/images', import.meta.url))
        },
    },
    build: {
        target: ['es2015', 'chrome58', 'firefox57', 'safari11'],
        minify: 'esbuild',
        rollupOptions: {
            output: {
                format: 'es',
                entryFileNames: '[name]-[hash].js',
                chunkFileNames: '[name]-[hash].js',
                assetFileNames: '[name]-[hash].[ext]'
            }
        }
    },
    esbuild: {
        target: 'es2015',
        supported: {
            'top-level-await': false
        }
    }
});
