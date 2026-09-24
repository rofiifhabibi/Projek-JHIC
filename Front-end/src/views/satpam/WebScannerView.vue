<template>
  <div class="min-h-screen bg-gray-900 flex flex-col">
    <!-- Header -->
    <header class="bg-gray-800 text-white p-4 flex justify-between items-center shadow-md z-10">
      <h1 class="font-bold">Scanner Gerbang</h1>
      <button @click="authStore.logout()" class="text-sm bg-gray-700 hover:bg-gray-600 px-3 py-1 rounded">Keluar</button>
    </header>

    <!-- Scanner Area -->
    <div class="flex-1 relative flex items-center justify-center bg-black overflow-hidden">
      <!-- Mock Camera Viewfinder -->
      <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-50">
        <div class="w-64 h-64 border-2 border-dashed border-green-400 rounded-lg relative">
          <div class="absolute top-0 left-0 w-4 h-4 border-t-4 border-l-4 border-green-500 -mt-1 -ml-1"></div>
          <div class="absolute top-0 right-0 w-4 h-4 border-t-4 border-r-4 border-green-500 -mt-1 -mr-1"></div>
          <div class="absolute bottom-0 left-0 w-4 h-4 border-b-4 border-l-4 border-green-500 -mb-1 -ml-1"></div>
          <div class="absolute bottom-0 right-0 w-4 h-4 border-b-4 border-r-4 border-green-500 -mb-1 -mr-1"></div>
        </div>
      </div>
      
      <div class="text-center text-white/50 z-10">
        <p class="mb-4">Menginisialisasi html5-qrcode...</p>
        <button @click="mockScan" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-full font-medium pointer-events-auto shadow-lg">
          Simulasi Scan Berhasil
        </button>
      </div>
    </div>

    <!-- Modal Mock -->
    <div v-if="showResult" class="absolute inset-0 bg-black/60 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl">
        <div class="bg-green-500 text-white p-4 text-center">
          <h2 class="text-xl font-bold">IZIN VALID</h2>
          <p class="text-sm opacity-90">Pemindaian Berhasil</p>
        </div>
        <div class="p-6">
          <div class="text-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Budi Santoso</h3>
            <p class="text-sm text-gray-500">XII RPL 1 - 12345</p>
          </div>
          <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 mb-6">
            <p class="text-xs text-gray-500 mb-1">Jenis Izin</p>
            <p class="font-medium text-gray-800">Keluar Sementara (TEMP)</p>
          </div>
          <button @click="showResult = false" class="w-full bg-gray-800 hover:bg-gray-700 text-white py-3 rounded-xl font-medium">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
const showResult = ref(false)

const mockScan = () => {
  showResult.value = true
}
</script>
