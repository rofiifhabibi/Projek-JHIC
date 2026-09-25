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
import { RefreshCw, ShieldAlert } from 'lucide-vue-next'

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
