<template>
  <div class="min-h-screen bg-[#F4F7F4] text-slate-800 font-sans flex flex-col md:flex-row">
    <ToastNotification />

    <!-- Desktop Sidebar -->
    <aside class="w-64 bg-slate-900 border-r border-slate-800 hidden md:flex flex-col justify-between shadow-2xl z-20 shrink-0">
      <div>
        <div class="h-20 flex items-center gap-3 px-6 border-b border-slate-800 bg-slate-950/40">
          <div class="w-10 h-10 bg-[#355245] rounded-xl flex items-center justify-center font-black text-white shadow-lg">
            BK
          </div>
          <div>
            <h1 class="text-sm font-extrabold text-white tracking-tight leading-tight">Console BK</h1>
            <p class="text-[10px] text-emerald-400 font-semibold uppercase tracking-wider mt-0.5">Bimbingan Konseling</p>
          </div>
        </div>

        <nav class="p-4 space-y-1.5">
          <div class="px-3 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Menu Konseling</div>
          <router-link
            to="/bk/kanban"
            class="flex items-center justify-between px-4 py-3 rounded-xl text-xs font-bold transition-all text-slate-300 hover:text-white hover:bg-slate-800"
            active-class="bg-[#355245] text-white shadow-md"
          >
            <div class="flex items-center gap-3">
              <Kanban class="w-4 h-4" />
              <span>Papan Kanban BK</span>
            </div>
            <span
              v-if="openCasesCount > 0"
              class="bg-amber-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full"
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
              <span>Monitor Mobilitas</span>
            </div>
            <span
              v-if="overdueCount > 0"
              class="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full animate-pulse flex items-center gap-1 shadow-sm"
            >
              <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
              {{ overdueCount }} Terlambat
            </span>
          </router-link>
        </nav>
      </div>

      <div class="p-4 border-t border-slate-800 bg-slate-950/40 space-y-3">
        <div class="px-2">
          <p class="text-xs font-bold text-white truncate">{{ authStore.userName }}</p>
          <p class="text-[10px] text-emerald-400 font-semibold">Petugas Konseling BK</p>
        </div>
        <button
          @click="showLogoutConfirm = true"
          class="w-full bg-slate-800 hover:bg-slate-700 active:scale-95 text-slate-200 py-2.5 rounded-xl text-xs font-bold transition-all border border-slate-700 flex items-center justify-center gap-2"
        >
          <LogOut class="w-3.5 h-3.5" />
          <span>Keluar Sesi</span>
        </button>
      </div>
    </aside>

    <!-- Main Workspace -->
    <main class="flex-1 min-w-0 flex flex-col">
      <!-- Mobile Header WITH Navigation Tabs -->
      <header class="md:hidden bg-slate-900 text-white border-b border-slate-800 sticky top-0 z-30">
        <div class="h-16 px-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-[#355245] rounded-lg flex items-center justify-center font-bold text-xs">BK</div>
            <h1 class="font-extrabold text-sm text-white">Console BK</h1>
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
            <span>Kanban BK</span>
            <span
              v-if="openCasesCount > 0"
              class="bg-amber-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full"
            >
              {{ openCasesCount }}
            </span>
          </router-link>
          <router-link
            to="/bk/global-monitor"
            class="text-xs font-bold py-1.5 px-3 rounded-lg text-slate-300 flex items-center gap-1.5"
            active-class="bg-[#355245] text-white"
          >
            <span>Mobilitas Global</span>
            <span
              v-if="overdueCount > 0"
              class="bg-rose-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full animate-pulse"
            >
              {{ overdueCount }}
            </span>
          </router-link>
        </div>
      </header>

      <div class="p-4 sm:p-6 lg:p-8 flex-1">
        <router-view />
      </div>
    </main>

    <!-- Logout Modal -->
    <ConfirmDialog
      :show="showLogoutConfirm"
      title="Keluar Sesi Console BK"
      message="Apakah Anda yakin ingin keluar dari console BK?"
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
import ToastNotification from '@/components/ui/ToastNotification.vue'
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue'
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

onMounted(() => {
  syncData()
  pollTimer = setInterval(syncData, 4000)
})

onUnmounted(() => {
  if (pollTimer) clearInterval(pollTimer)
})

const handleLogout = () => {
  showLogoutConfirm.value = false
  authStore.logout()
}
</script>
