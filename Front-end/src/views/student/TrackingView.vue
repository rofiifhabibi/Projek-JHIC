<template>
  <div class="space-y-5">
    <!-- Header Page -->
    <div class="flex items-start justify-between gap-3">
      <div>
        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight leading-tight">
          Status & Riwayat Pelacakan
        </h2>
        <p class="text-xs text-slate-500 mt-1">Pantau status izin dan progres aduan BK milik Anda secara langsung.</p>
      </div>
      <button
        @click="refreshData"
        class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-xs font-semibold shadow-xs active:scale-95 transition"
      >
        <RefreshCw class="w-3.5 h-3.5" :class="{ 'animate-spin': isRefreshing }" />
        <span>Refresh</span>
      </button>
    </div>

    <!-- Active E-Permit Ticket Card -->
    <div
      v-if="permitStore.activePermit"
      class="bg-white rounded-2xl border shadow-sm overflow-hidden transition-all"
      :class="permitStore.activePermit.status === 'OVERDUE' ? 'border-rose-200 ring-1 ring-rose-200' : 'border-slate-200'"
    >
      <!-- Ticket Header Strip -->
      <div
        class="px-4 sm:px-5 py-3 border-b flex items-center justify-between"
        :class="permitStore.activePermit.status === 'OVERDUE' ? 'bg-rose-50/70 border-rose-100' : 'bg-slate-50/80 border-slate-100'"
      >
        <div class="flex items-center gap-2.5">
          <div
            class="w-7 h-7 rounded-lg flex items-center justify-center font-bold"
            :class="permitStore.activePermit.status === 'OVERDUE' ? 'bg-rose-100 text-rose-700' : 'bg-[#E8EFEA] text-[#355245]'"
          >
            <QrCode class="w-4 h-4" />
          </div>
          <div>
            <h3 class="text-xs font-bold text-slate-900 leading-none">Izin Keluar Aktif</h3>
            <span class="text-[10px] text-slate-400 font-medium">Presensi Digital Siswa</span>
          </div>
        </div>
        <BaseBadge :status="permitStore.activePermit.status" />
      </div>

      <!-- Ticket Body -->
      <div class="p-4 sm:p-5 space-y-3.5">
        <!-- Key Info Grid -->
        <div class="grid grid-cols-2 gap-2.5 text-xs">
          <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Jenis Izin</span>
            <span class="font-bold text-slate-800 mt-0.5 block truncate">
              {{ permitStore.activePermit.type === 'TEMP' ? 'Keluar Sementara' : 'Izin Pulang' }}
            </span>
          </div>
          <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Durasi Alokasi</span>
            <span class="font-bold text-slate-800 mt-0.5 block truncate">
              {{ permitStore.activePermit.duration_minutes || 30 }} Menit
            </span>
          </div>
        </div>

        <!-- Detail Lines -->
        <div class="space-y-1.5 text-xs border-t border-slate-100 pt-2.5">
          <div class="flex items-start justify-between gap-3">
            <span class="text-slate-400 text-[11px] shrink-0">Alasan:</span>
            <span class="font-semibold text-slate-800 text-right capitalize">
              {{ permitStore.activePermit.reason || '-' }}
            </span>
          </div>
          <div class="flex items-center justify-between gap-3">
            <span class="text-slate-400 text-[11px] shrink-0">Guru Pengampu:</span>
            <span class="font-semibold text-slate-800 text-right">
              {{ permitStore.activePermit.teacher?.name || '-' }}
            </span>
          </div>
        </div>

        <!-- Overdue Notice Banner if Late -->
        <div
          v-if="permitStore.activePermit.status === 'OVERDUE'"
          class="p-3 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-2.5 text-xs text-rose-900"
        >
          <AlertTriangle class="w-4 h-4 text-rose-600 shrink-0 mt-0.5 animate-pulse" />
          <div class="leading-relaxed">
            <strong class="font-bold text-rose-950">Waktu Izin Berakhir!</strong>
            <p class="text-[11px] text-rose-700 mt-0.5">Anda tercatat terlambat oleh sistem. Harap segera kembali ke ruang kelas.</p>
          </div>
        </div>

        <!-- Action Button -->
        <router-link to="/student/permit/pass" class="block pt-0.5">
          <BaseButton variant="primary" size="md" block>
            <template #icon-left><QrCode class="w-4 h-4" /></template>
            Buka Tiket QR Pass Digital
          </BaseButton>
        </router-link>
      </div>
    </div>

    <!-- Empty State for Permit -->
    <div v-else class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center justify-between gap-3">
      <div class="space-y-0.5">
        <p class="text-xs font-bold text-slate-800">Tidak Ada Izin Keluar Aktif</p>
        <p class="text-[11px] text-slate-500">Anda tercatat mengikuti KBM secara normal di kelas.</p>
      </div>
      <router-link to="/student/permit/create" class="shrink-0">
        <BaseButton variant="outline" size="sm">
          Ajukan Izin
        </BaseButton>
      </router-link>
    </div>

    <!-- BK Reports Feed -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm space-y-4">
      <div class="flex items-center justify-between">
        <h3 class="text-xs font-bold text-slate-900 flex items-center gap-2">
          <ShieldAlert class="w-4 h-4 text-teal-600" />
          Riwayat Pengaduan Care BK
        </h3>
        <span class="text-[10px] text-slate-400 font-semibold">{{ reportStore.myReports?.length || 0 }} Aduan</span>
      </div>

      <div v-if="reportStore.myReports && reportStore.myReports.length > 0" class="space-y-2.5">
        <div
          v-for="rep in reportStore.myReports"
          :key="rep.report_id"
          class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5"
        >
          <div class="space-y-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
              <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-teal-50 text-teal-800 border border-teal-200/60 uppercase">
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
import { ref, onMounted } from 'vue'
import { useReportStore } from '@/stores/report'
import { usePermitStore } from '@/stores/permit'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import { RefreshCw, ShieldAlert, QrCode, AlertTriangle } from 'lucide-vue-next'

const reportStore = useReportStore()
const permitStore = usePermitStore()
const isRefreshing = ref(false)

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
