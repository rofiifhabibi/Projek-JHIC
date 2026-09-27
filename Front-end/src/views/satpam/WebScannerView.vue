<template>
  <div class="flex-1 relative flex flex-col items-center justify-center p-3 sm:p-6 max-w-md mx-auto w-full space-y-4">
      
      <!-- Camera Reader Container -->
      <div class="w-full bg-slate-900 rounded-2xl border border-slate-800 p-4 sm:p-5 flex flex-col items-center shadow-sm space-y-4">
        <!-- Top bar of scanner card -->
        <div class="flex items-center justify-between w-full border-b border-slate-800/80 pb-3">
          <div class="flex items-center gap-2">
            <Camera class="w-4 h-4 text-slate-300 shrink-0" />
            <span class="text-xs font-bold text-slate-200">Pemindai Kamera</span>
          </div>
          <button
            @click="toggleCamera"
            class="whitespace-nowrap text-xs font-bold px-3 py-1.5 rounded-xl border transition-all active:scale-95 flex items-center gap-1.5"
            :class="isCameraActive ? 'border-rose-500/50 bg-rose-500/15 text-rose-300 hover:bg-rose-500/25' : 'border-emerald-500/50 bg-emerald-500/15 text-emerald-300 hover:bg-emerald-500/25'"
          >
            <span class="w-1.5 h-1.5 rounded-full" :class="isCameraActive ? 'bg-rose-400' : 'bg-emerald-400'"></span>
            {{ isCameraActive ? 'Matikan Kamera' : 'Nyalakan Kamera' }}
          </button>
        </div>

        <!-- Video Reader Element with Viewfinder Styling -->
        <div class="w-full min-h-[230px] bg-slate-950 rounded-2xl border border-slate-800 overflow-hidden relative flex items-center justify-center">
          <div id="qr-reader" class="w-full" :class="{ 'hidden': !isCameraActive }"></div>

          <!-- Processing Overlay -->
          <div
            v-if="isProcessing"
            class="absolute inset-0 bg-slate-950/80 backdrop-blur-xs flex flex-col items-center justify-center p-4 text-center space-y-2 z-10 pointer-events-none"
          >
            <div class="w-7 h-7 border-2 border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-xs font-bold text-emerald-400">Memeriksa Surat Izin...</p>
          </div>

          <!-- Cooldown indicator -->
          <div
            v-else-if="isCooldown"
            class="absolute bottom-3 inset-x-4 bg-slate-900/90 border border-slate-700/80 rounded-xl py-1.5 px-3 text-center text-xs text-amber-300 z-10 pointer-events-none shadow"
          >
            Kamera dijeda sejenak...
          </div>

          <!-- Idle Placeholder Viewfinder -->
          <div
            v-if="!isCameraActive"
            class="absolute inset-0 flex flex-col items-center justify-center p-5 text-center space-y-2.5 pointer-events-none"
          >
            <div class="w-12 h-12 rounded-2xl bg-slate-900/90 border border-slate-800 flex items-center justify-center mx-auto text-slate-300 shadow-sm">
              <QrCode class="w-6 h-6" />
            </div>
            <div>
              <p class="text-xs font-bold text-slate-200">Arahkan QR ke Kamera</p>
              <p class="text-xs text-slate-300 mt-1 max-w-[240px] mx-auto leading-relaxed">
                Nyalakan kamera untuk memindai surat izin siswa, atau gunakan tombol simulasi di bawah.
              </p>
            </div>
          </div>
        </div>

        <!-- Quick Simulation Selector -->
        <div class="w-full space-y-2 bg-slate-950/60 p-3.5 rounded-xl border border-slate-800/70">
          <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-300 px-0.5">
            <span>Simulasi Cepat</span>
            <span class="text-emerald-400 font-medium text-xs">Pilih Siswa</span>
          </div>

          <div class="grid grid-cols-1 gap-1.5">
            <!-- Skenario 1: Siti (TEMP Normal) -->
            <button
              @click="scanToken('QR-ACTIVE-XII-RPL1-002')"
              :disabled="isProcessing"
              class="w-full bg-slate-900 hover:bg-slate-800/90 border border-slate-800 hover:border-emerald-500/50 p-2.5 rounded-xl transition-all text-left flex items-center justify-between gap-2 group active:scale-98 disabled:opacity-50 disabled:pointer-events-none"
            >
              <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-7 h-7 rounded-lg bg-emerald-500/15 text-emerald-400 flex items-center justify-center font-bold text-xs border border-emerald-500/20 shrink-0">
                  SR
                </div>
                <div class="min-w-0">
                  <div class="text-xs font-bold text-slate-200 group-hover:text-emerald-300 transition-colors truncate">
                    Siti Rahmawati
                  </div>
                  <div class="text-xs text-slate-300 truncate">XII RPL 1 • Izin Sementara UKS</div>
                </div>
              </div>
              <span class="shrink-0 text-xs font-bold px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                Keluar
              </span>
            </button>

            <!-- Skenario 2: Andi (Overdue) -->
            <button
              @click="scanToken('QR-OVERDUE-XII-TKJ2-003')"
              :disabled="isProcessing"
              class="w-full bg-slate-900 hover:bg-slate-800/90 border border-slate-800 hover:border-rose-500/50 p-2.5 rounded-xl transition-all text-left flex items-center justify-between gap-2 group active:scale-98 disabled:opacity-50 disabled:pointer-events-none"
            >
              <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-7 h-7 rounded-lg bg-rose-500/15 text-rose-400 flex items-center justify-center font-bold text-xs border border-rose-500/20 shrink-0">
                  AS
                </div>
                <div class="min-w-0">
                  <div class="text-xs font-bold text-slate-200 group-hover:text-rose-300 transition-colors truncate">
                    Andi Saputra
                  </div>
                  <div class="text-xs text-slate-300 truncate">XII TKJ 2 • Durasi Lewat Waktu</div>
                </div>
              </div>
              <span class="shrink-0 text-xs font-bold px-2 py-0.5 rounded bg-rose-500/20 text-rose-300 border border-rose-500/30">
                Terlambat
              </span>
            </button>

            <!-- Skenario 3: Rizky (Exit School) -->
            <button
              @click="scanToken('QR-EXIT-X-AK3-005')"
              :disabled="isProcessing"
              class="w-full bg-slate-900 hover:bg-slate-800/90 border border-slate-800 hover:border-amber-500/50 p-2.5 rounded-xl transition-all text-left flex items-center justify-between gap-2 group active:scale-98 disabled:opacity-50 disabled:pointer-events-none"
            >
              <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-7 h-7 rounded-lg bg-amber-500/15 text-amber-400 flex items-center justify-center font-bold text-xs border border-amber-500/20 shrink-0">
                  RP
                </div>
                <div class="min-w-0">
                  <div class="text-xs font-bold text-slate-200 group-hover:text-amber-300 transition-colors truncate">
                    Rizky Pratama
                  </div>
                  <div class="text-xs text-slate-300 truncate">X AK 3 • Izin Pulang Sekolah</div>
                </div>
              </div>
              <span class="shrink-0 text-xs font-bold px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/30">
                Pulang
              </span>
            </button>
          </div>
        </div>

        <!-- Manual Token Form -->
        <form @submit.prevent="scanToken(inputQrToken)" class="w-full space-y-1.5">
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 px-0.5">
            Input Kode Izin Manual
          </label>
          <div class="flex items-center gap-2">
            <input
              v-model="inputQrToken"
              type="text"
              placeholder="Masukkan kode surat izin..."
              :disabled="isProcessing"
              class="flex-1 bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-xs outline-none focus:border-emerald-500 text-white font-mono placeholder:text-slate-500 transition min-h-[36px] disabled:opacity-50"
            />
            <BaseButton type="submit" variant="primary" size="sm" :disabled="!inputQrToken || isProcessing" :loading="isProcessing">
              Periksa
            </BaseButton>
          </div>
        </form>
      </div>

    <!-- Scan Verification Dialog Modal -->
    <BaseModal
      :show="!!scanResult"
      title="Hasil Pemeriksaan Izin"
      max-width="md"
      @close="handleCloseModal"
    >
      <!-- Kasus Berhasil -->
      <div v-if="scanResult?.success" class="space-y-4 text-slate-900">
        <div
          class="p-4 rounded-2xl border text-center space-y-1"
          :class="scanResult.scan_action === 'CHECK_IN_LATE' ? 'bg-amber-50 border-amber-200 text-amber-900' : 'bg-emerald-50 border-emerald-200 text-emerald-900'"
        >
          <AlertTriangle v-if="scanResult.scan_action === 'CHECK_IN_LATE'" class="w-10 h-10 text-amber-600 mx-auto" :stroke-width="1.75" />
          <CheckCircle v-else class="w-10 h-10 text-emerald-600 mx-auto" :stroke-width="1.75" />
          <h4 class="font-bold text-base">
            {{
              scanResult.scan_action === 'CHECK_IN_ON_TIME'
                ? 'Siswa Kembali Tepat Waktu'
                : (scanResult.scan_action === 'CHECK_IN_LATE'
                    ? 'Siswa Kembali (Terlambat)'
                    : (scanResult.scan_action === 'EXIT'
                        ? 'Siswa Diizinkan Pulang'
                        : 'Siswa Diizinkan Keluar'))
            }}
          </h4>
          <p
            class="text-xs font-semibold"
            :class="scanResult.scan_action === 'CHECK_IN_LATE' ? 'text-amber-700' : 'text-emerald-700'"
          >
            {{ scanResult.message }}
          </p>
        </div>

        <div v-if="scanResult.data" class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2 text-xs">
          <div class="flex justify-between">
            <span class="text-slate-500">Nama Siswa:</span>
            <span class="font-bold text-slate-900">{{ scanResult.data.student?.name }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500">NIS / Kelas:</span>
            <span class="font-bold text-slate-900">{{ scanResult.data.student?.nis || scanResult.data.student?.username }} • {{ scanResult.data.student?.class_name }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500">Jenis Izin:</span>
            <span class="font-bold text-slate-900">{{ scanResult.data.type === 'TEMP' ? 'Keluar Sementara' : 'Izin Pulang' }}</span>
          </div>
          <div class="flex justify-between items-center pt-2 border-t border-slate-200">
            <span class="text-slate-500">Status Izin:</span>
            <BaseBadge :status="scanResult.data.status" />
          </div>
        </div>
      </div>

      <!-- Kasus Khusus: Izin Telah Selesai / Ditutup Sebelumnya -->
      <div v-else-if="scanResult?.isAlreadyClosed" class="space-y-4 text-slate-900">
        <div class="p-4 rounded-2xl border text-center space-y-1.5 bg-amber-50/80 border-amber-200 text-amber-950">
          <Clock class="w-10 h-10 text-amber-600 mx-auto" :stroke-width="1.75" />
          <h4 class="font-bold text-base text-amber-900">Surat Izin Telah Selesai</h4>
          <p class="text-xs text-amber-800 leading-relaxed font-semibold">
            {{ scanResult.message }}
          </p>
          <p class="text-[11px] text-amber-700/80">
            Surat izin ini sudah pernah dipindai dan tuntas sebelumnya. Tidak dapat digunakan kembali.
          </p>
        </div>

        <div v-if="scanResult.data" class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2 text-xs">
          <div class="flex justify-between">
            <span class="text-slate-500">Nama Siswa:</span>
            <span class="font-bold text-slate-900">{{ scanResult.data.student?.name }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500">NIS / Kelas:</span>
            <span class="font-bold text-slate-900">{{ scanResult.data.student?.nis || scanResult.data.student?.username }} • {{ scanResult.data.student?.class_name }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500">Jenis Izin:</span>
            <span class="font-bold text-slate-900">{{ scanResult.data.type === 'TEMP' ? 'Keluar Sementara' : 'Izin Pulang' }}</span>
          </div>
          <div class="flex justify-between items-center pt-2 border-t border-slate-200">
            <span class="text-slate-500">Status Terakhir:</span>
            <BaseBadge :status="scanResult.data.status" />
          </div>
        </div>
      </div>

      <!-- Kasus Khusus: Izin Ditandai ALPHA -->
      <div v-else-if="scanResult?.isAlpha" class="space-y-4 text-slate-900">
        <div class="p-4 rounded-2xl border text-center space-y-1.5 bg-rose-50 border-rose-200 text-rose-950">
          <AlertTriangle class="w-10 h-10 text-rose-600 mx-auto" :stroke-width="1.75" />
          <h4 class="font-bold text-base text-rose-900">Perhatian: Siswa Ditandai ALPHA</h4>
          <p class="text-xs text-rose-800 leading-relaxed font-semibold">
            {{ scanResult.message }}
          </p>
        </div>

        <div v-if="scanResult.data" class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2 text-xs">
          <div class="flex justify-between">
            <span class="text-slate-500">Nama Siswa:</span>
            <span class="font-bold text-slate-900">{{ scanResult.data.student?.name }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500">NIS / Kelas:</span>
            <span class="font-bold text-slate-900">{{ scanResult.data.student?.nis || scanResult.data.student?.username }} • {{ scanResult.data.student?.class_name }}</span>
          </div>
          <div class="flex justify-between items-center pt-2 border-t border-slate-200">
            <span class="text-slate-500">Status:</span>
            <BaseBadge :status="scanResult.data.status" />
          </div>
        </div>
      </div>

      <!-- Kasus Gagal Lainnya -->
      <div v-else-if="scanResult" class="space-y-4 text-slate-900 text-center py-4">
        <XCircle class="w-12 h-12 text-rose-600 mx-auto" :stroke-width="1.75" />
        <h4 class="font-bold text-base text-rose-900">Pemeriksaan Gagal</h4>
        <p class="text-xs text-slate-600 max-w-xs mx-auto">{{ scanResult.message }}</p>
      </div>

      <template #footer>
        <BaseButton variant="primary" size="md" block @click="handleCloseModal">
          Tutup & Lanjutkan
        </BaseButton>
      </template>
    </BaseModal>
  </div>
</template>

<script setup>
import { ref, nextTick, onMounted, onUnmounted } from 'vue'
import { Html5Qrcode } from 'html5-qrcode'
import { usePermitStore } from '@/stores/permit'
import { useToast } from '@/composables/useToast'
import BaseModal from '@/components/ui/BaseModal.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import { Camera, QrCode, CheckCircle, XCircle, AlertTriangle, Clock } from 'lucide-vue-next'

const permitStore = usePermitStore()
const toast = useToast()

const scanResult = ref(null)
const inputQrToken = ref('')
const isCameraActive = ref(false)
const isProcessing = ref(false)
const isCooldown = ref(false)
const isOnline = ref(typeof navigator !== 'undefined' ? navigator.onLine : true)

let html5QrCode = null

const handleOnline = () => {
  isOnline.value = true
  toast.success('Koneksi internet terhubung kembali.')
}

const handleOffline = () => {
  isOnline.value = false
  toast.error('Koneksi internet terputus!')
}

onMounted(() => {
  window.addEventListener('online', handleOnline)
  window.addEventListener('offline', handleOffline)
})

const toggleCamera = async () => {
  if (isCameraActive.value) {
    stopCamera()
  } else {
    startCamera()
  }
}

const startCamera = async () => {
  try {
    isCameraActive.value = true
    await nextTick()
    html5QrCode = new Html5Qrcode("qr-reader")
    await html5QrCode.start(
      { facingMode: "environment" },
      { fps: 10, qrbox: { width: 220, height: 220 } },
      (decodedText) => {
        scanToken(decodedText)
      },
      () => {}
    )
    toast.info('Kamera pemindai aktif.')
  } catch (err) {
    toast.error('Gagal mengakses kamera. Silakan gunakan input kode manual.')
    isCameraActive.value = false
  }
}

const stopCamera = async () => {
  if (html5QrCode && isCameraActive.value) {
    try {
      if (html5QrCode.getState() === 3) {
        try { html5QrCode.resume() } catch (e) {}
      }
      await html5QrCode.stop()
      html5QrCode.clear()
    } catch (err) {}
    isCameraActive.value = false
  }
}

const handleCloseModal = () => {
  scanResult.value = null
  isCooldown.value = true
  // Jeda 1.5 detik agar satpam sempat menjauhkan kamera sebelum memindai kode berikutnya
  setTimeout(() => {
    isCooldown.value = false
    if (html5QrCode && isCameraActive.value) {
      try {
        if (html5QrCode.getState() === 3) { // 3 = PAUSED
          html5QrCode.resume()
        }
      } catch (err) {
        console.warn('Gagal melanjutkan kamera pemindai:', err)
      }
    }
  }, 1500)
}

const scanToken = async (qrToken) => {
  if (!qrToken) return
  if (isProcessing.value || isCooldown.value || scanResult.value) return

  if (!isOnline.value || (typeof navigator !== 'undefined' && !navigator.onLine)) {
    scanResult.value = {
      success: false,
      message: 'Perangkat offline. Pastikan koneksi internet terhubung untuk memeriksa izin.'
    }
    toast.error('Perangkat pos satpam sedang offline.')
    return
  }

  isProcessing.value = true

  // Segera jeda pemindaian frame kamera agar tidak memicu pemindaian ganda
  if (html5QrCode && isCameraActive.value) {
    try {
      if (html5QrCode.getState() === 2) { // 2 = SCANNING
        html5QrCode.pause()
      }
    } catch (e) {
      console.warn('Gagal menjeda kamera:', e)
    }
  }

  try {
    const res = await permitStore.scanQrToken(qrToken)
    scanResult.value = {
      success: true,
      message: res.message,
      data: res.data,
      scan_action: res.scan_action,
    }
    inputQrToken.value = ''
  } catch (err) {
    const errorData = err.response?.data
    const errorMsg = !err.response
      ? 'Koneksi ke server terputus. Periksa jaringan internet pos satpam.'
      : (errorData?.message || 'Kode surat izin tidak ditemukan atau sudah tidak berlaku.')

    const permitData = errorData?.data
    const isAlreadyClosed = permitData?.status === 'CLOSED' || permitData?.status === 'COMPLETED'
    const isAlpha = permitData?.status === 'ALPHA'

    scanResult.value = {
      success: false,
      message: errorMsg,
      isAlreadyClosed,
      isAlpha,
      data: permitData,
    }
  } finally {
    isProcessing.value = false
  }
}

onUnmounted(() => {
  stopCamera()
  window.removeEventListener('online', handleOnline)
  window.removeEventListener('offline', handleOffline)
})
</script>
