<template>
  <div class="min-h-screen bg-[#F4F7F4] text-slate-800 flex flex-col font-sans">
    <!-- Top Bar -->
    <header class="bg-[#355245] text-white px-4 py-3 sticky top-0 z-30 shadow-xs border-b border-white/10">
      <div class="max-w-5xl lg:max-w-6xl mx-auto flex items-center justify-between gap-3">
        <router-link to="/satpam/scanner" class="flex items-center gap-2.5 min-w-0 group">
          <AppLogo variant="satpam" size="md" theme="white-trans" class="shrink-0 group-hover:scale-105 transition" />
          <div class="min-w-0">
            <div class="flex items-center gap-2">
              <h1 class="font-extrabold text-sm sm:text-base tracking-tight text-white group-hover:text-emerald-100 transition leading-tight truncate">
                Satpam
              </h1>
              <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-white/15 text-[#E8EFEA] text-xs font-medium border border-white/20 shrink-0">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-300"></span>
                Online
              </span>
            </div>
            <p class="text-xs text-[#E8EFEA]/80 font-medium truncate mt-0.5">
              <span class="sm:hidden">Pemeriksaan Gerbang</span>
              <span class="hidden sm:inline">Pemeriksaan Gerbang • SMKN 2 Depok Sleman</span>
            </p>
          </div>
        </router-link>

        <div class="flex items-center gap-2 sm:gap-2.5 shrink-0">
          <div class="hidden sm:block text-right">
            <p class="text-xs font-bold text-white leading-none truncate max-w-[150px]">{{ authStore.userName }}</p>
            <p class="text-xs text-[#E8EFEA]/80 mt-0.5">Petugas Keamanan</p>
          </div>

          <NotificationToggle variant="dark" />

          <button
            type="button"
            @click="handleReload"
            :disabled="isReloading"
            title="Segarkan Halaman"
            class="shrink-0 text-xs bg-white/10 hover:bg-white/20 active:scale-95 px-2.5 sm:px-3 py-1.5 rounded-xl font-bold border border-white/15 transition-all text-white flex items-center gap-1.5 shadow-xs cursor-pointer touch-manipulation disabled:opacity-50"
          >
            <RefreshCw class="w-3.5 h-3.5" :class="{ 'animate-spin': isReloading }" />
            <span class="hidden sm:inline">Segarkan</span>
          </button>

          <button
            type="button"
            @click="showLogoutConfirm = true"
            class="shrink-0 text-xs bg-white/10 hover:bg-white/20 active:scale-95 px-3 py-1.5 rounded-xl font-bold border border-white/15 transition-all text-white flex items-center gap-1.5 shadow-xs cursor-pointer touch-manipulation"
          >
            <LogOut class="w-3.5 h-3.5" />
            <span class="hidden sm:inline">Keluar</span>
          </button>
        </div>
      </div>
    </header>

    <!-- Offline Alert Banner -->
    <div v-if="!isOnline" class="bg-rose-50 border-b border-rose-200 px-4 py-2.5 text-center">
      <div class="max-w-md mx-auto flex items-center justify-center gap-2 text-rose-700 text-xs font-medium">
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
import NotificationToggle from '@/components/ui/NotificationToggle.vue'
import { LogOut, WifiOff, RefreshCw } from 'lucide-vue-next'

const authStore = useAuthStore()
const showLogoutConfirm = ref(false)
const isOnline = ref(typeof navigator !== 'undefined' ? navigator.onLine : true)
const isReloading = ref(false)

const handleReload = () => {
  if (isReloading.value) return
  isReloading.value = true
  window.location.reload()
}

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
