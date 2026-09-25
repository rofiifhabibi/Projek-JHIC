<template>
  <div class="min-h-screen bg-slate-950 text-white flex flex-col font-sans">
    <ToastNotification />

    <!-- Top Bar -->
    <header class="bg-slate-900 border-b border-slate-800 p-4 flex justify-between items-center shadow-lg">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-[#355245] rounded-xl flex items-center justify-center font-extrabold text-xs text-white shadow-md">
          SP
        </div>
        <div>
          <h1 class="font-black text-base tracking-wide text-white">Portal Web Scanner Satpam</h1>
          <p class="text-[10px] text-emerald-400 font-semibold uppercase tracking-wider">POS GERBANG UTAMA SMKN 2 DEPOK</p>
        </div>
      </div>
      <button @click="showLogoutConfirm = true" class="text-xs bg-slate-800 hover:bg-slate-700 px-3.5 py-2 rounded-xl font-bold border border-slate-700 transition-all text-white flex items-center gap-1.5">
        <LogOut class="w-3.5 h-3.5" />
        <span>Keluar</span>
      </button>
    </header>

    <!-- Main Scanner Interface -->
    <div class="flex-1 relative flex flex-col items-center justify-center p-4 max-w-lg mx-auto w-full space-y-6">
      
      <!-- Camera Reader Container -->
      <div class="w-full bg-slate-900 rounded-3xl border-2 border-slate-800 p-6 flex flex-col items-center shadow-2xl relative overflow-hidden space-y-4">
        <div class="flex items-center justify-between w-full border-b border-slate-800 pb-3">
          <div class="flex items-center gap-2">
            <Camera class="w-4 h-4 text-emerald-400" />
            <span class="text-xs font-bold uppercase tracking-wider text-slate-300">Pemindai Barcode Kamera</span>
          </div>
          <button
            @click="toggleCamera"
            class="text-[11px] font-bold px-3 py-1 rounded-lg border transition"
            :class="isCameraActive ? 'border-rose-500 bg-rose-500/20 text-rose-300' : 'border-emerald-500 bg-emerald-500/20 text-emerald-300'"
          >
            {{ isCameraActive ? 'Matikan Kamera' : 'Nyalakan Kamera' }}
          </button>
        </div>

        <!-- Video Reader Element -->
        <div class="w-full min-h-[240px] bg-slate-950 rounded-2xl border border-slate-800 overflow-hidden flex items-center justify-center relative">
          <div id="qr-reader" class="w-full"></div>
          <div v-if="!isCameraActive" class="text-center p-6 text-slate-500 space-y-2">
            <QrCode class="w-12 h-12 mx-auto text-slate-700" />
            <p class="text-xs font-medium">Klik "Nyalakan Kamera" untuk mulai memindai QR Code di HP siswa.</p>
          </div>
        </div>

        <!-- Quick Test Buttons (Dev Mode) -->
        <div class="w-full space-y-2 bg-slate-950 p-4 rounded-2xl border border-slate-800/80">
          <p class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-400 text-center mb-1">
            UJI SCAN TOKEN (TEST SEED DATA)
          </p>
          <div class="grid grid-cols-1 gap-2">
            <button
              @click="scanToken('QR-ACTIVE-XII-RPL1-002')"
              class="w-full bg-slate-800 hover:bg-emerald-950 border border-slate-700 hover:border-emerald-500 text-slate-200 hover:text-emerald-300 text-xs font-bold py-2 px-3 rounded-xl transition-all flex justify-between items-center"
            >
              <span>Scan QR Siti (Active TEMP)</span>
              <span class="text-[10px] bg-emerald-500/20 text-emerald-300 px-2 py-0.5 rounded">TEMP</span>
            </button>
            <button
              @click="scanToken('QR-OVERDUE-XII-TKJ2-003')"
              class="w-full bg-slate-800 hover:bg-red-950 border border-slate-700 hover:border-red-500 text-slate-200 hover:text-red-300 text-xs font-bold py-2 px-3 rounded-xl transition-all flex justify-between items-center"
            >
              <span>Scan QR Andi (Overdue TEMP)</span>
              <span class="text-[10px] bg-red-500/20 text-red-300 px-2 py-0.5 rounded">OVERDUE</span>
            </button>
            <button
              @click="scanToken('QR-EXIT-X-AK3-005')"
              class="w-full bg-slate-800 hover:bg-amber-950 border border-slate-700 hover:border-amber-500 text-slate-200 hover:text-amber-300 text-xs font-bold py-2 px-3 rounded-xl transition-all flex justify-between items-center"
            >
              <span>Scan QR Rizky (Izin Pulang)</span>
              <span class="text-[10px] bg-amber-500/20 text-amber-300 px-2 py-0.5 rounded">EXIT</span>
            </button>
          </div>
        </div>

        <!-- Manual Token Form -->
        <form @submit.prevent="scanToken(inputQrToken)" class="w-full space-y-2">
          <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Atau Masukkan / Paste Token QR Manual</label>
          <div class="flex gap-2">
            <input
              v-model="inputQrToken"
              type="text"
              placeholder="Paste QR Token UUID..."
              class="flex-1 bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs outline-none focus:border-emerald-500 text-white font-mono"
            />
            <BaseButton type="submit" variant="primary" size="sm" :disabled="!inputQrToken">
              Scan Token
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
