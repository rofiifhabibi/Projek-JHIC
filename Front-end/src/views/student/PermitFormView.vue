<template>
  <div class="bg-white rounded-2xl border border-border p-5 shadow-sm">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Pengajuan Izin</h2>
    
    <form @submit.prevent="submitPermit" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Izin</label>
        <div class="flex gap-4">
          <label class="flex items-center gap-2">
            <input type="radio" v-model="form.type" value="TEMP" class="text-primary focus:ring-primary">
            <span class="text-sm">Keluar Sementara</span>
          </label>
          <label class="flex items-center gap-2">
            <input type="radio" v-model="form.type" value="EXIT_SCHOOL" class="text-primary focus:ring-primary">
            <span class="text-sm">Pulang</span>
          </label>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Guru Pengampu</label>
        <select v-model="form.teacher_id" class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-primary">
          <option value="" disabled>Pilih Guru...</option>
          <option value="1">Bpk. Ahmad (Matematika)</option>
          <option value="2">Ibu Budi (Fisika)</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Alasan</label>
        <textarea v-model="form.reason" rows="3" class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-primary" placeholder="Jelaskan alasan izin..."></textarea>
      </div>

      <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-lg font-medium hover:bg-primary-hover transition-colors mt-2">
        Ajukan Izin
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const form = ref({ type: 'TEMP', teacher_id: '', reason: '' })

const submitPermit = () => {
  alert('Izin diajukan! Menunggu persetujuan guru.')
  router.push('/student/dashboard')
}
</script>
