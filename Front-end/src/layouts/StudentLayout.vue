<template>
  <div class="min-h-screen bg-[#F4F7F4] font-sans text-slate-800 pb-24 lg:pb-8">
    <ToastNotification />

    <!-- Top Bar Header -->
    <header class="bg-[#355245] text-white sticky top-0 z-30 shadow-md border-b border-white/10">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 h-16 flex justify-between items-center">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center border border-white/20 shadow-inner font-extrabold text-white text-base">
            SC
          </div>
          <div>
            <h1 class="font-extrabold text-base tracking-tight leading-none text-white">StudentCare</h1>
            <p class="text-[10px] text-emerald-200 font-medium tracking-wider mt-0.5 uppercase">Portal Siswa • SMKN 2 Depok</p>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <div class="hidden sm:flex flex-col text-right">
            <span class="text-xs font-bold text-white leading-tight">{{ authStore.userName }}</span>
            <span class="text-[10px] text-emerald-200">{{ authStore.userClass || 'Siswa' }}</span>
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

    <!-- Responsive Navigation (Bottom Bar on Mobile, Sticky Floating Bar) -->
    <div class="fixed bottom-3 left-0 right-0 max-w-lg mx-auto px-4 z-40">
      <nav class="bg-white/95 backdrop-blur-xl border border-slate-200 rounded-2xl p-1.5 flex justify-around items-center shadow-xl shadow-slate-900/10">
        <router-link
          to="/student/dashboard"
          class="flex flex-col items-center py-2 px-3.5 rounded-xl text-slate-500 hover:text-[#355245] transition-all"
          active-class="bg-[#355245] !text-white font-bold shadow-md shadow-[#355245]/20"
        >
          <Home class="w-5 h-5" />
          <span class="text-[10px] font-bold mt-1 tracking-wider uppercase">Beranda</span>
        </router-link>

        <router-link
          to="/student/permit/create"
          class="flex flex-col items-center py-2 px-3.5 rounded-xl text-slate-500 hover:text-[#355245] transition-all"
          active-class="bg-[#355245] !text-white font-bold shadow-md shadow-[#355245]/20"
        >
          <FilePlus class="w-5 h-5" />
          <span class="text-[10px] font-bold mt-1 tracking-wider uppercase">Form Izin</span>
        </router-link>

        <router-link
          to="/student/report/create"
          class="flex flex-col items-center py-2 px-3.5 rounded-xl text-slate-500 hover:text-[#355245] transition-all"
          active-class="bg-[#355245] !text-white font-bold shadow-md shadow-[#355245]/20"
        >
          <ShieldAlert class="w-5 h-5" />
          <span class="text-[10px] font-bold mt-1 tracking-wider uppercase">Lapor BK</span>
        </router-link>

        <router-link
          to="/student/tracking"
          class="flex flex-col items-center py-2 px-3.5 rounded-xl text-slate-500 hover:text-[#355245] transition-all"
          active-class="bg-[#355245] !text-white font-bold shadow-md shadow-[#355245]/20"
        >
          <Clock class="w-5 h-5" />
          <span class="text-[10px] font-bold mt-1 tracking-wider uppercase">Tracking</span>
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
