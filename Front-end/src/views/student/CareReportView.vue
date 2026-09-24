<template>
  <div class="bg-white rounded-2xl border border-border p-5 shadow-sm">
    <h2 class="text-lg font-bold text-gray-800 mb-1">Lapor BK / Care Report</h2>
    <p class="text-sm text-gray-500 mb-4">Laporan Anda ditangani secara rahasia.</p>
    
    <form @submit.prevent="submitReport" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
        <select v-model="form.category" class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-primary">
          <option value="BULLYING">Perundungan (Bullying)</option>
          <option value="FACILITY">Fasilitas Sekolah</option>
          <option value="ACADEMIC">Masalah Akademik</option>
          <option value="PERSONAL">Masalah Personal</option>
          <option value="OTHERS">Lainnya</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Detail Kejadian / Keluhan</label>
        <textarea v-model="form.description" rows="4" class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-primary" placeholder="Ceritakan dengan detail..."></textarea>
      </div>

      <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
        <div>
          <p class="text-sm font-medium text-gray-800">Kirim Anonim</p>
          <p class="text-xs text-gray-500">Nama Anda disembunyikan</p>
        </div>
        <label class="relative inline-flex items-center cursor-pointer">
          <input type="checkbox" v-model="form.is_anonymous" class="sr-only peer">
          <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
        </label>
      </div>

      <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-lg font-medium hover:bg-primary-hover transition-colors mt-2">
        Kirim Laporan
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const form = ref({ category: 'BULLYING', description: '', is_anonymous: true })

const submitReport = () => {
  alert('Laporan berhasil dikirim ke BK.')
  router.push('/student/dashboard')
}
</script>
