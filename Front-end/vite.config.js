import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    tailwindcss(),
    vue(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
  build: {
    chunkSizeWarningLimit: 600,
    rollupOptions: {
      output: {
        manualChunks(id) {
          // 1. Pisahkan pemindai QR html5-qrcode ke chunk tersendiri (hanya diunduh di rute satpam)
          if (id.includes('html5-qrcode')) {
            return 'vendor-scanner'
          }
          // 2. Pisahkan library generator QR code
          if (id.includes('qrcode.vue')) {
            return 'vendor-qrcode'
          }
          // 3. Pisahkan icon library lucide
          if (id.includes('lucide-vue-next')) {
            return 'vendor-icons'
          }
          // 4. Pisahkan vendor inti Vue & Pinia & Vue Router & Axios
          if (
            id.includes('node_modules/vue') ||
            id.includes('node_modules/vue-router') ||
            id.includes('node_modules/pinia') ||
            id.includes('node_modules/axios')
          ) {
            return 'vendor-core'
          }
        },
      },
    },
  },
})
