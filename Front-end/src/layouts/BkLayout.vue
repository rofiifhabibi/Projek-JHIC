<template>
  <div class="min-h-screen bg-canvas flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-border hidden md:flex flex-col">
      <div class="h-16 flex items-center px-6 border-b border-border">
        <h1 class="text-lg font-bold text-primary">Pusat Data BK</h1>
      </div>
      <nav class="flex-1 p-4 space-y-1">
        <router-link to="/bk/kanban" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900" active-class="bg-blue-50 text-blue-700 hover:bg-blue-50 hover:text-blue-700">Kanban Aduan</router-link>
        <router-link to="/bk/global-monitor" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900" active-class="bg-blue-50 text-blue-700 hover:bg-blue-50 hover:text-blue-700">Monitor Global</router-link>
      </nav>
      <div class="p-4 border-t border-border">
        <button @click="authStore.logout()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 rounded-lg text-sm font-medium transition-colors">Keluar Sistem</button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-x-hidden">
      <!-- Mobile Header -->
      <header class="md:hidden bg-white h-16 border-b border-border flex items-center justify-between px-4">
        <h1 class="font-bold text-primary">Pusat Data BK</h1>
        <button @click="authStore.logout()" class="text-sm bg-gray-100 px-3 py-1.5 rounded">Keluar</button>
      </header>
      
      <div class="p-4 sm:p-6 lg:p-8">
        <router-view />
      </div>
    </main>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'
const authStore = useAuthStore()
</script>
