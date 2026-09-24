<template>
  <div class="space-y-6">
    <div class="bg-white p-5 rounded-2xl border border-border shadow-sm">
      <h2 class="text-gray-500 text-sm mb-1">Selamat datang,</h2>
      <p class="text-xl font-bold text-gray-800">{{ authStore.user?.name || 'Siswa' }}</p>
    </div>

    <div class="bg-surface-soft p-5 rounded-2xl border border-secondary">
      <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold text-primary">Status Saat Ini</h3>
        <span class="bg-status-active text-white text-xs px-2 py-1 rounded-full font-medium">DI KELAS</span>
      </div>
      <p class="text-sm text-gray-600">Anda tidak memiliki izin aktif saat ini. Tetap semangat belajar!</p>
    </div>
    
    <div class="grid grid-cols-2 gap-4">
      <button @click="$router.push('/student/permit/create')" class="bg-white p-4 rounded-xl border border-border flex flex-col items-center justify-center gap-2 shadow-sm hover:border-primary transition-colors">
        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold">I</div>
        <span class="text-sm font-medium text-gray-700">Buat Izin</span>
      </button>
      <button @click="$router.push('/student/report/create')" class="bg-white p-4 rounded-xl border border-border flex flex-col items-center justify-center gap-2 shadow-sm hover:border-primary transition-colors">
        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">L</div>
        <span class="text-sm font-medium text-gray-700">Lapor BK</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'
const authStore = useAuthStore()
</script>
