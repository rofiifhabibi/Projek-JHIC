<template>
  <div class="min-h-screen bg-slate-950 text-white flex flex-col font-sans">
    <!-- Top Bar -->
    <header class="bg-slate-900 border-b border-slate-800 px-4 py-3 sticky top-0 z-30 shadow-xs">
      <div class="max-w-5xl lg:max-w-6xl mx-auto flex items-center justify-between gap-3">
        <div class="flex items-center gap-2.5 min-w-0">
          <AppLogo variant="satpam" size="md" theme="emerald" class="shrink-0" />
          <div class="min-w-0">
            <div class="flex items-center gap-2">
              <h1 class="font-extrabold text-sm sm:text-base tracking-tight text-white leading-tight truncate">
                Pos Satpam
              </h1>
              <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-semibold border border-emerald-500/30 shrink-0">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                Online
              </span>
            </div>
            <p class="text-xs text-slate-300 font-medium truncate mt-0.5">Pemeriksaan Gerbang • SMK N 2 Depok</p>
          </div>
        </div>

        <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
          <div class="hidden sm:block text-right">
            <p class="text-xs font-bold text-white leading-none truncate max-w-[150px]">{{ authStore.userName }}</p>
            <p class="text-xs text-slate-300 mt-0.5">Petugas Keamanan</p>
          </div>

          <button
            @click="showLogoutConfirm = true"
            class="shrink-0 text-xs bg-slate-800 hover:bg-slate-700 active:scale-95 px-2.5 sm:px-3 py-1.5 rounded-xl font-bold border border-slate-700 transition-all text-white flex items-center gap-1.5 shadow-sm"
          >
            <LogOut class="w-3.5 h-3.5" />
            <span class="hidden sm:inline">Keluar</span>
          </button>
        </div>
      </div>
    </header>

    <!-- Offline Alert Banner -->
    <div v-if="!isOnline" class="bg-rose-500/20 border-b border-rose-500/40 px-4 py-2 text-center">
      <div class="max-w-md mx-auto flex items-center justify-center gap-2 text-rose-300 text-xs font-semibold">
        <WifiOff class="w-4 h-4 shrink-0" />
        <span>Koneksi internet terputus. Mohon periksa jaringan internet untuk memindai surat izin.</span>
      </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
      <router-view v-slot="{ Component }">
        <transition name="page-fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <!-- Logout Confirm -->
    <ConfirmDialog
      :show="showLogoutConfirm"
      title="Konfirmasi Keluar"
      message="Apakah Anda yakin ingin keluar dari akun petugas satpam?"
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
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue'
import AppLogo from '@/components/ui/AppLogo.vue'
import { LogOut, WifiOff } from 'lucide-vue-next'

const authStore = useAuthStore()
const showLogoutConfirm = ref(false)
const isOnline = ref(typeof navigator !== 'undefined' ? navigator.onLine : true)

const handleOnline = () => { isOnline.value = true }
const handleOffline = () => { isOnline.value = false }

onMounted(() => {
  window.addEventListener('online', handleOnline)
  window.addEventListener('offline', handleOffline)
})

onUnmounted(() => {
  window.removeEventListener('online', handleOnline)
  window.removeEventListener('offline', handleOffline)
})

const handleLogout = () => {
  showLogoutConfirm.value = false
  authStore.logout()
}
</script>
