<template>
  <div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-[#355245] to-[#273e34] rounded-3xl p-6 text-white shadow-sm border border-emerald-500/20 relative overflow-hidden">
      <div class="relative z-10 space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-emerald-200 text-xs font-semibold backdrop-blur-md">
          <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
          {{ greetingText }}
        </div>
        <h2 class="text-2xl sm:text-3xl font-black tracking-tight">
          Selamat datang, {{ authStore.userName }}!
        </h2>
        <p class="text-xs sm:text-sm text-slate-200">
          Kelas: <span class="font-bold text-white">{{ authStore.userClass || 'XII RPL 1' }}</span> • SMKN 2 Depok Sleman
        </p>
      </div>
    </div>

    <!-- Active Permit / Disciplinary Card -->
    <div
      v-if="permitStore.activePermit"
      class="bg-white rounded-2xl border shadow-sm overflow-hidden transition-all"
      :class="permitStore.activePermit.status === 'ALPHA' || permitStore.activePermit.status === 'REJECTED' ? 'border-rose-300 ring-2 ring-rose-200' : (permitStore.activePermit.status === 'OVERDUE' ? 'border-rose-200 ring-1 ring-rose-200' : 'border-slate-200')"
    >
      <!-- Card Header Strip -->
      <div
        class="px-4 sm:px-5 py-3 border-b flex items-center justify-between"
        :class="permitStore.activePermit.status === 'ALPHA' || permitStore.activePermit.status === 'OVERDUE' || permitStore.activePermit.status === 'REJECTED' ? 'bg-rose-50/80 border-rose-100' : 'bg-slate-50/80 border-slate-100'"
      >
        <div class="flex items-center gap-2.5">
          <div
            class="w-8 h-8 rounded-lg flex items-center justify-center font-bold"
            :class="permitStore.activePermit.status === 'ALPHA' || permitStore.activePermit.status === 'OVERDUE' || permitStore.activePermit.status === 'REJECTED' ? 'bg-rose-100 text-rose-700' : 'bg-[#E8EFEA] text-[#355245]'"
          >
            <AlertOctagon v-if="permitStore.activePermit.status === 'ALPHA'" class="w-4 h-4 text-rose-700" />
            <XCircle v-else-if="permitStore.activePermit.status === 'REJECTED'" class="w-4 h-4 text-rose-700" />
            <QrCode v-else class="w-4 h-4" />
          </div>
          <div>
            <h3 class="text-sm font-bold text-slate-900 leading-tight">
              {{ permitStore.activePermit.status === 'ALPHA' ? 'Peringatan: Ditandai Alpha' : (permitStore.activePermit.status === 'REJECTED' ? 'Izin Tidak Disetujui' : 'Surat Izin Aktif') }}
            </h3>
            <span class="text-xs text-slate-500 font-medium">Perizinan Siswa</span>
          </div>
        </div>
        <BaseBadge :status="permitStore.activePermit.status" />
      </div>

      <!-- Card Body -->
      <div class="p-4 sm:p-5 space-y-3.5">
        <!-- Disciplinary Alpha Alert Banner -->
        <div
          v-if="permitStore.activePermit.status === 'ALPHA'"
          class="p-4 bg-rose-50 border border-rose-200 rounded-xl space-y-2 text-rose-950"
        >
          <div class="flex items-center gap-2 text-xs font-black text-rose-800 uppercase tracking-wide">
            <AlertTriangle class="w-4 h-4 text-rose-600 shrink-0" />
            Status: Ditandai Alpha
          </div>
          <p class="text-xs text-rose-800 leading-relaxed">
            Izin kamu ditandai <strong>Alpha</strong> oleh guru pengajar (<strong class="text-rose-950">{{ permitStore.activePermit.teacher?.name || 'Guru' }}</strong>) karena belum kembali ke kelas melebihi batas waktu.
          </p>
          <div class="p-2.5 bg-white/90 rounded-lg border border-rose-200 text-xs text-rose-900 font-medium flex items-center gap-2">
            <ShieldAlert class="w-4 h-4 text-rose-600 shrink-0" />
            <span>Tindakan: Segera temui guru yang bersangkutan atau ke ruang BK untuk konfirmasi.</span>
          </div>
        </div>

        <!-- Rejected Alert Banner -->
        <div
          v-else-if="permitStore.activePermit.status === 'REJECTED'"
          class="p-4 bg-rose-50 border border-rose-200 rounded-xl space-y-2 text-rose-950"
        >
          <div class="flex items-center gap-2 text-xs font-black text-rose-800 uppercase tracking-wide">
            <XCircle class="w-4 h-4 text-rose-600 shrink-0" />
            Izin Tidak Disetujui Guru
          </div>
          <p class="text-xs text-rose-800 leading-relaxed">
            Permohonan izin kamu tidak disetujui oleh <strong class="text-rose-950">{{ permitStore.activePermit.teacher?.name || 'Guru Pengajar' }}</strong>. Silakan tetap berada di dalam kelas untuk mengikuti pelajaran.
          </p>
        </div>

        <!-- Key Info Grid -->
        <div class="grid grid-cols-2 gap-2.5 text-xs">
          <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
            <span class="text-xs font-semibold text-slate-500 block">Jenis Izin</span>
            <span class="font-bold text-slate-800 mt-0.5 block truncate text-sm">
              {{ permitStore.activePermit.type === 'TEMP' ? 'Keluar Sementara' : 'Izin Pulang' }}
            </span>
          </div>
          <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
            <span class="text-xs font-semibold text-slate-500 block">Batas Waktu</span>
            <span class="font-bold text-slate-800 mt-0.5 block truncate text-sm">
              {{ permitStore.activePermit.duration_minutes || 30 }} Menit
            </span>
          </div>
        </div>

        <!-- Detail Lines -->
        <div class="space-y-2 text-xs border-t border-slate-100 pt-3">
          <div class="flex items-center justify-between gap-3">
            <span class="text-slate-500 text-xs shrink-0">ID Surat Izin:</span>
            <span class="font-mono font-bold text-slate-800 text-right">#{{ permitStore.activePermit.request_id }}</span>
          </div>
          <div class="flex items-start justify-between gap-3">
            <span class="text-slate-500 text-xs shrink-0">Alasan:</span>
            <span class="font-semibold text-slate-800 text-right capitalize">
              {{ permitStore.activePermit.reason || '-' }}
            </span>
          </div>
          <div class="flex items-center justify-between gap-3">
            <span class="text-slate-500 text-xs shrink-0">Guru yang Mengajar:</span>
            <span class="font-semibold text-slate-800 text-right">
              {{ permitStore.activePermit.teacher?.name || '-' }}
            </span>
          </div>
        </div>

        <!-- Approved Notice Banner if Approved by Teacher -->
        <div
          v-if="permitStore.activePermit.status === 'APPROVED'"
          class="p-3 bg-sky-50 border border-sky-200 rounded-xl flex items-start gap-2.5 text-xs text-sky-900"
        >
          <CheckCircle class="w-4 h-4 text-sky-600 shrink-0 mt-0.5" />
          <div class="leading-relaxed">
            <strong class="font-bold text-sky-950">Izin Disetujui Guru!</strong>
            <p class="text-xs text-sky-700 mt-0.5">Tunjukkan kode QR izin ke satpam di gerbang sebelum keluar sekolah.</p>
          </div>
        </div>

        <!-- Overdue Notice Banner if Late -->
        <div
          v-if="permitStore.activePermit.status === 'OVERDUE'"
          class="p-3 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-2.5 text-xs text-rose-900"
        >
          <AlertTriangle class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" />
          <div class="leading-relaxed">
            <strong class="font-bold text-rose-950">Waktu Izin Berakhir!</strong>
            <p class="text-xs text-rose-700 mt-0.5">Batas waktu izin kamu sudah habis. Segera kembali ke kelas dan lapor ke guru pengajar.</p>
          </div>
        </div>

        <!-- Action Button -->
        <div v-if="permitStore.activePermit.status === 'ALPHA'" class="pt-1 flex flex-col sm:flex-row gap-2">
          <div v-if="pendingClarification" class="flex-1">
            <BaseButton variant="secondary" size="md" block disabled>
              <template #icon-left><Clock class="w-4 h-4 text-amber-600" /></template>
              Klarifikasi Terkirim (Menunggu BK)
            </BaseButton>
          </div>
          <router-link v-else :to="'/student/report/create?type=alpha&request_id=' + permitStore.activePermit.request_id" class="flex-1">
            <BaseButton variant="danger" size="md" block>
              <template #icon-left><HeartHandshake class="w-4 h-4" /></template>
              Kirim Klarifikasi ke BK
            </BaseButton>
          </router-link>
          <router-link to="/student/permit/pass" class="flex-1">
            <BaseButton variant="outline" size="md" block>
              Lihat Detail Izin
            </BaseButton>
          </router-link>
        </div>
        <div v-else-if="permitStore.activePermit.status === 'REJECTED'" class="pt-1 flex flex-col sm:flex-row gap-2">
          <router-link to="/student/permit/create" class="flex-1">
            <BaseButton variant="primary" size="md" block>
              <template #icon-left><FilePlus class="w-4 h-4" /></template>
              Ajukan Izin Baru
            </BaseButton>
          </router-link>
          <router-link to="/student/permit/pass" class="flex-1">
            <BaseButton variant="outline" size="md" block>
              Lihat Alasan Ditolak
            </BaseButton>
          </router-link>
        </div>
        <div v-else-if="permitStore.activePermit.status === 'PENDING' || permitStore.activePermit.status === 'APPROVED'" class="pt-1 flex flex-col sm:flex-row gap-2">
          <router-link to="/student/permit/pass" class="flex-1">
            <BaseButton variant="primary" size="md" block>
              <template #icon-left>
                <Clock v-if="permitStore.activePermit.status === 'PENDING'" class="w-4 h-4" />
                <QrCode v-else class="w-4 h-4" />
              </template>
              {{ permitStore.activePermit.status === 'PENDING' ? 'Menunggu Persetujuan Guru' : 'Tampilkan Kode QR Izin' }}
            </BaseButton>
          </router-link>
          <div class="shrink-0">
            <BaseButton
              variant="outline"
              size="md"
              class="text-rose-600 border-rose-200 hover:bg-rose-50 w-full sm:w-auto"
              @click="showCancelConfirm = true"
            >
              Batalkan
            </BaseButton>
          </div>
        </div>
        <router-link v-else to="/student/permit/pass" class="block pt-0.5">
          <BaseButton variant="primary" size="md" block>
            <template #icon-left><QrCode class="w-4 h-4" /></template>
            Tampilkan Kode QR Izin
          </BaseButton>
        </router-link>
      </div>
    </div>

    <!-- Normal Presence Banner -->
    <div v-else class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
      <div class="space-y-1">
        <div class="flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
          <span class="text-xs font-bold uppercase tracking-wider text-emerald-800">Status Belajar</span>
        </div>
        <h3 class="text-base sm:text-lg font-bold text-slate-900">Sedang Belajar di Kelas</h3>
        <p class="text-xs text-slate-500">Kamu tidak memiliki izin keluar aktif saat ini.</p>
      </div>
      <router-link to="/student/permit/create" class="shrink-0">
        <BaseButton variant="secondary" size="sm">
          Buat Izin
        </BaseButton>
      </router-link>
    </div>

    <!-- Quick Action Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <router-link to="/student/permit/create" class="group">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:border-[#355245] transition space-y-3">
          <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center group-hover:scale-105 transition">
            <FilePlus class="w-5 h-5" />
          </div>
          <div>
            <h4 class="font-bold text-sm text-slate-900 group-hover:text-[#355245]">Buat Surat Izin</h4>
            <p class="text-xs text-slate-500 mt-0.5">Izin keluar kelas sementara ke UKS/TU atau izin pulang sekolah.</p>
          </div>
        </div>
      </router-link>

      <router-link to="/student/report/create" class="group">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:border-[#355245] transition space-y-3">
          <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center group-hover:scale-105 transition">
            <ShieldAlert class="w-5 h-5" />
          </div>
          <div>
            <h4 class="font-bold text-sm text-slate-900 group-hover:text-[#355245]">Layanan Konseling BK</h4>
            <p class="text-xs text-slate-500 mt-0.5">Kirim pesan pengaduan atau konseling rahasia ke Guru BK.</p>
          </div>
        </div>
      </router-link>
    </div>

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
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { usePermitStore } from '@/stores/permit'
import { useReportStore } from '@/stores/report'
import { useToast } from '@/composables/useToast'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue'
import { QrCode, FilePlus, ShieldAlert, AlertTriangle, AlertOctagon, HeartHandshake, Clock, CheckCircle, XCircle } from 'lucide-vue-next'

const authStore = useAuthStore()
const permitStore = usePermitStore()
const reportStore = useReportStore()
const toast = useToast()

const showCancelConfirm = ref(false)
const isCancelling = ref(false)

const handleCancelPermit = async () => {
  if (!permitStore.activePermit) return
  isCancelling.value = true
  try {
    await permitStore.cancelPermit(permitStore.activePermit.request_id)
    toast.success('Permohonan izin berhasil dibatalkan.')
    showCancelConfirm.value = false
  } catch (err) {
    toast.error(err.response?.data?.message || 'Gagal membatalkan izin.')
  } finally {
    isCancelling.value = false
  }
}

const pendingClarification = computed(() => {
  return reportStore.myReports?.find(r => 
    (r.category === 'OTHERS' || r.title?.toLowerCase().includes('klarifikasi')) &&
    r.status !== 'RESOLVED'
  )
})

const greetingText = computed(() => {
  const hour = new Date().getHours()
  if (hour < 11) return 'Selamat Pagi'
  if (hour < 15) return 'Selamat Siang'
  if (hour < 18) return 'Selamat Sore'
  return 'Selamat Malam'
})

onMounted(() => {
  permitStore.fetchActivePermit()
  reportStore.fetchMyReports()
})
</script>
