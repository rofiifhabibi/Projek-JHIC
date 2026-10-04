<template>
  <div class="min-h-screen bg-[#F4F7F4] font-sans text-slate-800 pb-24 md:pb-12">
    <!-- Top Bar Header -->
    <header class="bg-[#355245] text-white sticky top-0 z-30 shadow-md border-b border-white/10">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex justify-between items-center">
        <div class="flex items-center gap-2.5 sm:gap-6 min-w-0">
          <router-link to="/student/dashboard" class="flex items-center gap-2.5 shrink-0 group">
            <AppLogo size="md" theme="white-trans" class="shrink-0" />
            <div class="min-w-0">
              <h1 class="font-extrabold text-sm sm:text-base tracking-tight leading-none text-white group-hover:text-emerald-200 transition">StudentCare</h1>
              <p class="text-xs text-[#E8EFEA]/80 font-medium tracking-wide mt-0.5">SMKN 2 Depok Sleman</p>
            </div>
          </router-link>

          <!-- Desktop & Tablet Header Nav Tabs -->
          <nav class="hidden md:flex items-center gap-1 lg:gap-1.5 ml-2 bg-black/15 p-1 rounded-xl border border-white/10 shrink-0">
            <router-link
              to="/student/dashboard"
              class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-[#E8EFEA]/90 hover:text-white flex items-center gap-1.5"
              active-class="!bg-white !text-[#355245] shadow-xs font-extrabold"
            >
              <Home class="w-3.5 h-3.5" />
              <span>Beranda</span>
            </router-link>
            <router-link
              to="/student/permit/create"
              class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-[#E8EFEA]/90 hover:text-white flex items-center gap-1.5"
              active-class="!bg-white !text-[#355245] shadow-xs font-extrabold"
            >
              <FilePlus class="w-3.5 h-3.5" />
              <span>Buat Izin</span>
            </router-link>
            <router-link
              to="/student/report/create"
              class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-[#E8EFEA]/90 hover:text-white flex items-center gap-1.5"
              active-class="!bg-white !text-[#355245] shadow-xs font-extrabold"
            >
              <ShieldAlert class="w-3.5 h-3.5" />
              <span>Lapor BK</span>
            </router-link>
            <router-link
              to="/student/tracking"
              class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-[#E8EFEA]/90 hover:text-white flex items-center gap-1.5"
              active-class="!bg-white !text-[#355245] shadow-xs font-extrabold"
            >
              <Clock class="w-3.5 h-3.5" />
              <span>Riwayat</span>
            </router-link>
          </nav>
        </div>

        <div class="flex items-center gap-2 sm:gap-2.5 shrink-0">
          <div class="hidden sm:flex flex-col text-right">
            <span class="text-xs font-bold text-white leading-tight truncate max-w-[140px]">{{ authStore.userName }}</span>
            <span class="text-xs text-[#E8EFEA]/80">{{ authStore.userClass || 'Siswa' }}</span>
          </div>

          <NotificationToggle variant="dark" />

          <button
            type="button"
            @click="showLogoutConfirm = true"
            class="text-xs bg-white/10 hover:bg-white/20 active:scale-95 px-2.5 sm:px-3 py-1.5 rounded-xl font-semibold border border-white/15 transition-all inline-flex items-center justify-center gap-1.5 text-white cursor-pointer touch-manipulation leading-none"
          >
            <LogOut class="w-3.5 h-3.5 opacity-80 shrink-0" />
            <span class="hidden sm:inline">Keluar</span>
          </button>
        </div>
      </div>
    </header>

    <!-- Container Body -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-5 sm:py-7 space-y-6">
      <router-view v-slot="{ Component }">
        <transition name="page-fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <!-- Responsive Navigation (Mobile Bottom Floating Dock Only) -->
    <div class="md:hidden fixed bottom-3 left-0 right-0 max-w-md mx-auto px-3 z-40 pb-safe">
      <nav class="bg-white/95 backdrop-blur-xl border border-slate-200/90 rounded-2xl p-1.5 flex justify-around items-center shadow-lg">
        <router-link
          to="/student/dashboard"
          class="flex-1 flex flex-col items-center py-1 rounded-xl transition-all active:scale-90 duration-150"
          :class="$route.path === '/student/dashboard' ? 'text-[#355245] font-extrabold' : 'text-slate-500 hover:text-slate-700 font-medium'"
        >
          <div class="w-10 h-7 rounded-xl flex items-center justify-center transition-all" :class="$route.path === '/student/dashboard' ? 'bg-[#E8EFEA] text-[#355245]' : ''">
            <Home class="w-5 h-5" />
          </div>
          <span class="text-xs tracking-tight mt-0.5">Beranda</span>
        </router-link>

        <router-link
          to="/student/permit/create"
          class="flex-1 flex flex-col items-center py-1 rounded-xl transition-all active:scale-90 duration-150"
          :class="$route.path.startsWith('/student/permit') ? 'text-[#355245] font-extrabold' : 'text-slate-500 hover:text-slate-700 font-medium'"
        >
          <div class="w-10 h-7 rounded-xl flex items-center justify-center transition-all" :class="$route.path.startsWith('/student/permit') ? 'bg-[#E8EFEA] text-[#355245]' : ''">
            <FilePlus class="w-5 h-5" />
          </div>
          <span class="text-xs tracking-tight mt-0.5">Buat Izin</span>
        </router-link>

        <router-link
          to="/student/report/create"
          class="flex-1 flex flex-col items-center py-1 rounded-xl transition-all active:scale-90 duration-150"
          :class="$route.path.startsWith('/student/report') ? 'text-[#355245] font-extrabold' : 'text-slate-500 hover:text-slate-700 font-medium'"
        >
          <div class="w-10 h-7 rounded-xl flex items-center justify-center transition-all" :class="$route.path.startsWith('/student/report') ? 'bg-[#E8EFEA] text-[#355245]' : ''">
            <ShieldAlert class="w-5 h-5" />
          </div>
          <span class="text-xs tracking-tight mt-0.5">Lapor BK</span>
        </router-link>

        <router-link
          to="/student/tracking"
          class="flex-1 flex flex-col items-center py-1 rounded-xl transition-all active:scale-90 duration-150"
          :class="$route.path === '/student/tracking' ? 'text-[#355245] font-extrabold' : 'text-slate-500 hover:text-slate-700 font-medium'"
        >
          <div class="w-10 h-7 rounded-xl flex items-center justify-center transition-all" :class="$route.path === '/student/tracking' ? 'bg-[#E8EFEA] text-[#355245]' : ''">
            <Clock class="w-5 h-5" />
          </div>
          <span class="text-xs tracking-tight mt-0.5">Riwayat</span>
        </router-link>
      </nav>
    </div>

    <!-- Confirm Logout Modal -->
    <ConfirmDialog
      :show="showLogoutConfirm"
      title="Konfirmasi Keluar"
      message="Apakah kamu yakin ingin keluar dari akun?"
      confirm-text="Ya, Keluar"
      variant="danger"
      @confirm="handleLogout"
      @cancel="showLogoutConfirm = false"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { usePermitStore } from '@/stores/permit'
import { useReportStore } from '@/stores/report'
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue'
import AppLogo from '@/components/ui/AppLogo.vue'
import NotificationToggle from '@/components/ui/NotificationToggle.vue'
import { Home, FilePlus, ShieldAlert, Clock, LogOut } from 'lucide-vue-next'

const authStore = useAuthStore()
const permitStore = usePermitStore()
const reportStore = useReportStore()
const showLogoutConfirm = ref(false)
let pollTimer = null

const syncStudentData = async () => {
  await Promise.allSettled([
    permitStore.fetchActivePermit(),
    reportStore.fetchMyReports()
  ])
}

const startPolling = (intervalMs = 15000) => {
  stopPolling()
  const jitter = Math.floor(Math.random() * 3000)
  pollTimer = setInterval(syncStudentData, intervalMs + jitter)
}

const stopPolling = () => {
  if (pollTimer) {
    clearInterval(pollTimer)
    pollTimer = null
  }
}

const handleVisibilityChange = async () => {
  if (document.hidden) {
    // Di latar belakang tetap polling dengan interval santai (25 detik) agar notifikasi sistem tetap terkirim
    startPolling(25000)
  } else {
    // Kembali aktif ke layar: langsung sinkronkan seketika lalu kembali ke interval aktif (15 detik)
    await syncStudentData()
    startPolling(15000)
  }
}

onMounted(() => {
  syncStudentData()
  startPolling()
  document.addEventListener('visibilitychange', handleVisibilityChange)
})

onUnmounted(() => {
  stopPolling()
  document.removeEventListener('visibilitychange', handleVisibilityChange)
})

const handleLogout = () => {
  showLogoutConfirm.value = false
  authStore.logout()
}
</script>
