<template>
  <div class="min-h-screen bg-[#F4F7F4] font-sans text-slate-800 pb-28 lg:pb-8">
    <ToastNotification />

    <!-- Top Bar Header -->
    <header class="bg-[#355245] text-white sticky top-0 z-30 shadow-md border-b border-white/10">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 h-16 flex justify-between items-center">
        <div class="flex items-center gap-2.5">
          <div class="w-9 h-9 sm:w-10 sm:h-10 bg-white/15 rounded-xl flex items-center justify-center border border-white/20 shadow-inner font-extrabold text-white text-sm sm:text-base shrink-0">
            SC
          </div>
          <div>
            <h1 class="font-extrabold text-sm sm:text-base tracking-tight leading-none text-white">StudentCare</h1>
            <p class="text-[10px] text-[#E8EFEA]/80 font-medium tracking-wider mt-0.5 uppercase">Portal Siswa • SMKN 2 Depok</p>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <div class="hidden sm:flex flex-col text-right">
            <span class="text-xs font-bold text-white leading-tight">{{ authStore.userName }}</span>
            <span class="text-[10px] text-[#E8EFEA]/80">{{ authStore.userClass || 'Siswa' }}</span>
          </div>

          <button
            @click="showLogoutConfirm = true"
            class="text-xs bg-white/10 hover:bg-white/20 active:scale-95 px-3 py-1.5 rounded-xl font-semibold border border-white/15 transition-all flex items-center gap-1.5 text-white"
          >
            <LogOut class="w-3.5 h-3.5 opacity-80" />
            <span class="hidden sm:inline">Keluar</span>
          </button>
        </div>
      </div>
    </header>

    <!-- Container Body -->
    <main class="max-w-4xl mx-auto p-4 sm:p-6 space-y-6">
      <router-view />
    </main>

    <!-- Responsive Navigation (Bottom Floating Dock) -->
    <div class="fixed bottom-3 sm:bottom-4 left-0 right-0 max-w-md mx-auto px-3 z-40">
      <nav class="bg-white/95 backdrop-blur-xl border border-slate-200/90 rounded-2xl p-1.5 flex justify-around items-center shadow-xl shadow-slate-900/10">
        <router-link
          to="/student/dashboard"
          class="flex-1 flex flex-col items-center py-1 rounded-xl transition-all"
          :class="$route.path === '/student/dashboard' ? 'text-[#355245] font-extrabold' : 'text-slate-400 hover:text-slate-600 font-medium'"
        >
          <div class="w-10 h-7 rounded-xl flex items-center justify-center transition-all" :class="$route.path === '/student/dashboard' ? 'bg-[#E8EFEA] text-[#355245]' : ''">
            <Home class="w-5 h-5" />
          </div>
          <span class="text-[10px] tracking-wider uppercase mt-0.5">Beranda</span>
        </router-link>

        <router-link
          to="/student/permit/create"
          class="flex-1 flex flex-col items-center py-1 rounded-xl transition-all"
          :class="$route.path.startsWith('/student/permit') ? 'text-[#355245] font-extrabold' : 'text-slate-400 hover:text-slate-600 font-medium'"
        >
          <div class="w-10 h-7 rounded-xl flex items-center justify-center transition-all" :class="$route.path.startsWith('/student/permit') ? 'bg-[#E8EFEA] text-[#355245]' : ''">
            <FilePlus class="w-5 h-5" />
          </div>
          <span class="text-[10px] tracking-wider uppercase mt-0.5">Form Izin</span>
        </router-link>

        <router-link
          to="/student/report/create"
          class="flex-1 flex flex-col items-center py-1 rounded-xl transition-all"
          :class="$route.path.startsWith('/student/report') ? 'text-[#355245] font-extrabold' : 'text-slate-400 hover:text-slate-600 font-medium'"
        >
          <div class="w-10 h-7 rounded-xl flex items-center justify-center transition-all" :class="$route.path.startsWith('/student/report') ? 'bg-[#E8EFEA] text-[#355245]' : ''">
            <ShieldAlert class="w-5 h-5" />
          </div>
          <span class="text-[10px] tracking-wider uppercase mt-0.5">Lapor BK</span>
        </router-link>

        <router-link
          to="/student/tracking"
          class="flex-1 flex flex-col items-center py-1 rounded-xl transition-all"
          :class="$route.path === '/student/tracking' ? 'text-[#355245] font-extrabold' : 'text-slate-400 hover:text-slate-600 font-medium'"
        >
          <div class="w-10 h-7 rounded-xl flex items-center justify-center transition-all" :class="$route.path === '/student/tracking' ? 'bg-[#E8EFEA] text-[#355245]' : ''">
            <Clock class="w-5 h-5" />
          </div>
          <span class="text-[10px] tracking-wider uppercase mt-0.5">Tracking</span>
        </router-link>
      </nav>
    </div>

    <!-- Confirm Logout Modal -->
    <ConfirmDialog
      :show="showLogoutConfirm"
      title="Keluar Sesi"
      message="Apakah Anda yakin ingin keluar dari akun siswa ini?"
      confirm-text="Ya, Keluar"
      variant="danger"
      @confirm="handleLogout"
      @cancel="showLogoutConfirm = false"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import ToastNotification from '@/components/ui/ToastNotification.vue'
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue'
import { Home, FilePlus, ShieldAlert, Clock, LogOut } from 'lucide-vue-next'

const authStore = useAuthStore()
const showLogoutConfirm = ref(false)

const handleLogout = () => {
  showLogoutConfirm.value = false
  authStore.logout()
}
</script>
