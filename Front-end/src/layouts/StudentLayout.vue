<template>
  <div class="min-h-screen bg-canvas pb-20">
    <header class="bg-primary text-white p-4 sticky top-0 z-10 shadow-sm">
      <div class="flex justify-between items-center max-w-md mx-auto">
        <h1 class="font-semibold text-lg">Portal Siswa</h1>
        <button @click="authStore.logout()" class="text-sm bg-white/20 px-3 py-1 rounded-full">Keluar</button>
      </div>
    </header>
    
    <main class="max-w-md mx-auto p-4">
      <router-view />
    </main>

    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-border px-4 py-2 flex justify-between items-center max-w-md mx-auto z-20 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
      <router-link to="/student/dashboard" class="flex flex-col items-center p-2 text-gray-500 hover:text-primary" active-class="text-primary">
        <span class="text-xs font-medium mt-1">Beranda</span>
      </router-link>
      <router-link to="/student/permit/create" class="flex flex-col items-center p-2 text-gray-500 hover:text-primary" active-class="text-primary">
        <span class="text-xs font-medium mt-1">Izin</span>
      </router-link>
      <router-link to="/student/report/create" class="flex flex-col items-center p-2 text-gray-500 hover:text-primary" active-class="text-primary">
        <span class="text-xs font-medium mt-1">Lapor</span>
      </router-link>
    </nav>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'
const authStore = useAuthStore()
</script>
