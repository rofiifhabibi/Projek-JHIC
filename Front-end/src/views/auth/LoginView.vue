<template>
  <div class="min-h-screen flex items-center justify-center p-4 bg-canvas">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-border p-8">
      <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-primary mb-2">Student Care</h1>
        <p class="text-gray-500 text-sm">Masuk untuk melanjutkan</p>
      </div>
      
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Peran (Simulasi)</label>
          <select v-model="selectedRole" class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-primary">
            <option value="student">Siswa</option>
            <option value="teacher">Guru</option>
            <option value="satpam">Satpam</option>
            <option value="bk">Guru BK</option>
          </select>
        </div>
        
        <button @click="handleLogin" class="w-full bg-primary hover:bg-primary-hover text-white font-medium py-2.5 rounded-lg transition-colors">
          Masuk ke Sistem
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
const selectedRole = ref('student')

const handleLogin = () => {
  authStore.login(selectedRole.value)
}
</script>
