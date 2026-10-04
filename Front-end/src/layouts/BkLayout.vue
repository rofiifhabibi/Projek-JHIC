<template>
  <div class="min-h-screen bg-[#F4F7F4] text-slate-800 font-sans flex flex-col md:flex-row">
    <!-- Desktop Sidebar -->
    <aside class="w-64 bg-[#355245] border-r border-[#273e34] text-white hidden md:flex flex-col justify-between shadow-md z-20 shrink-0">
      <div>
        <router-link to="/bk/kanban" class="h-20 flex items-center gap-3 px-6 border-b border-white/10 bg-black/10 group">
          <AppLogo variant="bk" size="md" theme="white-trans" class="group-hover:scale-105 transition" />
          <div>
            <h1 class="text-sm font-extrabold text-white group-hover:text-emerald-200 transition tracking-tight leading-tight">Bimbingan Konseling</h1>
            <p class="text-xs text-[#E8EFEA]/80 font-medium uppercase tracking-wider mt-0.5">SMKN 2 Depok Sleman</p>
          </div>
        </router-link>

        <nav class="p-3.5 space-y-1.5">
          <div class="px-3 py-2 text-xs font-bold text-[#E8EFEA]/70 uppercase tracking-widest">Menu Konseling</div>
          <router-link
            to="/bk/kanban"
            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all text-[#E8EFEA]/90 hover:text-white hover:bg-white/10"
            active-class="!bg-white !text-[#355245] shadow-sm font-extrabold"
          >
            <div class="flex items-center gap-2.5 min-w-0">
              <Kanban class="w-4 h-4 shrink-0" />
              <span class="whitespace-nowrap truncate">Penanganan Kasus</span>
            </div>
            <span
              v-if="openCasesCount > 0"
              class="bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-xs shrink-0 whitespace-nowrap"
            >
              {{ openCasesCount }}
            </span>
          </router-link>

          <router-link
            to="/bk/global-monitor"
            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all text-[#E8EFEA]/90 hover:text-white hover:bg-white/10"
            active-class="!bg-white !text-[#355245] shadow-sm font-extrabold"
          >
            <div class="flex items-center gap-2.5 min-w-0">
              <Globe class="w-4 h-4 shrink-0" />
              <span class="whitespace-nowrap truncate">Izin Siswa</span>
            </div>
            <span
              v-if="overdueCount > 0"
              class="bg-rose-500 text-white text-[11px] font-bold px-2 py-0.5 rounded-full shadow-xs shrink-0 whitespace-nowrap"
            >
              {{ overdueCount }} Terlambat
            </span>
          </router-link>
        </nav>
      </div>

      <div class="p-4 border-t border-white/10 bg-black/10 space-y-3">
        <div class="px-2">
          <p class="text-xs font-bold text-white truncate">{{ authStore.userName }}</p>
          <p class="text-xs text-[#E8EFEA]/80 font-medium">Guru Bimbingan Konseling</p>
        </div>
        <button
          type="button"
          @click="showLogoutConfirm = true"
          class="w-full bg-white/10 hover:bg-white/20 active:scale-95 text-white py-2.5 rounded-xl text-xs font-bold transition-all border border-white/15 inline-flex items-center justify-center gap-2 cursor-pointer touch-manipulation leading-none"
        >
          <LogOut class="w-3.5 h-3.5 shrink-0" />
          <span>Keluar</span>
        </button>
      </div>
    </aside>

    <!-- Main Workspace -->
    <main class="flex-1 min-w-0 flex flex-col">
      <!-- Mobile Header WITH Navigation Tabs -->
      <header class="md:hidden bg-[#355245] text-white border-b border-white/10 sticky top-0 z-30 shadow-md">
        <div class="h-16 px-4 flex items-center justify-between">
          <router-link to="/bk/kanban" class="flex items-center gap-2.5 group">
            <AppLogo variant="bk" size="sm" theme="white-trans" class="group-hover:scale-105 transition" />
            <div>
              <h1 class="font-extrabold text-sm text-white group-hover:text-emerald-200 transition leading-tight">Bimbingan Konseling</h1>
              <p class="text-[11px] text-[#E8EFEA]/80 font-medium">SMKN 2 Depok Sleman</p>
            </div>
          </router-link>
          <div class="flex items-center gap-2">
            <NotificationToggle variant="dark" />
            <button
              type="button"
              @click="showLogoutConfirm = true"
              class="text-xs bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-xl border border-white/15 font-semibold cursor-pointer touch-manipulation transition inline-flex items-center justify-center leading-none"
            >
              Keluar
            </button>
          </div>
        </div>

        <div class="bg-black/25 border-t border-white/10 px-3 py-2 flex items-center justify-center gap-2">
          <router-link
            to="/bk/kanban"
            class="flex-1 max-w-[200px] text-xs font-bold py-2 px-3 rounded-xl flex items-center justify-center gap-1.5 transition-all text-[#E8EFEA]/90 hover:text-white"
            active-class="!bg-white !text-[#355245] shadow-sm font-extrabold"
          >
            <Kanban class="w-3.5 h-3.5 shrink-0" />
            <span>Kasus Siswa</span>
            <span
              v-if="openCasesCount > 0"
              class="bg-amber-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full"
            >
              {{ openCasesCount }}
            </span>
          </router-link>
          <router-link
            to="/bk/global-monitor"
            class="flex-1 max-w-[200px] text-xs font-bold py-2 px-3 rounded-xl flex items-center justify-center gap-1.5 transition-all text-[#E8EFEA]/90 hover:text-white"
            active-class="!bg-white !text-[#355245] shadow-sm font-extrabold"
          >
            <Globe class="w-3.5 h-3.5 shrink-0" />
            <span>Izin Siswa</span>
            <span
              v-if="overdueCount > 0"
              class="bg-rose-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full"
            >
              {{ overdueCount }}
            </span>
          </router-link>
        </div>
      </header>

      <!-- Desktop Top Header Bar -->
      <div class="hidden md:flex h-16 bg-white border-b border-slate-200 px-6 lg:px-8 items-center justify-between sticky top-0 z-20 shadow-xs">
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
          <span class="text-slate-600 font-medium">Layanan Bimbingan Konseling & Pemantauan Siswa</span>
          <span>•</span>
          <span class="text-[#355245] font-bold">SMKN 2 Depok Sleman</span>
        </div>
        <div class="flex items-center gap-3">
          <NotificationToggle variant="light" />
          <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            Panel Petugas BK
          </span>
        </div>
      </div>

      <div class="p-4 sm:p-6 lg:p-8 flex-1 max-w-7xl 2xl:max-w-[1440px] w-full mx-auto">
        <router-view v-slot="{ Component }">
          <transition name="page-fade" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </div>
    </main>

    <!-- Logout Modal -->
    <ConfirmDialog
      :show="showLogoutConfirm"
      title="Konfirmasi Keluar"
      message="Apakah Anda yakin ingin keluar dari akun Guru BK?"
      confirm-text="Ya, Keluar"
      variant="danger"
      @confirm="handleLogout"
      @cancel="showLogoutConfirm = false"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useReportStore } from '@/stores/report'
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue'
import AppLogo from '@/components/ui/AppLogo.vue'
import NotificationToggle from '@/components/ui/NotificationToggle.vue'
import { Kanban, Globe, LogOut } from 'lucide-vue-next'

const authStore = useAuthStore()
const reportStore = useReportStore()
const showLogoutConfirm = ref(false)
let pollTimer = null

const overdueCount = computed(() => {
  return reportStore.globalMobility?.filter(p => p.status === 'OVERDUE').length || 0
})

const openCasesCount = computed(() => {
  return reportStore.kanban?.OPEN?.length || 0
})

const syncData = async () => {
  await Promise.allSettled([
    reportStore.fetchGlobalMobility(),
    reportStore.fetchKanban(),
    reportStore.fetchBkMetrics()
  ])
}

const startPolling = (intervalMs = 15000) => {
  stopPolling()
  const jitter = Math.floor(Math.random() * 3000)
  pollTimer = setInterval(syncData, intervalMs + jitter)
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
    await syncData()
    startPolling(15000)
  }
}

onMounted(() => {
  syncData()
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
