<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Status & Riwayat Pelacakan</h2>
        <p class="text-xs text-slate-500 mt-0.5">Pantau status izin dan progres aduan BK milik Anda secara langsung.</p>
      </div>
      <BaseButton variant="outline" size="sm" @click="refreshData">
        <template #icon-left><RefreshCw class="w-3.5 h-3.5" /></template>
        Refresh
      </BaseButton>
    </div>

    <!-- Active E-Permit Section -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
      <div class="flex items-center justify-between">
        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
          <QrCode class="w-4 h-4 text-[#355245]" />
          Status Izin E-Permit Terkini
        </h3>
        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Presensi Kelas</span>
      </div>

      <div
        v-if="permitStore.activePermit"
        class="p-5 rounded-2xl border space-y-3 transition-all"
        :class="permitStore.activePermit.status === 'OVERDUE' ? 'border-rose-300 bg-rose-50/40' : 'border-slate-200 bg-slate-50/60'"
      >
        <div class="flex items-start justify-between gap-3">
          <div>
            <div class="flex items-center gap-2">
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#E8EFEA] text-[#355245] uppercase">
                {{ permitStore.activePermit.type === 'TEMP' ? 'Keluar Sementara' : 'Izin Pulang' }}
              </span>
              <span class="text-xs text-slate-500 font-medium">
                Durasi: <strong>{{ permitStore.activePermit.duration_minutes || 30 }} Menit</strong>
              </span>
            </div>
            <p class="font-bold text-sm text-slate-900 mt-1.5">
              {{ permitStore.activePermit.reason || 'Tidak ada keterangan alasan.' }}
            </p>
            <p class="text-xs text-slate-500 mt-0.5">
              Guru Pengampu: <strong class="text-slate-700">{{ permitStore.activePermit.teacher?.name || 'Guru' }}</strong>
            </p>
          </div>
          <BaseBadge :status="permitStore.activePermit.status" />
        </div>

        <div v-if="permitStore.activePermit.status === 'OVERDUE'" class="p-3 bg-rose-100/70 border border-rose-300 rounded-xl flex items-center gap-2 text-xs text-rose-900 font-semibold">
          <AlertTriangle class="w-4 h-4 text-rose-600 shrink-0 animate-pulse" />
          <span>Batas waktu telah berakhir! Anda tercatat terlambat oleh sistem. Harap segera kembali ke kelas.</span>
        </div>

        <div class="pt-1 flex items-center justify-end">
          <router-link to="/student/permit/pass">
            <BaseButton variant="primary" size="sm">
              <template #icon-left><QrCode class="w-3.5 h-3.5" /></template>
              Buka Tiket QR Pass
            </BaseButton>
          </router-link>
        </div>
      </div>

      <div v-else class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
        <div class="space-y-0.5">
          <p class="text-xs font-bold text-slate-800">Tidak Ada Izin Keluar Aktif</p>
          <p class="text-[11px] text-slate-500">Anda tercatat mengikuti KBM secara normal di kelas.</p>
        </div>
        <router-link to="/student/permit/create">
          <BaseButton variant="outline" size="sm">
            Ajukan Izin
          </BaseButton>
        </router-link>
      </div>
    </div>

    <!-- BK Reports Timeline Feed -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
      <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
        <ShieldAlert class="w-4 h-4 text-purple-600" />
        Riwayat Pengaduan Care BK
      </h3>

      <div v-if="reportStore.myReports && reportStore.myReports.length > 0" class="space-y-3">
        <div
          v-for="rep in reportStore.myReports"
          :key="rep.report_id"
          class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3"
        >
          <div class="space-y-1">
            <div class="flex items-center gap-2">
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800 uppercase">
                {{ rep.category }}
              </span>
              <h4 class="font-bold text-xs text-slate-900">{{ rep.title }}</h4>
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
import { onMounted } from 'vue'
import { useReportStore } from '@/stores/report'
import { usePermitStore } from '@/stores/permit'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import { RefreshCw, ShieldAlert, QrCode, AlertTriangle } from 'lucide-vue-next'

const reportStore = useReportStore()
const permitStore = usePermitStore()

const refreshData = async () => {
  await reportStore.fetchMyReports()
  await permitStore.fetchActivePermit()
}

onMounted(() => {
  refreshData()
})
</script>
