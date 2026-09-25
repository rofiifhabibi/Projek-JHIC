<template>
  <div class="min-h-screen bg-[#F4F7F4] flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center space-y-6 bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-xl">
      <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-3xl bg-[#E8EFEA] text-[#355245] flex items-center justify-center mx-auto shadow-inner">
        <FileQuestion class="w-8 h-8 sm:w-10 sm:h-10" />
      </div>

      <div class="space-y-2">
        <span class="inline-flex px-3 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-bold uppercase tracking-wider border border-rose-200/60">
          Galat 404
        </span>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Halaman Tidak Ditemukan</h1>
        <p class="text-xs sm:text-sm text-slate-500 max-w-sm mx-auto leading-relaxed">
          Tautan yang Anda tuju mungkin salah ketik, sudah dipindahkan, atau Anda tidak memiliki hak akses ke halaman tersebut.
        </p>
      </div>

      <div class="pt-2 flex flex-col sm:flex-row gap-3 justify-center">
        <button
          @click="goBackOrHome"
          class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-[#355245] text-white font-bold text-xs sm:text-sm hover:bg-[#273e34] active:scale-95 transition shadow-sm"
        >
          Kembali ke Menu Utama
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { FileQuestion } from 'lucide-vue-next'

const router = useRouter()
const authStore = useAuthStore()

const goBackOrHome = () => {
  if (authStore.isAuthenticated) {
    router.push(authStore.defaultRedirectRoute)
  } else {
    router.push('/')
  }
}
</script>
