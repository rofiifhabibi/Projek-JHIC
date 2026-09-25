<template>
  <div class="min-h-screen bg-slate-950 text-white flex flex-col font-sans">
    <ToastNotification />

    <!-- Top Bar -->
    <header class="bg-slate-900 border-b border-slate-800 px-4 py-3 sticky top-0 z-30 shadow-xl">
      <div class="max-w-md sm:max-w-4xl mx-auto flex items-center justify-between gap-3">
        <div class="flex items-center gap-2.5 min-w-0">
          <div class="w-9 h-9 bg-[#355245] rounded-xl flex items-center justify-center font-extrabold text-xs text-white shadow-md border border-white/10 shrink-0">
            SP
          </div>
          <div class="min-w-0">
            <div class="flex items-center gap-1.5">
              <h1 class="font-extrabold text-sm sm:text-base tracking-tight text-white leading-tight truncate">
                Scanner Satpam
              </h1>
              <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-[9px] font-bold border border-emerald-500/30 shrink-0">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                Siaga
              </span>
            </div>
            <p class="text-[10px] text-slate-400 font-medium truncate mt-0.5">Pos Gerbang Utama • SMKN 2 Depok</p>
          </div>
        </div>
        <button
          @click="showLogoutConfirm = true"
          class="shrink-0 text-xs bg-slate-800 hover:bg-slate-700 active:scale-95 px-2.5 sm:px-3 py-1.5 rounded-xl font-bold border border-slate-700 transition-all text-white flex items-center gap-1.5 shadow-sm"
        >
          <LogOut class="w-3.5 h-3.5" />
          <span class="hidden sm:inline">Keluar</span>
        </button>
      </div>
    </header>

    <!-- Main Scanner Interface -->
    <div class="flex-1 relative flex flex-col items-center justify-center p-3 sm:p-6 max-w-md mx-auto w-full space-y-4">
      
      <!-- Camera Reader Container -->
      <div class="w-full bg-slate-900 rounded-2xl border border-slate-800 p-4 sm:p-5 flex flex-col items-center shadow-xl space-y-4">
        <!-- Top bar of scanner card -->
        <div class="flex items-center justify-between w-full border-b border-slate-800/80 pb-3">
          <div class="flex items-center gap-2">
            <Camera class="w-4 h-4 text-slate-300 shrink-0" />
            <span class="text-xs font-bold text-slate-200">Kamera Scanner</span>
          </div>
          <button
            @click="toggleCamera"
            class="whitespace-nowrap text-[11px] font-bold px-3 py-1.5 rounded-xl border transition-all active:scale-95 flex items-center gap-1.5"
            :class="isCameraActive ? 'border-rose-500/50 bg-rose-500/15 text-rose-300 hover:bg-rose-500/25' : 'border-emerald-500/50 bg-emerald-500/15 text-emerald-300 hover:bg-emerald-500/25'"
          >
            <span class="w-1.5 h-1.5 rounded-full" :class="isCameraActive ? 'bg-rose-400' : 'bg-emerald-400'"></span>
            {{ isCameraActive ? 'Matikan Kamera' : 'Nyalakan Kamera' }}
          </button>
        </div>

        <!-- Video Reader Element with Viewfinder Styling -->
        <div class="w-full min-h-[230px] bg-slate-950 rounded-2xl border border-slate-800/90 overflow-hidden relative shadow-inner flex items-center justify-center">
          <div id="qr-reader" class="w-full" :class="{ 'hidden': !isCameraActive }"></div>

          <!-- Idle Placeholder Viewfinder (Dead Center) -->
          <div
            v-if="!isCameraActive"
            class="absolute inset-0 flex flex-col items-center justify-center p-5 text-center space-y-2.5 pointer-events-none"
          >
            <div class="w-12 h-12 rounded-2xl bg-slate-900/90 border border-slate-800 flex items-center justify-center mx-auto text-slate-300 shadow-sm">
              <QrCode class="w-6 h-6" />
            </div>
            <div>
              <p class="text-xs font-bold text-slate-200">Siap Memindai QR Code</p>
              <p class="text-[11px] text-slate-400 mt-0.5 max-w-[220px] mx-auto leading-relaxed">
                Nyalakan kamera untuk scan tiket di HP siswa, atau gunakan uji simulasi cepat di bawah.
              </p>
            </div>
          </div>
        </div>

        <!-- Quick Simulation Selector (Clean Security Post Station Style) -->
        <div class="w-full space-y-2 bg-slate-950/60 p-3.5 rounded-xl border border-slate-800/70">
          <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-wider text-slate-400 px-0.5">
            <span>UJI SIMULASI GERBANG (3 KASUS)</span>
            <span class="text-emerald-400 font-mono text-[9px]">1-Click Scan</span>
          </div>

          <div class="grid grid-cols-1 gap-1.5">
            <!-- Skenario 1: Siti (TEMP Normal) -->
            <button
              @click="scanToken('QR-ACTIVE-XII-RPL1-002')"
              class="w-full bg-slate-900 hover:bg-slate-800/90 border border-slate-800 hover:border-emerald-500/50 p-2.5 rounded-xl transition-all text-left flex items-center justify-between gap-2 group active:scale-98"
            >
              <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-7 h-7 rounded-lg bg-emerald-500/15 text-emerald-400 flex items-center justify-center font-bold text-[11px] border border-emerald-500/20 shrink-0">
                  SR
                </div>
                <div class="min-w-0">
                  <div class="text-xs font-bold text-slate-200 group-hover:text-emerald-300 transition-colors truncate">
                    Siti Rahmawati
                  </div>
                  <div class="text-[10px] text-slate-400 truncate">XII RPL 1 • Izin Sementara UKS</div>
                </div>
              </div>
              <span class="shrink-0 text-[9px] font-black uppercase px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                ACTIVE
              </span>
            </button>

            <!-- Skenario 2: Andi (Overdue) -->
            <button
              @click="scanToken('QR-OVERDUE-XII-TKJ2-003')"
              class="w-full bg-slate-900 hover:bg-slate-800/90 border border-slate-800 hover:border-rose-500/50 p-2.5 rounded-xl transition-all text-left flex items-center justify-between gap-2 group active:scale-98"
            >
              <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-7 h-7 rounded-lg bg-rose-500/15 text-rose-400 flex items-center justify-center font-bold text-[11px] border border-rose-500/20 shrink-0">
                  AS
                </div>
                <div class="min-w-0">
                  <div class="text-xs font-bold text-slate-200 group-hover:text-rose-300 transition-colors truncate">
                    Andi Saputra
                  </div>
                  <div class="text-[10px] text-slate-400 truncate">XII TKJ 2 • Durasi Lewat Waktu</div>
                </div>
              </div>
              <span class="shrink-0 text-[9px] font-black uppercase px-2 py-0.5 rounded bg-rose-500/20 text-rose-300 border border-rose-500/30">
                OVERDUE
              </span>
            </button>

            <!-- Skenario 3: Rizky (Exit School) -->
            <button
              @click="scanToken('QR-EXIT-X-AK3-005')"
              class="w-full bg-slate-900 hover:bg-slate-800/90 border border-slate-800 hover:border-amber-500/50 p-2.5 rounded-xl transition-all text-left flex items-center justify-between gap-2 group active:scale-98"
            >
              <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-7 h-7 rounded-lg bg-amber-500/15 text-amber-400 flex items-center justify-center font-bold text-[11px] border border-amber-500/20 shrink-0">
                  RP
                </div>
                <div class="min-w-0">
                  <div class="text-xs font-bold text-slate-200 group-hover:text-amber-300 transition-colors truncate">
                    Rizky Pratama
                  </div>
                  <div class="text-[10px] text-slate-400 truncate">X AK 3 • Izin Pulang Sekolah</div>
                </div>
              </div>
              <span class="shrink-0 text-[9px] font-black uppercase px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/30">
                PULANG
              </span>
            </button>
          </div>
        </div>

        <!-- Manual Token Form -->
        <form @submit.prevent="scanToken(inputQrToken)" class="w-full space-y-1.5">
          <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 px-0.5">
            Input Token QR Manual
          </label>
          <div class="flex gap-2">
            <input
              v-model="inputQrToken"
              type="text"
              placeholder="Ketik / tempel token QR..."
              class="flex-1 bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs outline-none focus:border-emerald-500 text-white font-mono placeholder:text-slate-600 transition"
            />
            <BaseButton type="submit" variant="primary" size="sm" :disabled="!inputQrToken">
              Scan
            </BaseButton>
          </div>
        </form>
      </div>
    </div>

    <!-- Scan Verification Dialog Modal -->
    <BaseModal
      :show="!!scanResult"
      title="Hasil Validasi Gerbang Satpam"
      max-width="md"
      @close="scanResult = null"
    >
      <div v-if="scanResult?.success" class="space-y-4 text-slate-900">
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-center space-y-1">
          <CheckCircle2 class="w-10 h-10 text-emerald-600 mx-auto" />
          <h4 class="font-extrabold text-base">VALIDASI GERBANG BERHASIL</h4>
          <p class="text-xs font-semibold text-emerald-700">{{ scanResult.message }}</p>
        </div>

        <div v-if="scanResult.data" class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2 text-xs">
          <div class="flex justify-between">
            <span class="text-slate-500">Nama Siswa:</span>
            <span class="font-bold text-slate-900">{{ scanResult.data.student?.name }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500">NIS / Rombel:</span>
            <span class="font-bold text-slate-900">{{ scanResult.data.student?.nis }} • {{ scanResult.data.student?.class_name }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500">Jenis Perizinan:</span>
            <span class="font-bold text-slate-900">{{ scanResult.data.type === 'TEMP' ? 'Keluar Sementara' : 'Izin Pulang' }}</span>
          </div>
          <div class="flex justify-between items-center pt-2 border-t border-slate-200">
            <span class="text-slate-500">Status Gerbang Terkini:</span>
            <BaseBadge :status="scanResult.data.status" />
          </div>
        </div>
      </div>

      <div v-else-if="scanResult" class="space-y-4 text-slate-900 text-center py-4">
        <XCircle class="w-12 h-12 text-rose-600 mx-auto" />
        <h4 class="font-extrabold text-base text-rose-900">VALIDASI GERBANG GAGAL</h4>
        <p class="text-xs text-slate-600 max-w-xs mx-auto">{{ scanResult.message }}</p>
      </div>

      <template #footer>
        <BaseButton variant="primary" size="md" block @click="scanResult = null">
          Selesai / Scan Berikutnya
        </BaseButton>
      </template>
    </BaseModal>

    <!-- Logout Confirm -->
    <ConfirmDialog
      :show="showLogoutConfirm"
      title="Keluar Sesi Scanner Satpam"
      message="Apakah Anda yakin ingin keluar dari portal scanner satpam?"
      confirm-text="Ya, Keluar"
      variant="danger"
      @confirm="handleLogout"
      @cancel="showLogoutConfirm = false"
    />
  </div>
</template>

<script setup>
import { ref, nextTick, onUnmounted } from 'vue'
import { Html5Qrcode } from 'html5-qrcode'
import { useAuthStore } from '@/stores/auth'
import { usePermitStore } from '@/stores/permit'
import { useToast } from '@/composables/useToast'
import BaseModal from '@/components/ui/BaseModal.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue'
import ToastNotification from '@/components/ui/ToastNotification.vue'
import { Camera, QrCode, LogOut, CheckCircle2, XCircle } from 'lucide-vue-next'

const authStore = useAuthStore()
const permitStore = usePermitStore()
const toast = useToast()

const scanResult = ref(null)
const inputQrToken = ref('')
const isCameraActive = ref(false)
const showLogoutConfirm = ref(false)

let html5QrCode = null

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
    toast.info('Kamera scanner diaktifkan!')
  } catch (err) {
    toast.error('Gagal mengakses kamera fisik. Gunakan scan manual token.')
    isCameraActive.value = false
  }
}

const stopCamera = async () => {
  if (html5QrCode && isCameraActive.value) {
    try {
      await html5QrCode.stop()
      html5QrCode.clear()
    } catch (err) {}
    isCameraActive.value = false
  }
}

const scanToken = async (qrToken) => {
  if (!qrToken) return
  try {
    const res = await permitStore.scanQrToken(qrToken)
    scanResult.value = { success: true, message: res.message, data: res.data }
    inputQrToken.value = ''
  } catch (err) {
    scanResult.value = { success: false, message: err.response?.data?.message || 'QR Code tidak terdaftar atau sudah tidak berlaku' }
  }
}

const handleLogout = () => {
  stopCamera()
  showLogoutConfirm.value = false
  authStore.logout()
}

onUnmounted(() => {
  stopCamera()
})
</script>
