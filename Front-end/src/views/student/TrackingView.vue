<template>
  <div class="space-y-5">
    <!-- Header Page -->
    <div class="flex items-start justify-between gap-3">
      <div>
        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight leading-tight">
          Status & Riwayat Perizinan
        </h2>
        <p class="text-xs text-slate-500 mt-1">Pantau status izin dan tindak lanjut konseling BK kamu di sini.</p>
      </div>
      <button
        @click="refreshData"
        class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-xs font-semibold shadow-xs active:scale-95 transition"
      >
        <RefreshCw class="w-3.5 h-3.5" :class="{ 'animate-spin': isRefreshing }" />
        <span>Segarkan</span>
      </button>
    </div>

    <!-- Active Permit Card -->
    <div
      v-if="permitStore.activePermit"
      class="bg-white rounded-2xl border shadow-sm overflow-hidden transition-all"
      :class="permitStore.activePermit.status === 'ALPHA' ? 'border-rose-300 ring-2 ring-rose-200' : (permitStore.activePermit.status === 'OVERDUE' ? 'border-rose-200 ring-1 ring-rose-200' : 'border-slate-200')"
    >
      <!-- Card Header Strip -->
      <div
        class="px-4 sm:px-5 py-3 border-b flex items-center justify-between"
        :class="permitStore.activePermit.status === 'ALPHA' || permitStore.activePermit.status === 'OVERDUE' ? 'bg-rose-50/80 border-rose-100' : 'bg-slate-50/80 border-slate-100'"
      >
        <div class="flex items-center gap-2.5">
          <div
            class="w-8 h-8 rounded-lg flex items-center justify-center font-bold"
            :class="permitStore.activePermit.status === 'ALPHA' || permitStore.activePermit.status === 'OVERDUE' ? 'bg-rose-100 text-rose-700' : 'bg-[#E8EFEA] text-[#355245]'"
          >
            <AlertOctagon v-if="permitStore.activePermit.status === 'ALPHA'" class="w-4 h-4 text-rose-700" />
            <QrCode v-else class="w-4 h-4" />
          </div>
          <div>
            <h3 class="text-sm font-bold text-slate-900 leading-tight">
              {{ permitStore.activePermit.status === 'ALPHA' ? 'Peringatan: Ditandai Alpha' : (permitStore.activePermit.status === 'APPROVED' ? 'Izin Disetujui (Siap ke Gerbang)' : (permitStore.activePermit.status === 'PENDING' ? 'Menunggu Persetujuan Guru' : 'Surat Izin Aktif')) }}
            </h3>
            <span class="text-xs text-slate-500 font-medium">Perizinan Siswa</span>
          </div>
        </div>
        <BaseBadge :status="permitStore.activePermit.status" />
      </div>

      <!-- Card Body -->
      <div class="p-4 sm:p-5 space-y-3.5">
        <!-- Disciplinary Alpha Notice if ALPHA -->
        <div
          v-if="permitStore.activePermit.status === 'ALPHA'"
          class="p-3.5 bg-rose-50 border border-rose-200 rounded-xl space-y-2 text-xs text-rose-900"
        >
          <div class="flex items-center gap-2 font-black text-rose-950">
            <AlertOctagon class="w-4 h-4 text-rose-600 shrink-0" />
            Status: Ditandai Alpha
          </div>
          <p class="text-xs text-rose-800 leading-relaxed">
            Izin ini telah ditandai Alpha oleh guru pengajar (<strong class="text-rose-950">{{ permitStore.activePermit.teacher?.name || 'Guru' }}</strong>).
          </p>
          <div
            v-if="pendingClarification"
            class="p-2.5 bg-amber-50 rounded-lg border border-amber-200 text-xs text-amber-900 font-semibold flex items-center gap-2"
          >
            <Clock class="w-4 h-4 text-amber-600 shrink-0 animate-spin" />
            <span>Klarifikasi kamu telah terkirim (Status: {{ pendingClarification.status === 'IN_PROGRESS' ? 'Sedang Ditangani' : 'Menunggu Tanggapan Guru BK' }}).</span>
          </div>
          <div
            v-else
            class="p-2.5 bg-white/80 rounded-lg border border-rose-200 text-xs text-rose-800 font-medium flex items-center gap-2"
          >
            <ShieldAlert class="w-4 h-4 text-rose-600 shrink-0" />
            <span>Harap segera kirim penjelasan ke BK atau temui guru yang bersangkutan.</span>
          </div>
        </div>

        <!-- Key Info Grid -->
        <div class="grid grid-cols-2 gap-2.5 text-xs">
          <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
            <span class="text-xs font-semibold text-slate-500 block">Jenis Izin</span>
            <span class="font-bold text-slate-800 mt-0.5 block truncate text-sm">
              {{ permitStore.activePermit.type === 'TEMP' ? 'Keluar Sementara' : 'Izin Pulang' }}
            </span>
          </div>
          <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
            <span class="text-xs font-semibold text-slate-500 block">Batas Waktu</span>
            <span class="font-bold text-slate-800 mt-0.5 block truncate text-sm">
              {{ permitStore.activePermit.duration_minutes || 30 }} Menit
            </span>
          </div>
        </div>

        <!-- Detail Lines -->
        <div class="space-y-1.5 text-xs border-t border-slate-100 pt-2.5">
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
            <p class="text-xs text-sky-700 mt-0.5">Tunjukkan kode QR ke petugas satpam di gerbang sekolah saat hendak keluar.</p>
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
            <p class="text-xs text-rose-700 mt-0.5">Batas waktu izin kamu sudah habis. Harap segera kembali ke ruang kelas dan lapor ke guru pengajar.</p>
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
        <router-link v-else to="/student/permit/pass" class="block pt-0.5">
          <BaseButton variant="primary" size="md" block>
            <template #icon-left><QrCode class="w-4 h-4" /></template>
            Tampilkan Kode QR Izin
          </BaseButton>
        </router-link>
      </div>
    </div>

    <!-- Empty State for Permit -->
    <div v-else class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center justify-between gap-3">
      <div class="space-y-0.5">
        <p class="text-xs font-bold text-slate-800">Tidak Ada Izin Keluar Aktif</p>
        <p class="text-xs text-slate-500">Kamu terdaftar sedang berada di dalam kelas.</p>
      </div>
      <router-link to="/student/permit/create" class="shrink-0">
        <BaseButton variant="outline" size="sm">
          Buat Izin
        </BaseButton>
      </router-link>
    </div>

    <!-- BK Reports Feed -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm space-y-4">
      <div class="flex items-center justify-between">
        <h3 class="text-xs font-bold text-slate-900 flex items-center gap-2">
          <ShieldAlert class="w-4 h-4 text-teal-600" />
          Riwayat Pengaduan & Konseling BK
        </h3>
        <span class="text-xs text-slate-500 font-semibold">{{ reportStore.myReports?.length || 0 }} Laporan</span>
      </div>

      <div v-if="reportStore.myReports && reportStore.myReports.length > 0" class="space-y-2.5">
        <div
          v-for="rep in reportStore.myReports"
          :key="rep.report_id"
          class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5"
        >
          <div class="space-y-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
              <span class="px-2 py-0.5 rounded text-xs font-bold bg-teal-50 text-teal-800 border border-teal-200/60 uppercase">
                {{ rep.category }}
              </span>
              <h4 class="font-bold text-xs text-slate-900 truncate">{{ rep.title }}</h4>
            </div>
            <p class="text-xs text-slate-500 line-clamp-2">{{ rep.description }}</p>
          </div>

          <BaseBadge :status="rep.status" class="self-start sm:self-center shrink-0" />
        </div>
      </div>

      <EmptyState
        v-else
        title="Belum Ada Aduan BK"
        description="Anda belum pernah mengirimkan laporan konseling ke Guru BK."
      />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useReportStore } from '@/stores/report'
import { usePermitStore } from '@/stores/permit'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import { RefreshCw, ShieldAlert, QrCode, AlertTriangle, AlertOctagon, HeartHandshake, Clock, CheckCircle } from 'lucide-vue-next'

const reportStore = useReportStore()
const permitStore = usePermitStore()
const isRefreshing = ref(false)

const pendingClarification = computed(() => {
  return reportStore.myReports?.find(r => 
    (r.category === 'OTHERS' || r.title?.toLowerCase().includes('klarifikasi')) &&
    r.status !== 'RESOLVED'
  )
})

const refreshData = async () => {
  isRefreshing.value = true
  try {
    await Promise.allSettled([
      reportStore.fetchMyReports(),
      permitStore.fetchActivePermit()
    ])
  } finally {
    setTimeout(() => {
      isRefreshing.value = false
    }, 400)
  }
}

onMounted(() => {
  refreshData()
})
</script>
