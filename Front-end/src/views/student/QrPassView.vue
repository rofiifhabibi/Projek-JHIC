<template>
  <div class="space-y-6">
    <!-- Active Ticket Card -->
    <div v-if="activePermit" class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
      <!-- Header -->
      <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white p-6 relative">
        <div class="flex items-center justify-between">
          <span class="text-xs font-mono tracking-widest text-slate-400 uppercase">TIKET PERIZINAN DIGITAL</span>
          <BaseBadge :status="isTimeExpired ? 'OVERDUE' : activePermit.status" />
        </div>
        <h2 class="text-xl font-bold mt-2">
          {{ activePermit.type === 'TEMP' ? 'Izin Keluar Sementara' : 'Izin Pulang Sekolah' }}
        </h2>
        <p class="text-xs text-slate-400 mt-0.5">
          Guru Pengampu: <strong class="text-white">{{ activePermit.teacher?.name || 'Guru Pengampu' }}</strong>
        </p>
      </div>

      <!-- Content -->
      <div class="p-6 space-y-6 text-center">
        <!-- 1. PENDING State: Menunggu Approval Guru -->
        <div v-if="activePermit.status === 'PENDING'" class="py-8 space-y-3">
          <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto animate-bounce">
            <Clock class="w-8 h-8" />
          </div>
          <h3 class="text-lg font-bold text-slate-900">Menunggu Persetujuan Guru</h3>
          <p class="text-xs text-slate-500 max-w-xs mx-auto">
            Permohonan izin Anda sedang diverifikasi oleh guru pengampu kelas. QR Code akan terbit otomatis setelah disetujui.
          </p>
          <div class="inline-flex items-center gap-2 text-xs font-mono text-amber-700 bg-amber-50 px-3 py-1.5 rounded-full">
            <RefreshCw class="w-3.5 h-3.5 animate-spin" />
            Memeriksa status otomatis...
          </div>
        </div>

        <!-- 2. QR Code State (Sudah disetujui Guru) -->
        <div v-else-if="activePermit.qr_token" class="space-y-6">
          <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 inline-block shadow-inner">
            <qrcode-vue
              :value="activePermit.qr_token"
              :size="210"
              level="H"
              class="mx-auto"
              aria-label="QR Code Perizinan Digital Siswa"
            />
            <p class="text-[11px] font-mono text-slate-500 mt-3 uppercase tracking-wider">
              Token: {{ activePermit.qr_token.substring(0, 18) }}...
            </p>
          </div>

          <!-- KONDISI A: Izin TEMP & SUDAH DISCAN SATPAM (Countdown Aktif) -->
          <div
            v-if="activePermit.type === 'TEMP' && activePermit.expiry_time && !isTimeExpired && activePermit.status !== 'OVERDUE'"
            class="bg-[#E8EFEA] p-5 rounded-2xl border border-[#355245]/20 max-w-xs mx-auto space-y-2 shadow-xs"
          >
            <div class="flex items-center justify-center gap-1.5 text-[11px] font-extrabold uppercase tracking-wider text-[#355245]">
              <CheckCircle2 class="w-4 h-4 text-emerald-600" />
              Tervalidasi Satpam di Gerbang
            </div>
            <div class="text-4xl font-black font-mono tracking-tight text-slate-900">
              {{ formattedCountdown }}
            </div>
            <span class="text-[11px] text-[#355245] font-semibold block">
              Sisa Batas Waktu Kembali ke Kelas
            </span>
          </div>

          <!-- KONDISI B: Izin TEMP & BELUM DISCAN SATPAM (Menunggu di Pos Gerbang) -->
          <div
            v-else-if="activePermit.type === 'TEMP' && !activePermit.expiry_time"
            class="bg-amber-50 p-4 rounded-2xl border border-amber-200 text-xs text-amber-900 max-w-xs mx-auto space-y-2"
          >
            <div class="flex items-center justify-center gap-2 font-bold text-amber-900">
              <ShieldAlert class="w-4 h-4 text-amber-600" />
              Menunggu Scan Satpam di Gerbang
            </div>
            <p class="text-[11px] text-amber-700 leading-relaxed">
              Tunjukkan QR Code ini kepada Satpam di pos gerbang sekolah. Hitung mundur durasi izin (<strong>{{ activePermit.duration_minutes || 30 }} Menit</strong>) akan dimulai otomatis setelah berhasil dipindai oleh Satpam.
            </p>
          </div>

          <!-- KONDISI C: Status OVERDUE / Waktu Habis -->
          <div
            v-else-if="activePermit.type === 'TEMP' && (isTimeExpired || activePermit.status === 'OVERDUE')"
            class="bg-rose-50 p-5 rounded-2xl border border-rose-200 text-xs text-rose-900 max-w-xs mx-auto space-y-2"
          >
            <div class="flex items-center justify-center gap-1.5 font-extrabold text-rose-800 text-sm">
              <AlertTriangle class="w-5 h-5 text-rose-600" />
              Waktu Izin Telah Habis (00:00)
            </div>
            <p class="text-[11px] text-rose-700 leading-relaxed">
              Batas waktu izin Anda telah berakhir. Harap segera kembali ke ruang kelas dan laporkan kepulangan Anda kepada Guru Pengampu untuk menyelesaikan izin.
            </p>
          </div>

          <!-- KONDISI D: Izin Pulang Sekolah (EXIT_SCHOOL) -->
          <div
            v-else-if="activePermit.type === 'EXIT_SCHOOL'"
            class="bg-slate-100 p-4 rounded-2xl text-xs text-slate-600 font-medium max-w-xs mx-auto space-y-1"
          >
            <p class="font-bold text-slate-800">Izin Pulang Sekolah</p>
            <p class="text-[11px]">Tunjukkan QR Code ini ke petugas satpam di gerbang utama saat hendak meninggalkan sekolah.</p>
          </div>

          <!-- Manual Refresh Button -->
          <div class="pt-1">
            <button
              type="button"
              @click="refreshPass"
              class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 transition font-medium py-1.5 px-3.5 rounded-full hover:bg-slate-100 border border-slate-200"
            >
              <RefreshCw class="w-3.5 h-3.5" :class="{ 'animate-spin': isRefreshing }" />
              Perbarui Status Tiket
            </button>
          </div>
        </div>

        <!-- Detail Table -->
        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-left text-xs space-y-2">
          <div class="flex justify-between border-b border-slate-200/60 pb-2">
            <span class="text-slate-500">Nama Siswa:</span>
            <span class="font-bold text-slate-900">{{ activePermit.student?.name || authStore.userName }}</span>
          </div>
          <div class="flex justify-between border-b border-slate-200/60 pb-2">
            <span class="text-slate-500">Kelas / Rombel:</span>
            <span class="font-bold text-slate-900">{{ activePermit.student?.class_name || '-' }}</span>
          </div>
          <div class="flex justify-between border-b border-slate-200/60 pb-2">
            <span class="text-slate-500">Alasan Izin:</span>
            <span class="font-bold text-slate-900 max-w-[200px] text-right">{{ activePermit.reason || '-' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500">Alokasi Durasi:</span>
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
import { Clock, RefreshCw, CheckCircle2, ShieldAlert, AlertTriangle } from 'lucide-vue-next'

const authStore = useAuthStore()
const permitStore = usePermitStore()

const activePermit = computed(() => permitStore.activePermit)

const remainingSeconds = ref(0)
const isRefreshing = ref(false)
let timerInterval = null
let pollingInterval = null

// Robust parsing for UTC timestamps across all browsers and devices
const parseExpiryTime = (dateStr) => {
  if (!dateStr) return 0
  if (typeof dateStr === 'string') {
    let s = dateStr.trim()
    // If format is like "2026-09-25 15:04:20" (no timezone designator), treat as UTC
    if (!s.endsWith('Z') && !/[+-]\d{2}(:\d{2})?$/.test(s)) {
      s = s.replace(' ', 'T') + 'Z'
    }
    const parsed = new Date(s).getTime()
    return isNaN(parsed) ? 0 : parsed
  }
  return new Date(dateStr).getTime()
}

const updateCountdown = () => {
  if (!activePermit.value || !activePermit.value.expiry_time) {
    remainingSeconds.value = 0
    return
  }
  const expiry = parseExpiryTime(activePermit.value.expiry_time)
  if (!expiry) {
    remainingSeconds.value = 0
    return
  }
  const now = Date.now()
  const diff = Math.floor((expiry - now) / 1000)
  remainingSeconds.value = diff > 0 ? diff : 0
}

const isTimeExpired = computed(() => {
  if (!activePermit.value || !activePermit.value.expiry_time) return false
  const expiry = parseExpiryTime(activePermit.value.expiry_time)
  return expiry > 0 && Date.now() >= expiry
})

const formattedCountdown = computed(() => {
  if (remainingSeconds.value <= 0) return '00:00'
  const minutes = Math.floor(remainingSeconds.value / 60)
  const seconds = remainingSeconds.value % 60
  return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`
})

const refreshPass = async () => {
  isRefreshing.value = true
  try {
    await permitStore.fetchActivePermit()
    updateCountdown()
  } finally {
    setTimeout(() => {
      isRefreshing.value = false
    }, 400)
  }
}

onMounted(async () => {
  await permitStore.fetchActivePermit()
  updateCountdown()

  timerInterval = setInterval(updateCountdown, 1000)

  // Polling sync setiap 3 detik
  pollingInterval = setInterval(async () => {
    await permitStore.fetchActivePermit()
    updateCountdown()
  }, 3000)
})

onUnmounted(() => {
  if (timerInterval) clearInterval(timerInterval)
  if (pollingInterval) clearInterval(pollingInterval)
})
</script>