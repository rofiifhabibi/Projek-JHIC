<template>
  <div class="min-h-screen bg-[#F4F7F4] text-slate-800 font-sans">
    <ToastNotification />

    <!-- Top Navbar Header -->
    <header class="bg-[#355245] text-white shadow-lg sticky top-0 z-30 border-b border-white/10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center gap-3 sm:gap-6 min-w-0">
            <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
              <div class="w-9 h-9 sm:w-10 sm:h-10 bg-white/15 rounded-xl flex items-center justify-center font-extrabold text-white text-sm sm:text-base border border-white/20 shrink-0">
                GP
              </div>
              <div class="min-w-0">
                <h1 class="font-extrabold text-sm sm:text-base tracking-tight leading-none text-white truncate">Workstation Guru</h1>
                <p class="text-[9px] sm:text-[10px] text-[#E8EFEA]/80 font-medium tracking-wide mt-0.5 uppercase truncate">SMKN 2 Depok Sleman</p>
              </div>
            </div>

            <!-- Tablet & Desktop Nav Tabs -->
            <nav class="hidden md:flex items-center gap-1.5 lg:gap-2 ml-1 lg:ml-4 bg-black/15 p-1 rounded-xl border border-white/10 shrink-0">
              <router-link
                to="/teacher/monitoring"
                class="px-3 lg:px-4 py-2 rounded-lg text-xs font-bold transition-all text-[#E8EFEA]/90 hover:text-white flex items-center gap-2"
                active-class="!bg-white !text-[#355245] shadow-sm font-extrabold"
              >
                <span>Monitoring Kelas</span>
                <span
                  v-if="overdueCount > 0"
                  class="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full animate-pulse flex items-center gap-1 shadow-sm"
                >
                  <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                  {{ overdueCount }} Terlambat
                </span>
              </router-link>
              <router-link
                to="/teacher/approvals"
                class="px-3 lg:px-4 py-2 rounded-lg text-xs font-bold transition-all text-[#E8EFEA]/90 hover:text-white flex items-center gap-2"
                active-class="!bg-white !text-[#355245] shadow-sm font-extrabold"
              >
                <span>Antrean Persetujuan</span>
                <span
                  v-if="pendingCount > 0"
                  class="bg-amber-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-sm"
                >
                  {{ pendingCount }}
                </span>
              </router-link>
            </nav>
          </div>

          <!-- User Info & Logout -->
          <div class="flex items-center gap-2.5 sm:gap-4 shrink-0">
            <div class="hidden lg:block text-right">
              <p class="text-xs font-bold text-white leading-none truncate max-w-[150px]">{{ authStore.userName }}</p>
              <p class="text-[10px] text-[#E8EFEA]/80 mt-1">Guru Pengampu Kelas</p>
            </div>
            <button
              @click="showLogoutConfirm = true"
              class="bg-white/10 hover:bg-white/20 active:scale-95 text-xs font-bold px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl border border-white/15 transition-all text-white flex items-center gap-1.5"
            >
              <LogOut class="w-3.5 h-3.5" />
              <span class="hidden sm:inline">Keluar</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Nav Tabs -->
      <div class="md:hidden bg-black/25 border-t border-white/10 px-3 py-2 flex items-center justify-center gap-2">
        <router-link
          to="/teacher/monitoring"
          class="flex-1 max-w-[200px] text-xs font-bold py-2 px-3 rounded-xl flex items-center justify-center gap-1.5 transition-all text-[#E8EFEA]/90"
          active-class="!bg-white !text-[#355245] shadow-sm font-extrabold"
        >
          <span>Monitoring Kelas</span>
          <span
            v-if="overdueCount > 0"
            class="bg-rose-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full animate-pulse"
          >
            {{ overdueCount }}
          </span>
        </router-link>
        <router-link
          to="/teacher/approvals"
          class="flex-1 max-w-[200px] text-xs font-bold py-2 px-3 rounded-xl flex items-center justify-center gap-1.5 transition-all text-[#E8EFEA]/90"
          active-class="!bg-white !text-[#355245] shadow-sm font-extrabold"
        >
          <span>Antrean Izin</span>
          <span
            v-if="pendingCount > 0"
            class="bg-amber-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full"
          >
            {{ pendingCount }}
          </span>
        </router-link>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-8 space-y-6">
      <router-view />
    </main>

    <!-- Logout Confirm -->
    <ConfirmDialog
      :show="showLogoutConfirm"
      title="Keluar Sesi Workstation"
      message="Apakah Anda yakin ingin keluar dari workstation guru?"
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
import { usePermitStore } from '@/stores/permit'
import ToastNotification from '@/components/ui/ToastNotification.vue'
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue'
import { LogOut } from 'lucide-vue-next'

const authStore = useAuthStore()
const permitStore = usePermitStore()
const showLogoutConfirm = ref(false)
let pollTimer = null

const overdueCount = computed(() => {
  return permitStore.monitoringData?.active_permits?.filter(p => p.status === 'OVERDUE').length || 0
})

const pendingCount = computed(() => {
  return permitStore.pendingApprovals?.length || 0
})

const syncData = async () => {
  await Promise.allSettled([
    permitStore.fetchTeacherMonitoring(),
    permitStore.fetchPendingApprovals()
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
