<template>
  <div class="min-h-screen bg-[#F4F7F4] text-slate-800 font-sans flex flex-col md:flex-row">
    <!-- Desktop Sidebar -->
    <aside class="w-64 bg-slate-900 border-r border-slate-800 hidden md:flex flex-col justify-between shadow-md z-20 shrink-0">
      <div>
        <div class="h-20 flex items-center gap-3 px-6 border-b border-slate-800 bg-slate-950/40">
          <AppLogo variant="bk" size="md" theme="emerald" />
          <div>
            <h1 class="text-sm font-extrabold text-white tracking-tight leading-tight">Portal BK</h1>
            <p class="text-xs text-teal-400 font-semibold uppercase tracking-wider mt-0.5">Bimbingan Konseling</p>
          </div>
        </div>

        <nav class="p-4 space-y-1.5">
          <div class="px-3 py-2 text-xs font-bold text-slate-500 uppercase tracking-widest">Menu Konseling</div>
          <router-link
            to="/bk/kanban"
            class="flex items-center justify-between px-4 py-3 rounded-xl text-xs font-bold transition-all text-slate-300 hover:text-white hover:bg-slate-800"
            active-class="bg-[#355245] text-white shadow-md"
          >
            <div class="flex items-center gap-3">
              <Kanban class="w-4 h-4" />
              <span>Penanganan Kasus</span>
            </div>
            <span
              v-if="openCasesCount > 0"
              class="bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"
            >
              {{ openCasesCount }}
            </span>
          </router-link>

          <router-link
            to="/bk/global-monitor"
            class="flex items-center justify-between px-4 py-3 rounded-xl text-xs font-bold transition-all text-slate-300 hover:text-white hover:bg-slate-800"
            active-class="bg-[#355245] text-white shadow-md"
          >
            <div class="flex items-center gap-3">
              <Globe class="w-4 h-4" />
              <span>Izin Seluruh Siswa</span>
            </div>
            <span
              v-if="overdueCount > 0"
              class="bg-rose-500 text-white text-xs font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm"
            >
              {{ overdueCount }} Terlambat
            </span>
          </router-link>
        </nav>
      </div>

      <div class="p-4 border-t border-slate-800 bg-slate-950/40 space-y-3">
        <div class="px-2">
          <p class="text-xs font-bold text-white truncate">{{ authStore.userName }}</p>
          <p class="text-xs text-teal-400 font-semibold">Guru Bimbingan Konseling</p>
        </div>
        <button
          @click="showLogoutConfirm = true"
          class="w-full bg-slate-800 hover:bg-slate-700 active:scale-95 text-slate-200 py-2.5 rounded-xl text-xs font-bold transition-all border border-slate-700 flex items-center justify-center gap-2"
        >
          <LogOut class="w-3.5 h-3.5" />
          <span>Keluar</span>
        </button>
      </div>
    </aside>

    <!-- Main Workspace -->
    <main class="flex-1 min-w-0 flex flex-col">
      <!-- Mobile Header WITH Navigation Tabs -->
      <header class="md:hidden bg-slate-900 text-white border-b border-slate-800 sticky top-0 z-30">
        <div class="h-16 px-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <AppLogo variant="bk" size="sm" theme="emerald" />
            <h1 class="font-extrabold text-sm text-white">Portal BK</h1>
          </div>
          <button @click="showLogoutConfirm = true" class="text-xs bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-700">
            Keluar
          </button>
        </div>

        <div class="bg-slate-800 px-4 py-2 flex justify-around border-t border-slate-700/80">
          <router-link
            to="/bk/kanban"
            class="text-xs font-bold py-1.5 px-3 rounded-lg text-slate-300 flex items-center gap-1.5"
            active-class="bg-[#355245] text-white"
          >
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
            class="text-xs font-bold py-1.5 px-3 rounded-lg text-slate-300 flex items-center gap-1.5"
            active-class="bg-[#355245] text-white"
          >
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

const startPolling = () => {
  stopPolling()
  const jitter = Math.floor(Math.random() * 3000)
  pollTimer = setInterval(syncData, 15000 + jitter)
}

const stopPolling = () => {
  if (pollTimer) {
    clearInterval(pollTimer)
    pollTimer = null
  }
}

const handleVisibilityChange = async () => {
  if (document.hidden) {
    stopPolling()
  } else {
    await syncData()
    startPolling()
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
