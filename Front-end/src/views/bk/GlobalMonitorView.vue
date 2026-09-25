<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Monitor Mobilitas Global Sekolah</h2>
        <p class="text-xs text-slate-500 mt-0.5">Pengawasan perizinan seluruh siswa lintas rombel dan kelas di SMKN 2 Depok.</p>
      </div>
      <BaseButton variant="outline" size="sm" @click="loadData">
        <template #icon-left><RefreshCw class="w-3.5 h-3.5" /></template>
        Refresh Data
      </BaseButton>
    </div>

    <!-- Data Table -->
    <DataTable
      :columns="columns"
      :data="reportStore.globalMobility || []"
      search-placeholder="Cari nama siswa, NIS, kelas, atau status..."
    >
      <template #cell-student="{ row }">
        <div>
          <p class="font-bold text-slate-900 text-sm">{{ row.student?.name }}</p>
          <p class="text-slate-400 text-xs">NIS: {{ row.student?.username }} • {{ row.student?.class_name }}</p>
        </div>
      </template>

      <template #cell-type="{ value }">
        <span class="font-semibold text-xs text-slate-700">
          {{ value === 'TEMP' ? 'Keluar Sementara' : 'Izin Pulang' }}
        </span>
      </template>

      <template #cell-status="{ value }">
        <BaseBadge :status="value" />
      </template>

      <template #cell-[#teacher]="{ row }">
        <span class="text-xs text-slate-600 font-medium">
          {{ row.teacher?.name || '-' }}
        </span>
      </template>
    </DataTable>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useReportStore } from '@/stores/report'
import DataTable from '@/components/ui/DataTable.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import { RefreshCw } from 'lucide-vue-next'

const reportStore = useReportStore()

const columns = [
  { key: 'student', label: 'Siswa & Rombel' },
  { key: 'type', label: 'Jenis Izin' },
  { key: 'status', label: 'Status Saat Ini' },
  { key: 'teacher', label: 'Guru Penanggung Jawab' }
]

const loadData = async () => {
  await reportStore.fetchGlobalMobility()
}

onMounted(() => {
  loadData()
})
</script>
