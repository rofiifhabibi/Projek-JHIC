<template>
  <div class="flex-1 relative flex flex-col items-center justify-center p-3.5 sm:p-6 lg:p-8 max-w-lg md:max-w-3xl lg:max-w-6xl mx-auto w-full">
      
    <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-4 lg:gap-6 items-start">
      <!-- Left Column: Camera Reader & Manual Form -->
      <div class="md:col-span-7 bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 flex flex-col items-center shadow-xs space-y-4">
        <!-- Control Bar: Status Kamera & Tombol Switch ON / OFF -->
        <div class="w-full bg-slate-50/90 rounded-2xl p-2.5 sm:p-3 border border-slate-200/80 flex items-center justify-between gap-2.5">
          <!-- Status Indikator -->
          <div class="flex items-center gap-2.5 min-w-0">
            <div
              class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 transition-colors"
              :class="isCameraActive ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-500'"
            >
              <Camera v-if="isCameraActive" class="w-4.5 h-4.5" />
              <CameraOff v-else class="w-4.5 h-4.5" />
            </div>
            <div class="min-w-0">
              <p class="text-xs font-bold text-slate-900 leading-tight truncate">Kamera Pemindai</p>
              <div class="flex items-center gap-1.5 mt-0.5">
                <span
                  class="w-2 h-2 rounded-full shrink-0"
                  :class="isCameraActive ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400'"
                ></span>
                <span
                  class="text-[11px] sm:text-xs font-black tracking-wide"
                  :class="isCameraActive ? 'text-emerald-700' : 'text-slate-500'"
                >
                  {{ isCameraActive ? 'MENYALA (ON)' : 'MATI (OFF)' }}
                </span>
              </div>
            </div>
          </div>

          <!-- Tombol Switch ON / OFF & Reset -->
          <div class="flex items-center gap-1.5 shrink-0">
            <!-- Tombol Segarkan Aliran Kamera jika aktif -->
            <button
              v-if="isCameraActive"
              type="button"
              @click="restartCamera"
              :disabled="isRestarting || isTogglingCamera"
              title="Segarkan Aliran Kamera"
              class="h-9 px-2 sm:px-2.5 rounded-xl bg-white hover:bg-slate-100 active:scale-95 text-slate-600 border border-slate-200 flex items-center justify-center gap-1 transition shadow-2xs cursor-pointer touch-manipulation disabled:opacity-50 text-xs font-bold"
            >
              <RefreshCw class="w-3.5 h-3.5" :class="{ 'animate-spin': isRestarting }" />
              <span class="hidden sm:inline">Reset</span>
            </button>

            <!-- Segmented Pill Switch: OFF | ON -->
            <div class="inline-flex p-1 bg-slate-200/90 rounded-xl border border-slate-300/70 shadow-inner">
              <button
                type="button"
                @click="isCameraActive && stopCamera()"
                :disabled="isTogglingCamera || isRestarting"
                class="px-3 sm:px-3.5 py-1.5 rounded-lg text-xs font-black transition-all flex items-center gap-1 cursor-pointer touch-manipulation disabled:opacity-50"
                :class="!isCameraActive
                  ? 'bg-slate-700 text-white shadow-xs'
                  : 'text-slate-500 hover:text-slate-800'"
              >
                <span class="w-1.5 h-1.5 rounded-full" :class="!isCameraActive ? 'bg-slate-300' : 'bg-transparent'"></span>
                <span>OFF</span>
              </button>

              <button
                type="button"
                @click="!isCameraActive && startCamera()"
                :disabled="isTogglingCamera || isRestarting"
                class="px-3 sm:px-3.5 py-1.5 rounded-lg text-xs font-black transition-all flex items-center gap-1 cursor-pointer touch-manipulation disabled:opacity-50"
                :class="isCameraActive
                  ? 'bg-[#355245] text-white shadow-xs'
                  : 'text-slate-600 hover:text-[#355245]'"
              >
                <span class="w-1.5 h-1.5 rounded-full" :class="isCameraActive ? 'bg-emerald-300' : 'bg-transparent'"></span>
                <span>ON</span>
              </button>
            </div>
          </div>
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
            class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center space-y-3.5 bg-slate-50"
          >
            <div class="w-14 h-14 rounded-2xl bg-white border border-slate-200 text-slate-500 flex items-center justify-center shadow-xs">
              <Camera class="w-7 h-7 text-[#355245]" />
            </div>
            <div class="space-y-1 max-w-xs">
              <h3 class="text-sm sm:text-base font-bold text-slate-800">Kamera Scanner Nonaktif</h3>
              <p class="text-xs text-slate-500 leading-relaxed">
                Sentuh tombol di bawah untuk menyalakan kamera pemindai surat izin siswa di gerbang.
              </p>
            </div>
            <button
              type="button"
              @click="startCamera()"
              :disabled="isTogglingCamera || isRestarting"
              class="px-5 py-2.5 rounded-xl bg-[#355245] hover:bg-[#273e34] active:scale-95 text-white font-bold text-xs sm:text-sm flex items-center gap-2 shadow-xs transition-all cursor-pointer touch-manipulation disabled:opacity-50"
            >
              <Camera class="w-4 h-4 sm:w-4.5 sm:h-4.5" />
              <span>Nyalakan Kamera (ON)</span>
            </button>
          </div>
        </div>

        <!-- Live Scanning Helper Hint & Bottom Turn Off Control -->
        <div v-if="isCameraActive" class="w-full bg-emerald-50 border border-emerald-200/80 rounded-xl p-3 flex items-center justify-between gap-3 text-xs text-emerald-800">
          <div class="flex items-center gap-2 min-w-0">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-pulse shrink-0"></span>
            <span class="font-medium truncate sm:whitespace-normal">Arahkan kamera ke kode QR siswa.</span>
          </div>
          <button
            type="button"
            @click="stopCamera"
            :disabled="isTogglingCamera || isRestarting"
            class="shrink-0 text-xs font-bold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-100 border border-slate-300 px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition active:scale-95 cursor-pointer touch-manipulation shadow-2xs disabled:opacity-50"
          >
            <CameraOff class="w-3.5 h-3.5 text-slate-500" />
            <span>Matikan (OFF)</span>
          </button>
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
      <div class="md:col-span-5 space-y-4">
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
                  Pilih tombol <strong class="text-slate-800">"ON"</strong> pada panel pemindai untuk membuka kamera.
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
import { Html5Qrcode, Html5QrcodeSupportedFormats } from 'html5-qrcode'
import { usePermitStore } from '@/stores/permit'
import { useToast } from '@/composables/useToast'
import BaseModal from '@/components/ui/BaseModal.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import { Camera, CameraOff, QrCode, CheckCircle, XCircle, AlertTriangle, Clock, ShieldCheck, RefreshCw } from 'lucide-vue-next'

const permitStore = usePermitStore()
const toast = useToast()

const scanResult = ref(null)
const inputQrToken = ref('')
const isCameraActive = ref(false)
const isProcessing = ref(false)
const isCooldown = ref(false)
const isRestarting = ref(false)
const isTogglingCamera = ref(false)
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
  if (isTogglingCamera.value || isRestarting.value) return
  if (isCameraActive.value) {
    stopCamera()
  } else {
    startCamera()
  }
}

const startCamera = async (silent = false) => {
  if (isTogglingCamera.value) return
  isTogglingCamera.value = true
  try {
    isCameraActive.value = true
    await nextTick()
    // Optimasi performa tinggi: batasi hanya format QR_CODE dan gunakan akselerasi GPU/NPU BarcodeDetector bawaan HP
    html5QrCode = new Html5Qrcode("qr-reader", {
      formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ],
      verbose: false,
      experimentalFeatures: {
        useBarCodeDetectorIfSupported: true
      }
    })
    await html5QrCode.start(
      { facingMode: "environment" },
      {
        fps: 4, // 4 FPS sangat hemat CPU & RAM ponsel (tidak membuat HP panas / lag)
        qrbox: { width: 220, height: 220 },
        aspectRatio: 1.0
      },
      (decodedText) => {
        scanToken(decodedText)
      },
      () => {}
    )
    if (!silent) {
      toast.info('Kamera pemindai aktif.')
    }
  } catch (err) {
    toast.error('Gagal mengakses kamera. Silakan gunakan input kode manual.')
    isCameraActive.value = false
  } finally {
    isTogglingCamera.value = false
  }
}

const stopCamera = async () => {
  if (isTogglingCamera.value) return
  isTogglingCamera.value = true
  try {
    if (html5QrCode) {
      try {
        if (html5QrCode.isScanning) {
          await html5QrCode.stop()
        }
        html5QrCode.clear()
      } catch (err) {
        console.warn('Gagal menghentikan scanner:', err)
      }
      html5QrCode = null
      isCameraActive.value = false
    }
  } finally {
    isTogglingCamera.value = false
  }
}

const restartCamera = async () => {
  if (isRestarting.value || isTogglingCamera.value) return
  isRestarting.value = true
  try {
    await stopCamera()
    await new Promise(resolve => setTimeout(resolve, 350))
    await startCamera(true) // Senyap tanpa memicu toast ganda
    toast.info('Kamera pemindai berhasil disegarkan.')
  } catch (err) {
    console.warn('Gagal menyegarkan kamera:', err)
  } finally {
    isRestarting.value = false
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
