<template>
  <div class="space-y-6">
    <!-- Active Ticket Card -->
    <div v-if="activePermit" class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
      <!-- Header -->
      <div class="bg-gradient-to-r from-[#355245] to-[#273e34] text-white p-4 sm:p-6 relative">
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg bg-white p-1 flex items-center justify-center shadow-xs shrink-0">
              <img src="/logos/studentcare-icon.png" alt="SC" class="w-5 h-5 object-contain" />
            </div>
            <div>
              <span class="text-[11px] font-bold tracking-wider text-emerald-200 uppercase block leading-none">SURAT IZIN KELUAR RESMI</span>
              <span class="text-[10px] text-[#E8EFEA]/80 font-medium">SMKN 2 Depok Sleman</span>
            </div>
          </div>
          <BaseBadge :status="isTimeExpired ? 'OVERDUE' : activePermit.status" />
        </div>
        <h2 class="text-lg sm:text-xl font-bold">
          {{ activePermit.type === 'TEMP' ? 'Izin Keluar Sementara' : 'Izin Pulang Sekolah' }}
        </h2>
        <p class="text-xs text-[#E8EFEA]/80 mt-0.5">
          Guru yang Mengajar: <strong class="text-white">{{ activePermit.teacher?.name || 'Guru Pengajar' }}</strong>
        </p>
      </div>

      <!-- Content -->
      <div class="p-4 sm:p-6 space-y-5 sm:space-y-6 text-center">
        <!-- 0. ALPHA State: Izin Dibatalkan & Dinyatakan Alpha -->
        <div v-if="activePermit.status === 'ALPHA'" class="py-6 sm:py-8 space-y-4 max-w-sm mx-auto">
          <div class="w-16 h-16 rounded-2xl bg-rose-100 text-rose-600 border border-rose-200 flex items-center justify-center mx-auto shadow-xs">
            <AlertOctagon class="w-9 h-9" :stroke-width="1.75" />
          </div>
          <div class="space-y-1.5">
            <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 text-xs font-bold uppercase tracking-wider">
              Izin Dibatalkan
            </span>
            <h3 class="text-lg font-black text-rose-950">Status: Ditandai Alpha</h3>
            <p class="text-xs text-rose-800 leading-relaxed">
              Guru pengajar (<strong>{{ activePermit.teacher?.name || 'Guru' }}</strong>) menandai izin ini sebagai <strong>Alpha</strong> karena kamu belum kembali ke kelas melebihi batas waktu.
            </p>
          </div>
          <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-left text-xs text-rose-900 space-y-1">
            <p class="font-bold flex items-center gap-1.5 text-rose-950">
              <ShieldAlert class="w-4 h-4 text-rose-600 shrink-0" />
              Kode QR Tidak Berlaku
            </p>
            <p class="text-xs text-rose-700">Kode QR ini sudah tidak bisa digunakan di pos satpam. Segera temui guru yang bersangkutan atau Guru BK.</p>
          </div>
          <div class="pt-2 flex flex-col gap-2">
            <BaseButton
              :to="'/student/report/create?type=alpha&request_id=' + activePermit.request_id"
              variant="danger"
              size="md"
              block
            >
              <template #icon-left><HeartHandshake class="w-4 h-4" /></template>
              Kirim Klarifikasi ke BK
            </BaseButton>
            <BaseButton to="/student/dashboard" variant="outline" size="sm" block>
              Kembali ke Beranda
            </BaseButton>
          </div>
        </div>

        <!-- 0B. REJECTED State: Izin Ditolak Guru -->
        <div v-else-if="activePermit.status === 'REJECTED'" class="py-6 sm:py-8 space-y-4 max-w-sm mx-auto">
          <div class="w-16 h-16 rounded-2xl bg-rose-100 text-rose-600 border border-rose-200 flex items-center justify-center mx-auto shadow-xs">
            <XCircle class="w-9 h-9 text-rose-600" :stroke-width="1.75" />
          </div>
          <div class="space-y-1.5">
            <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 text-xs font-bold uppercase tracking-wider">
              Izin Ditolak
            </span>
            <h3 class="text-lg font-black text-rose-950">Izin Tidak Disetujui</h3>
            <p class="text-xs text-rose-800 leading-relaxed">
              Permohonan izin kamu tidak disetujui oleh <strong>{{ activePermit.teacher?.name || 'Guru Pengajar' }}</strong>. Silakan tetap berada di kelas untuk mengikuti pelajaran.
            </p>
          </div>
          <div class="pt-2 flex flex-col sm:flex-row gap-2">
            <BaseButton to="/student/permit/create" variant="primary" size="md" block class="flex-1">
              <template #icon-left><FilePlus class="w-4 h-4" /></template>
              Ajukan Izin Baru
            </BaseButton>
            <BaseButton to="/student/dashboard" variant="outline" size="md" block class="flex-1">
              Kembali ke Beranda
            </BaseButton>
          </div>
        </div>

        <!-- 0C. CANCELLED State: Izin Dibatalkan -->
        <div v-else-if="activePermit.status === 'CANCELLED'" class="py-6 sm:py-8 space-y-4 max-w-sm mx-auto">
          <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-600 border border-slate-200 flex items-center justify-center mx-auto shadow-xs">
            <AlertTriangle class="w-9 h-9 text-slate-500" :stroke-width="1.75" />
          </div>
          <div class="space-y-1.5">
            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-bold uppercase tracking-wider">
              Izin Dibatalkan
            </span>
            <h3 class="text-lg font-black text-slate-900">Permohonan Telah Dibatalkan</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
              Kamu telah membatalkan permohonan izin ini. Kamu dapat membuat pengajuan baru jika diperlukan.
            </p>
          </div>
          <div class="pt-2 flex flex-col sm:flex-row gap-2">
            <BaseButton to="/student/permit/create" variant="primary" size="md" block class="flex-1">
              <template #icon-left><FilePlus class="w-4 h-4" /></template>
              Buat Izin Baru
            </BaseButton>
            <BaseButton to="/student/dashboard" variant="outline" size="md" block class="flex-1">
              Kembali ke Beranda
            </BaseButton>
          </div>
        </div>

        <!-- 1. PENDING State: Menunggu Approval Guru -->
        <div v-else-if="activePermit.status === 'PENDING'" class="py-6 sm:py-8 space-y-3">
          <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-amber-100 text-amber-600 border border-amber-200 flex items-center justify-center mx-auto shadow-xs">
            <Clock class="w-7 h-7 sm:w-8 sm:h-8" :stroke-width="1.75" />
          </div>
          <h3 class="text-base sm:text-lg font-bold text-slate-900">Menunggu Persetujuan Guru</h3>
          <p class="text-xs text-slate-600 max-w-xs mx-auto">
            Permohonan izin kamu sedang menunggu konfirmasi guru di kelas. Kode QR akan langsung muncul setelah disetujui.
          </p>
          <div class="inline-flex items-center gap-2 text-xs text-amber-700 bg-amber-50 px-3 py-1.5 rounded-full">
            <RefreshCw class="w-3.5 h-3.5 animate-spin" />
            Memeriksa persetujuan guru...
          </div>
          <div class="pt-3">
            <BaseButton
              variant="outline"
              size="sm"
              class="text-rose-600 border-rose-200 hover:bg-rose-50"
              :loading="isCancelling"
              @click="showCancelConfirm = true"
            >
              Batalkan Pengajuan Izin
            </BaseButton>
          </div>
        </div>

        <!-- 2. QR Code State (Sudah disetujui Guru) -->
        <div v-else-if="activePermit.qr_token" class="space-y-5 sm:space-y-6">
          <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 inline-block shadow-sm max-w-full">
            <qrcode-vue
              :value="activePermit.qr_token"
              :size="190"
              level="H"
              class="mx-auto"
              aria-label="QR Code Surat Izin Siswa"
            />
            <div class="mt-4 pt-3 border-t border-slate-100 space-y-2.5">
              <div class="flex items-center justify-center gap-1.5 text-xs text-slate-500 font-medium">
                <span>ID Surat Izin:</span>
                <span class="font-bold text-slate-800 font-mono">#{{ activePermit.request_id }}</span>
              </div>

              <!-- Cadangan Kode Manual untuk Pos Satpam -->
              <div class="bg-slate-50 rounded-xl p-3 border border-slate-200/90 max-w-xs mx-auto space-y-1.5 text-center">
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider block">
                  Kode Izin Manual (Cadangan)
                </span>
                <div class="flex items-center justify-center gap-2">
                  <span class="font-mono text-xs sm:text-sm font-black text-[#355245] tracking-wider select-all break-all bg-white px-2.5 py-1 rounded-lg border border-slate-200 shadow-xs">
                    {{ activePermit.qr_token }}
                  </span>
                  <button
                    type="button"
                    @click="copyCode(activePermit.qr_token)"
                    class="p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-200 transition shrink-0 active:scale-95 cursor-pointer touch-manipulation"
                    title="Salin Kode Izin"
                    aria-label="Salin Kode Izin"
                  >
                    <Copy class="w-4 h-4" />
                  </button>
                </div>
                <p class="text-xs text-slate-500 leading-tight">
                  Tunjukkan kode atau sebutkan ID di atas ke petugas pos satpam jika kamera pemindai QR mengalami gangguan.
                </p>
              </div>
            </div>
          </div>

          <!-- KONDISI A: Izin TEMP & SUDAH DISCAN SATPAM (Countdown Aktif) -->
          <div
            v-if="activePermit.type === 'TEMP' && activePermit.expiry_time && !isTimeExpired && activePermit.status !== 'OVERDUE'"
            class="bg-[#E8EFEA] p-5 rounded-2xl border border-[#355245]/20 max-w-xs mx-auto space-y-2 shadow-xs"
          >
            <div class="flex items-center justify-center gap-1.5 text-xs font-extrabold uppercase tracking-wider text-[#355245]">
              <CheckCircle class="w-4 h-4 text-emerald-600" />
              Izin Aktif — Sedang di Luar
            </div>
            <div class="text-4xl font-black font-mono tracking-tight text-slate-900">
              {{ formattedCountdown }}
            </div>
            <span class="text-xs text-[#355245] font-semibold block">
              Sisa Waktu Izin
            </span>
            <p class="text-xs text-slate-500 pt-1.5 border-t border-[#355245]/15">
              Tunjukkan QR ini ke Satpam saat tiba di gerbang, lalu segera lapor ke guru di kelas.
            </p>
          </div>

          <!-- KONDISI B: Izin TEMP & BELUM DISCAN SATPAM (Status APPROVED atau Belum ada expiry_time) -->
          <div
            v-else-if="activePermit.type === 'TEMP' && (!activePermit.expiry_time || activePermit.status === 'APPROVED')"
            class="bg-emerald-50 p-4 rounded-2xl border border-emerald-200 text-xs text-emerald-900 max-w-xs mx-auto space-y-2"
          >
            <div class="flex items-center justify-center gap-2 font-bold text-emerald-900">
              <CheckCircle class="w-4 h-4 text-emerald-600" />
              Izin Disetujui! Siap Menuju Gerbang
            </div>
            <p class="text-xs text-emerald-700 leading-relaxed">
              Tunjukkan kode QR ini ke Satpam di gerbang sekolah saat hendak keluar. Batas waktu izin (<strong>{{ activePermit.duration_minutes || 30 }} Menit</strong>) akan mulai berjalan setelah di-scan oleh Satpam.
            </p>
            <div class="pt-1">
              <button
                type="button"
                @click="showCancelConfirm = true"
                class="text-xs text-rose-600 hover:text-rose-800 font-semibold underline cursor-pointer touch-manipulation"
              >
                Batal keluar? Batalkan izin ini
              </button>
            </div>
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
            <p class="text-xs text-rose-700 leading-relaxed">
              Batas waktu izin kamu sudah habis. Segera kembali ke ruang kelas dan laporkan ke guru pengajar.
            </p>
          </div>

          <!-- KONDISI D: Izin Pulang Sekolah (EXIT_SCHOOL) -->
          <div
            v-else-if="activePermit.type === 'EXIT_SCHOOL'"
            class="bg-slate-100 p-4 rounded-2xl text-xs text-slate-600 font-medium max-w-xs mx-auto space-y-1"
          >
            <p class="font-bold text-slate-800">Izin Pulang Sekolah</p>
            <p class="text-xs">Tunjukkan kode QR ini ke petugas satpam di gerbang saat hendak meninggalkan sekolah.</p>
          </div>

          <!-- Manual Refresh Button -->
          <div class="pt-1">
            <button
              type="button"
              @click="refreshPass"
              class="inline-flex items-center gap-1.5 text-xs text-slate-600 hover:text-slate-900 transition font-medium py-1.5 px-3.5 rounded-full hover:bg-slate-100 border border-slate-200 cursor-pointer touch-manipulation active:scale-95"
            >
              <RefreshCw class="w-3.5 h-3.5" :class="{ 'animate-spin': isRefreshing }" />
              Perbarui Status
            </button>
          </div>
        </div>

        <!-- Detail Table -->
        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-left text-xs space-y-2.5">
          <div class="flex justify-between border-b border-slate-200/60 pb-2">
            <span class="text-slate-500">Nama Siswa:</span>
            <span class="font-bold text-slate-900">{{ activePermit.student?.name || authStore.userName }}</span>
          </div>
          <div class="flex justify-between border-b border-slate-200/60 pb-2">
            <span class="text-slate-500">Kelas:</span>
            <span class="font-bold text-slate-900">{{ activePermit.student?.class_name || '-' }}</span>
          </div>
          <div class="flex justify-between border-b border-slate-200/60 pb-2">
            <span class="text-slate-500">Alasan Izin:</span>
            <span class="font-bold text-slate-900 max-w-[200px] text-right">{{ activePermit.reason || '-' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500">Batas Waktu:</span>
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
        <BaseButton to="/student/permit/create" variant="primary" size="md">
          Buat Izin Baru
        </BaseButton>
      </template>
    </EmptyState>

    <!-- Confirm Cancel Dialog -->
    <ConfirmDialog
      :show="showCancelConfirm"
      title="Batalkan Permohonan Izin"
      message="Apakah Anda yakin ingin membatalkan permohonan izin ini? Anda dapat mengajukan izin baru setelahnya."
      confirm-text="Ya, Batalkan Izin"
      variant="danger"
      @confirm="handleCancelPermit"
      @cancel="showCancelConfirm = false"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import QrcodeVue from 'qrcode.vue'
import { useAuthStore } from '@/stores/auth'
import { usePermitStore } from '@/stores/permit'
import { useToast } from '@/composables/useToast'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue'
import { Clock, RefreshCw, CheckCircle, ShieldAlert, AlertTriangle, AlertOctagon, HeartHandshake, XCircle, FilePlus, Copy } from 'lucide-vue-next'

const authStore = useAuthStore()
const permitStore = usePermitStore()
const toast = useToast()

const activePermit = computed(() => permitStore.activePermit)

let lastCopyTime = 0
const copyCode = async (code) => {
  if (!code) return
  const now = Date.now()
  if (now - lastCopyTime < 1500) return
  lastCopyTime = now
  try {
    if (navigator?.clipboard?.writeText) {
      await navigator.clipboard.writeText(code)
      toast.success('Kode izin berhasil disalin ke clipboard!')
    }
  } catch (err) {
    toast.info(`Kode Izin: ${code}`)
  }
}

const remainingSeconds = ref(0)
const isRefreshing = ref(false)
const showCancelConfirm = ref(false)
const isCancelling = ref(false)
let timerInterval = null
let pollingInterval = null

const handleCancelPermit = async () => {
  if (!activePermit.value || isCancelling.value) return
  isCancelling.value = true
  try {
    await permitStore.cancelPermit(activePermit.value.request_id)
    toast.success('Permohonan izin berhasil dibatalkan.')
    showCancelConfirm.value = false
  } catch (err) {
    toast.error(err.response?.data?.message || 'Gagal membatalkan izin.')
  } finally {
    isCancelling.value = false
  }
}

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

const startPolling = () => {
  stopPolling()
  // Tambahkan random jitter (0-3 detik) agar ratusan request klien tidak menabrak server di milidetik yang sama
  const jitter = Math.floor(Math.random() * 3000)
  pollingInterval = setInterval(async () => {
    await permitStore.fetchActivePermit()
    updateCountdown()
  }, 15000 + jitter)
}

const stopPolling = () => {
  if (pollingInterval) {
    clearInterval(pollingInterval)
    pollingInterval = null
  }
}

const handleVisibilityChange = async () => {
  if (document.hidden) {
    // Layar HP mati / tab diminimize: hentikan polling untuk hemat sumber daya server
    stopPolling()
  } else {
    // Kembali aktif: langsung sync data terbaru lalu lanjutkan interval polling
    await permitStore.fetchActivePermit()
    updateCountdown()
    startPolling()
  }
}

onMounted(async () => {
  await permitStore.fetchActivePermit()
  updateCountdown()

  timerInterval = setInterval(updateCountdown, 1000)
  startPolling()

  document.addEventListener('visibilitychange', handleVisibilityChange)
})

onUnmounted(() => {
  if (timerInterval) clearInterval(timerInterval)
  stopPolling()
  document.removeEventListener('visibilitychange', handleVisibilityChange)
})
</script>