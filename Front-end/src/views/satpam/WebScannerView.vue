<template>
  <div class="flex-1 relative flex flex-col items-center justify-center p-3.5 sm:p-6 lg:p-8 max-w-md lg:max-w-5xl mx-auto w-full">
      
    <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-6 items-start">
      <!-- Left Column: Camera Reader & Manual Form -->
      <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 flex flex-col items-center shadow-xs space-y-4">
        <!-- Top bar of scanner card -->
        <div class="flex items-center justify-between w-full border-b border-slate-100 pb-3">
          <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full" :class="isCameraActive ? 'bg-emerald-500' : 'bg-slate-300'"></span>
            <span class="text-xs font-bold text-slate-800">
              {{ isCameraActive ? 'Kamera Pemindai Aktif' : 'Pemindai Kamera Gerbang' }}
            </span>
          </div>
          <button
            type="button"
            @click="toggleCamera"
            class="whitespace-nowrap text-xs font-bold px-3 py-1.5 rounded-xl border transition-all active:scale-95 flex items-center gap-1.5 shadow-2xs cursor-pointer touch-manipulation"
            :class="isCameraActive ? 'border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100' : 'border-[#355245] bg-[#355245] text-white hover:bg-[#283e34]'"
          >
            <Camera class="w-3.5 h-3.5" />
            {{ isCameraActive ? 'Matikan Kamera' : 'Nyalakan Kamera' }}
          </button>
        </div>

        <!-- Video Reader Element with Viewfinder Styling -->
        <div
          class="w-full min-h-[260px] sm:min-h-[300px] rounded-2xl border overflow-hidden relative flex items-center justify-center transition-colors"
          :class="isCameraActive ? 'bg-black border-slate-800' : 'bg-slate-50 border-slate-200'"
        >
          <div id="qr-reader" class="w-full" :class="{ 'hidden': !isCameraActive }"></div>

          <!-- Processing Overlay -->
          <div
            v-if="isProcessing"
            class="absolute inset-0 bg-white/85 backdrop-blur-xs flex flex-col items-center justify-center p-4 text-center space-y-2 z-10 pointer-events-none"
          >
            <div class="w-7 h-7 border-2 border-[#355245] border-t-transparent rounded-full animate-spin"></div>
            <p class="text-xs font-bold text-[#355245]">Memeriksa Surat Izin...</p>
          </div>

          <!-- Cooldown indicator -->
          <div
            v-else-if="isCooldown"
            class="absolute bottom-3 inset-x-4 bg-white/95 border border-slate-200 rounded-xl py-1.5 px-3 text-center text-xs font-medium text-slate-700 z-10 pointer-events-none shadow-xs"
          >
            Kamera dijeda sejenak...
          </div>

          <!-- Camera Inactive Viewfinder with Clean Action Button -->
          <div
            v-if="!isCameraActive"
            class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center space-y-3 bg-slate-50"
          >
            <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 text-[#355245] flex items-center justify-center shadow-2xs">
              <Camera class="w-6 h-6" />
            </div>
            <div class="space-y-1 max-w-xs">
              <h3 class="text-sm font-bold text-slate-800">Kamera Pemindai Nonaktif</h3>
              <p class="text-xs text-slate-500 leading-relaxed">
                Tekan tombol di bawah untuk membuka kamera dan memindai kode QR izin siswa.
              </p>
            </div>
            <button
              type="button"
              @click="startCamera"
              class="px-4 py-2.5 rounded-xl bg-[#355245] hover:bg-[#283e34] active:scale-95 text-white font-bold text-xs sm:text-sm flex items-center gap-2 shadow-xs transition-all cursor-pointer touch-manipulation"
            >
              <Camera class="w-4 h-4" />
              <span>Nyalakan Kamera</span>
            </button>
          </div>
        </div>

        <!-- Live Scanning Helper Hint -->
        <div v-if="isCameraActive" class="w-full bg-emerald-50 border border-emerald-200 rounded-xl p-2.5 flex items-center gap-2 text-xs text-emerald-800 font-medium">
          <span class="w-2 h-2 rounded-full bg-emerald-600 shrink-0"></span>
          <span>Arahkan kamera ke layar HP siswa. Kode QR akan dipindai otomatis.</span>
        </div>

        <!-- Manual Token Form -->
        <form @submit.prevent="scanToken(inputQrToken)" class="w-full space-y-1.5 pt-1">
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 px-0.5">
            Input Kode Izin Manual (Alternatif)
          </label>
          <div class="flex items-center gap-2">
            <input
              v-model="inputQrToken"
              type="text"
              placeholder="Masukkan kode surat izin siswa..."
              :disabled="isProcessing"
              class="flex-1 bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs outline-none focus:border-[#355245] focus:ring-1 focus:ring-[#355245] text-slate-800 font-mono placeholder:text-slate-400 transition min-h-[38px] disabled:opacity-50"
            />
            <BaseButton type="submit" variant="primary" size="sm" :disabled="!inputQrToken || isProcessing" :loading="isProcessing">
              Periksa
            </BaseButton>
          </div>
          <p class="text-[11px] text-slate-500 px-0.5">
            Gunakan kolom ini bila kamera ponsel/komputer buram atau tidak dapat memindai layar siswa.
          </p>
        </form>
      </div>

      <!-- Right Column: Panduan Operasional Pos Satpam -->
      <div class="lg:col-span-5 space-y-4">
        <!-- Card 1: Langkah Penggunaan -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
          <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[#355245] pb-2 border-b border-slate-100">
            <ShieldCheck class="w-4 h-4 text-[#355245] shrink-0" />
            <span>Petunjuk Penggunaan Petugas</span>
          </div>

          <div class="space-y-3 text-xs">
            <div class="flex items-start gap-2.5">
              <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                1
              </span>
              <div>
                <strong class="text-slate-800 block">Nyalakan Kamera</strong>
                <p class="text-slate-500 mt-0.5 leading-relaxed">
                  Tekan tombol <strong class="text-slate-700">"Nyalakan Kamera"</strong> di kartu pemindai.
                </p>
              </div>
            </div>

            <div class="flex items-start gap-2.5">
              <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                2
              </span>
              <div>
                <strong class="text-slate-800 block">Izinkan Akses Kamera</strong>
                <p class="text-slate-500 mt-0.5 leading-relaxed">
                  Jika layar memunculkan konfirmasi izin kamera browser, pilih <strong class="text-slate-700">"Izinkan" / "Allow"</strong>.
                </p>
              </div>
            </div>

            <div class="flex items-start gap-2.5">
              <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                3
              </span>
              <div>
                <strong class="text-slate-800 block">Arahkan ke QR Siswa</strong>
                <p class="text-slate-500 mt-0.5 leading-relaxed">
                  Minta siswa membuka surat izin di aplikasi HP mereka, lalu hadapkan kode QR ke depan kamera.
                </p>
              </div>
            </div>

            <div class="flex items-start gap-2.5">
              <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                4
              </span>
              <div>
                <strong class="text-slate-800 block">Lihat Hasil Pemeriksaan</strong>
                <p class="text-slate-500 mt-0.5 leading-relaxed">
                  Layar otomatis memunculkan data nama siswa, status izin, dan instruksi (Boleh Keluar / Boleh Masuk).
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 2: Standar Hasil Pemeriksaan -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs text-xs space-y-2.5">
          <p class="font-bold text-slate-700 uppercase tracking-wider text-[11px] pb-1 border-b border-slate-100">
            Arti Hasil Pemeriksaan:
          </p>
          <div class="space-y-2">
            <div class="flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
              <span class="text-slate-600"><strong class="text-slate-800">Scan Keluar:</strong> Siswa diizinkan keluar sekolah.</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
              <span class="text-slate-600"><strong class="text-slate-800">Scan Masuk (Tepat Waktu):</strong> Siswa kembali, izin selesai.</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></span>
              <span class="text-slate-600"><strong class="text-slate-800">Terlambat:</strong> Siswa melewati batas waktu (tercatat otomatis di BK).</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0"></span>
              <span class="text-slate-600"><strong class="text-slate-800">Alpha / Ditolak:</strong> Surat izin tidak berlaku / dibatalkan guru.</span>
            </div>
          </div>
        </div>
      </div>
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
import { Camera, QrCode, CheckCircle, XCircle, AlertTriangle, Clock, ShieldCheck } from 'lucide-vue-next'

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
