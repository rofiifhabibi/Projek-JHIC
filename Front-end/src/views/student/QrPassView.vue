<template>
  <div class="space-y-6">
    <!-- Active Ticket Card -->
    <div v-if="activePermit" class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
      <!-- Header -->
      <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white p-6 relative">
        <div class="flex items-center justify-between">
          <span class="text-xs font-mono tracking-widest text-slate-400 uppercase">TIKET PERIZINAN DIGITAL</span>
          <BaseBadge :status="activePermit.status" />
        </div>
        <h2 class="text-xl font-bold mt-2">
          {{ activePermit.type === 'TEMP' ? 'Izin Keluar Sementara' : 'Izin Pulang Sekolah' }}
        </h2>
        <p class="text-xs text-slate-400 mt-0.5">
          Guru Pengampu: <strong class="text-white">{{ activePermit.teacher?.name || 'Ahmad Dahlan, S.Pd.' }}</strong>
        </p>
      </div>

      <!-- Content -->
      <div class="p-6 space-y-6 text-center">
        <!-- PENDING State -->
        <div v-if="activePermit.status === 'PENDING'" class="py-8 space-y-3">
          <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto animate-bounce">
            <Clock class="w-8 h-8" />
          </div>
          <h3 class="text-lg font-bold text-slate-900">Menunggu Persetujuan Guru</h3>
          <p class="text-xs text-slate-500 max-w-xs mx-auto">
            Permohonan izin Anda sedang diverifikasi oleh guru pengampu. QR Code akan terbit otomatis setelah disetujui.
          </p>
          <div class="inline-flex items-center gap-2 text-xs font-mono text-amber-700 bg-amber-50 px-3 py-1.5 rounded-full">
            <RefreshCw class="w-3.5 h-3.5 animate-spin" />
            Memeriksa status otomatis...
          </div>
        </div>

        <!-- APPROVED / ACTIVE State: Render QR Code -->
        <div v-else-if="activePermit.qr_token" class="space-y-6">
          <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 inline-block shadow-inner">
            <qrcode-vue
              :value="activePermit.qr_token"
              :size="200"
              level="H"
              class="mx-auto"
              aria-label="QR Code Perizinan Digital Siswa"
            />
            <p class="text-[11px] font-mono text-slate-500 mt-3 uppercase tracking-wider">
              Token: {{ activePermit.qr_token.substring(0, 18) }}...
            </p>
          </div>

          <!-- Countdown (ONLY FOR TEMP PERMITS WITH EXPIRY TIME) -->
          <div v-if="activePermit.type === 'TEMP' && formattedCountdown" class="bg-[#E8EFEA] p-4 rounded-2xl border border-[#355245]/20 max-w-xs mx-auto space-y-1">
            <span class="text-[11px] font-bold uppercase tracking-wider text-[#355245]">Sisa Batas Waktu Kembali</span>
            <div class="text-3xl font-black font-mono tracking-tight text-slate-900">
              {{ formattedCountdown }}
            </div>
          </div>

          <div v-else-if="activePermit.type === 'EXIT_SCHOOL'" class="bg-slate-100 p-4 rounded-2xl text-xs text-slate-600 font-medium max-w-xs mx-auto">
            🏠 Tunjukkan QR Code ini ke petugas satpam di gerbang utama untuk izin pulang.
          </div>
        </div>

        <!-- Detail Table -->
        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-left text-xs space-y-2">
          <div class="flex justify-between border-b border-slate-200/60 pb-2">
            <span class="text-slate-500">Nama Siswa:</span>
            <span class="font-bold text-slate-900">{{ activePermit.student?.name || authStore.userName }}</span>
          </div>
          <div class="flex justify-between border-b border-slate-200/60 pb-2">
            <span class="text-slate-500">Alasan Izin:</span>
            <span class="font-bold text-slate-900 max-w-[200px] text-right">{{ activePermit.reason || '-' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500">Durasi Disetujui:</span>
            <span class="font-bold text-slate-900">{{ activePermit.duration_minutes || 30 }} Menit</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <EmptyState
      v-else
      title="Tidak Ada Izin Aktif"
      description="Anda tidak memiliki surat izin aktif atau dalam proses pengajuan saat ini."
    >
      <template #action>
        <router-link to="/student/permit/create">
          <BaseButton variant="primary" size="md">
            Buat Izin Baru
          </BaseButton>
        </router-link>
      </template>
    </EmptyState>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import QrcodeVue from 'qrcode.vue'
import { useAuthStore } from '@/stores/auth'
import { usePermitStore } from '@/stores/permit'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import { Clock, RefreshCw } from 'lucide-vue-next'

const authStore = useAuthStore()
const permitStore = usePermitStore()

const activePermit = computed(() => permitStore.activePermit)

const remainingSeconds = ref(0)
let timerInterval = null
let pollingInterval = null

const updateCountdown = () => {
  if (!activePermit.value || !activePermit.value.expiry_time) return
  const expiry = new Date(activePermit.value.expiry_time).getTime()
  const now = new Date().getTime()
  const diff = Math.floor((expiry - now) / 1000)
  remainingSeconds.value = diff > 0 ? diff : 0
}

const formattedCountdown = computed(() => {
  if (remainingSeconds.value <= 0) return '00:00'
  const minutes = Math.floor(remainingSeconds.value / 60)
  const seconds = remainingSeconds.value % 60
  return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`
})

onMounted(async () => {
  await permitStore.fetchActivePermit()
  updateCountdown()

  timerInterval = setInterval(updateCountdown, 1000)

  // Polling check every 10 seconds
  pollingInterval = setInterval(async () => {
    await permitStore.fetchActivePermit()
  }, 10000)
})

onUnmounted(() => {
  if (timerInterval) clearInterval(timerInterval)
  if (pollingInterval) clearInterval(pollingInterval)
})
</script>
