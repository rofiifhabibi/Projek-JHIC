<template>
  <div class="min-h-screen bg-slate-950 text-white flex flex-col font-sans">
    <ToastNotification />

    <!-- Top Bar -->
    <header class="bg-slate-900 border-b border-slate-800 p-4 sticky top-0 z-30 shadow-xl">
      <div class="max-w-4xl mx-auto flex justify-between items-center">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-[#355245] rounded-xl flex items-center justify-center font-extrabold text-sm text-white shadow-md border border-emerald-500/20">
            SP
          </div>
          <div>
            <div class="flex items-center gap-2">
              <h1 class="font-extrabold text-base tracking-wide text-white">Portal Web Scanner Satpam</h1>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold border border-emerald-500/30">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                Siaga
              </span>
            </div>
            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mt-0.5">Pos Gerbang Utama • SMKN 2 Depok Sleman</p>
          </div>
        </div>
        <button
          @click="showLogoutConfirm = true"
          class="text-xs bg-slate-800 hover:bg-slate-700 active:scale-95 px-3.5 py-2 rounded-xl font-bold border border-slate-700 transition-all text-white flex items-center gap-1.5 shadow-sm"
        >
          <LogOut class="w-3.5 h-3.5" />
          <span>Keluar</span>
        </button>
      </div>
    </header>

    <!-- Main Scanner Interface -->
    <div class="flex-1 relative flex flex-col items-center justify-center p-4 sm:p-6 max-w-lg mx-auto w-full space-y-6">
      
      <!-- Camera Reader Container -->
      <div class="w-full bg-slate-900 rounded-3xl border border-slate-800 p-6 flex flex-col items-center shadow-2xl relative overflow-hidden space-y-5">
        <div class="flex items-center justify-between w-full border-b border-slate-800/80 pb-3">
          <div class="flex items-center gap-2">
            <Camera class="w-4 h-4 text-emerald-400" />
            <span class="text-xs font-bold uppercase tracking-wider text-slate-300">Pemindai Barcode Kamera</span>
          </div>
          <button
            @click="toggleCamera"
            class="text-[11px] font-bold px-3 py-1.5 rounded-xl border transition-all active:scale-95 flex items-center gap-1.5"
            :class="isCameraActive ? 'border-rose-500/50 bg-rose-500/15 text-rose-300 hover:bg-rose-500/25' : 'border-emerald-500/50 bg-emerald-500/15 text-emerald-300 hover:bg-emerald-500/25'"
          >
            <span class="w-1.5 h-1.5 rounded-full" :class="isCameraActive ? 'bg-rose-400' : 'bg-emerald-400'"></span>
            {{ isCameraActive ? 'Matikan Kamera' : 'Nyalakan Kamera' }}
          </button>
        </div>

        <!-- Video Reader Element with Viewfinder Styling -->
        <div class="w-full min-h-[240px] bg-slate-950 rounded-2xl border border-slate-800/90 overflow-hidden flex items-center justify-center relative shadow-inner">
          <div id="qr-reader" class="w-full"></div>
          <div v-if="!isCameraActive" class="text-center p-6 text-slate-500 space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center mx-auto text-slate-600">
              <QrCode class="w-8 h-8 text-slate-500" />
            </div>
            <p class="text-xs font-medium text-slate-400">Klik "Nyalakan Kamera" untuk memindai QR Code di layar HP siswa.</p>
            <p class="text-[11px] text-slate-600">Atau pilih simulasi uji cepat di bawah.</p>
          </div>
        </div>

        <!-- Quick Simulation Selector (Clean Security Post Station Style) -->
        <div class="w-full space-y-2.5 bg-slate-950/70 p-4 rounded-2xl border border-slate-800/80">
          <div class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider text-slate-400 px-1">
            <span>UJI SIMULASI GERBANG (3 SKENARIO)</span>
            <span class="text-emerald-400 font-mono text-[10px]">1-Click Scan</span>
          </div>

          <div class="grid grid-cols-1 gap-2">
            <!-- Skenario 1: Siti (TEMP Normal) -->
            <button
              @click="scanToken('QR-ACTIVE-XII-RPL1-002')"
              class="w-full bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-emerald-500/60 p-3 rounded-xl transition-all text-left flex items-center justify-between group"
            >
              <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center font-bold text-xs border border-emerald-500/20">
                  SR
                </div>
                <div>
                  <div class="text-xs font-bold text-slate-200 group-hover:text-emerald-300 transition-colors">
                    Siti Rahmawati (XII RPL 1)
                  </div>
                  <div class="text-[10px] text-slate-500">Izin Sementara UKS • Token Aktif</div>
                </div>
              </div>
              <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                ACTIVE
              </span>
            </button>

            <!-- Skenario 2: Andi (Overdue) -->
            <button
              @click="scanToken('QR-OVERDUE-XII-TKJ2-003')"
              class="w-full bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-rose-500/60 p-3 rounded-xl transition-all text-left flex items-center justify-between group"
            >
              <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-rose-500/10 text-rose-400 flex items-center justify-center font-bold text-xs border border-rose-500/20">
                  AS
                </div>
                <div>
                  <div class="text-xs font-bold text-slate-200 group-hover:text-rose-300 transition-colors">
                    Andi Saputra (XII TKJ 2)
                  </div>
                  <div class="text-[10px] text-slate-500">Izin Sementara TU • Durasi Terlampaui</div>
                </div>
              </div>
              <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-rose-500/20 text-rose-300 border border-rose-500/30">
                OVERDUE
              </span>
            </button>

            <!-- Skenario 3: Rizky (Exit School) -->
            <button
              @click="scanToken('QR-EXIT-X-AK3-005')"
              class="w-full bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-amber-500/60 p-3 rounded-xl transition-all text-left flex items-center justify-between group"
            >
              <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center font-bold text-xs border border-amber-500/20">
                  RP
                </div>
                <div>
                  <div class="text-xs font-bold text-slate-200 group-hover:text-amber-300 transition-colors">
                    Rizky Pratama (X AK 3)
                  </div>
                  <div class="text-[10px] text-slate-500">Izin Pulang Sekolah • Sakit Demam</div>
                </div>
              </div>
              <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/30">
                PULANG
              </span>
            </button>
          </div>
        </div>

        <!-- Manual Token Form -->
        <form @submit.prevent="scanToken(inputQrToken)" class="w-full space-y-2">
          <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Atau Masukkan / Tempel Token QR</label>
          <div class="flex gap-2">
            <input
              v-model="inputQrToken"
              type="text"
              placeholder="Paste QR Token UUID..."
              class="flex-1 bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs outline-none focus:border-emerald-500 text-white font-mono placeholder:text-slate-600 transition"
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
import { ref, onUnmounted } from 'vue'
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
    html5QrCode = new Html5Qrcode("qr-reader")
    await html5QrCode.start(
      { facingMode: "environment" },
      { fps: 10, qrbox: { width: 220, height: 220 } },
      (decodedText) => {
        scanToken(decodedText)
      },
      () => {}
    )
    isCameraActive.value = true
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
