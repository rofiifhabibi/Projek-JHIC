<template>
  <div class="min-h-screen bg-canvas">
    <header class="bg-primary text-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center gap-4">
            <h1 class="text-xl font-bold">Dasbor Guru</h1>
            <nav class="hidden md:flex gap-4 ml-6">
              <router-link to="/teacher/monitoring" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-white/10" active-class="bg-white/20">Monitoring</router-link>
              <router-link to="/teacher/approvals" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-white/10" active-class="bg-white/20">Antrean Izin</router-link>
            </nav>
          </div>
          <button @click="authStore.logout()" class="text-sm bg-white/20 hover:bg-white/30 px-4 py-2 rounded-md font-medium transition-colors">Keluar</button>
        </div>
      </div>
      <!-- Mobile nav -->
      <div class="md:hidden border-t border-white/10 px-4 py-2 flex gap-4">
        <router-link to="/teacher/monitoring" class="text-sm text-white/80" active-class="text-white font-medium">Monitoring</router-link>
        <router-link to="/teacher/approvals" class="text-sm text-white/80" active-class="text-white font-medium">Antrean</router-link>
      </div>
    </header>
    
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'
const authStore = useAuthStore()
</script>
