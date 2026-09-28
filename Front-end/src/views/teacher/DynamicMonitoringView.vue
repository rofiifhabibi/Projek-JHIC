<template>
  <div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white p-5 sm:p-7 rounded-2xl sm:rounded-3xl border border-slate-700 shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
      <div class="space-y-2">
        <div class="inline-flex items-center gap-2 bg-emerald-500/20 text-emerald-300 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full border border-emerald-500/30">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
          <span>Monitoring Presensi (Hari {{ permitStore.monitoringData.day || 'Senin' }})</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Monitoring Presensi & Mobilitas</h2>
        <p class="text-xs text-slate-400">Pengawasan siswa berizin, aktif, dan terlambat di rombel yang Anda ampu.</p>
      </div>

      <div class="bg-slate-800/90 p-4 rounded-2xl border border-slate-700 text-left md:text-right min-w-[220px]">
        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Rombel / Kelas Aktif</p>
        <p class="text-lg font-black text-emerald-400">
          {{ permitStore.monitoringData.classes?.join(', ') || 'XII RPL 1' }}
        </p>
        <p class="text-xs text-slate-300 mt-0.5">Jam Mengajar Aktif</p>
      </div>
    </div>

    <!-- OVERDUE ALERT BANNER (Tampil mencolok jika ada siswa terlambat) -->
    <div
      v-if="overdueCount > 0"
      class="bg-rose-50 border-2 border-rose-300 rounded-3xl p-5 shadow-sm flex items-start gap-4 transition-all"
    >
      <div class="w-10 h-10 rounded-2xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-md">
        <AlertTriangle class="w-6 h-6 animate-pulse" />
      </div>
      <div class="space-y-1 flex-1">
        <div class="flex items-center gap-2 flex-wrap">
          <h3 class="text-base font-extrabold text-rose-950">
            PERINGATAN: {{ overdueCount }} Siswa Melewati Batas Waktu Izin!
          </h3>
          <span class="px-2.5 py-0.5 rounded-full bg-rose-200 text-rose-900 text-xs font-black animate-pulse">
            OVERDUE
          </span>
        </div>
        <p class="text-xs text-rose-800 leading-relaxed">
          Terdapat siswa yang belum kembali ke ruang kelas setelah alokasi durasi izin habis. Harap segera konfirmasi kepulangan siswa atau tandai <strong>Alpha</strong> jika siswa terindikasi membolos.
        </p>
      </div>
    </div>

    <!-- Stat Widgets Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <MetricCard
        label="Sedang Izin (Active)"
        :value="activeCount"
        color="warning"
        description="Siswa yang sedang di luar kelas"
      >
        <template #icon><Clock class="w-6 h-6 text-amber-600" /></template>
      </MetricCard>

      <MetricCard
        label="Terlambat (Overdue)"
        :value="overdueCount"
        :color="overdueCount > 0 ? 'danger' : 'secondary'"
        description="Melewati batas durasi izin"
      >
        <template #icon><AlertCircle class="w-6 h-6 text-rose-600" /></template>
      </MetricCard>

      <MetricCard
        label="Total Mobilitas Hari Ini"
        :value="totalMobilityCount"
        color="primary"
        description="Total perizinan terdaftar"
      >
        <template #icon><Users class="w-6 h-6 text-[#355245]" /></template>
      </MetricCard>
    </div>

    <!-- Real-time Table -->
    <DataTable
      :columns="columns"
      :data="permitStore.monitoringData.active_permits || []"
      search-placeholder="Cari nama siswa, NIS, kelas, atau jenis izin..."
    >
      <template #cell-student="{ row }">
        <div :class="{ 'pl-2 border-l-4 border-rose-500 rounded-l': row.status === 'OVERDUE' }">
          <p class="font-bold text-slate-900 text-sm flex items-center gap-2">
            <span>{{ row.student?.name }}</span>
            <span v-if="row.status === 'OVERDUE'" class="text-[10px] font-black uppercase text-rose-600 bg-rose-100 px-2 py-0.5 rounded">
              TERLAMBAT
            </span>
          </p>
          <p class="text-slate-500 text-xs mt-0.5">
            NIS: <span class="font-mono font-semibold">{{ row.student?.username }}</span> • 
            <span class="font-bold text-[#355245]">{{ row.student?.class_name }}</span>
          </p>
          <p class="text-slate-400 text-[11px] mt-0.5">
            Durasi: <strong>{{ row.duration_minutes || 30 }} menit</strong>
            <span v-if="row.reason"> • {{ row.reason }}</span>
          </p>
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

      <template #cell-actions="{ row }">
        <div class="flex items-center justify-end gap-2 whitespace-nowrap shrink-0">
          <BaseButton
            v-if="row.status !== 'COMPLETED' && row.status !== 'CLOSED'"
            variant="primary"
            size="sm"
            @click="confirmAction(row, 'COMPLETED')"
          >
            Siswa Kembali
          </BaseButton>
          <BaseButton
            v-if="row.status !== 'ALPHA' && row.status !== 'CLOSED'"
            :variant="row.status === 'OVERDUE' ? 'danger' : 'outline'"
            size="sm"
            @click="confirmAction(row, 'ALPHA')"
          >
            Tandai Alpha
          </BaseButton>
        </div>
      </template>
    </DataTable>

    <!-- Confirm Modal -->
    <ConfirmDialog
      :show="showConfirm"
      :title="selectedAction === 'COMPLETED' ? 'Konfirmasi Kembali ke Kelas' : 'Konfirmasi Status Alpha'"
      :message="`Apakah Anda yakin ingin menandai perizinan ${selectedItem?.student?.name} sebagai ${selectedAction === 'COMPLETED' ? 'Siswa Kembali ke Kelas' : 'ALPHA (Membolos)'}?`"
      :variant="selectedAction === 'ALPHA' ? 'danger' : 'primary'"
      confirm-text="Ya, Ubah Status"
      @confirm="executeAction"
      @cancel="showConfirm = false"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { usePermitStore } from '@/stores/permit'
import { useToast } from '@/composables/useToast'
import MetricCard from '@/components/ui/MetricCard.vue'
import DataTable from '@/components/ui/DataTable.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue'
import { Clock, AlertCircle, Users, AlertTriangle } from 'lucide-vue-next'

const permitStore = usePermitStore()
const toast = useToast()

let pollInterval = null

const columns = [
  { key: 'student', label: 'Siswa & Keterangan Izin' },
  { key: 'type', label: 'Jenis Izin' },
  { key: 'status', label: 'Status' },
  { key: 'actions', label: 'Aksi Konfirmasi Guru', class: 'text-right' }
]

const showConfirm = ref(false)
const selectedItem = ref(null)
const selectedAction = ref('')

const activeCount = computed(() => {
  return permitStore.monitoringData.active_permits?.filter(p => p.status === 'ACTIVE').length || 0
})

const overdueCount = computed(() => {
  return permitStore.monitoringData.active_permits?.filter(p => p.status === 'OVERDUE').length || 0
})

const totalMobilityCount = computed(() => {
  return permitStore.monitoringData.active_permits?.length || 0
})

const loadMonitoring = async () => {
  await permitStore.fetchTeacherMonitoring()
}

onMounted(() => {
  loadMonitoring()
  // Auto sync setiap 4 detik untuk update real-time
  pollInterval = setInterval(loadMonitoring, 4000)
})

onUnmounted(() => {
  if (pollInterval) clearInterval(pollInterval)
})

const confirmAction = (item, action) => {
  selectedItem.value = item
  selectedAction.value = action
  showConfirm.value = true
}

const executeAction = async () => {
  if (!selectedItem.value) return
  try {
    await permitStore.resolvePermit(selectedItem.value.request_id, selectedAction.value)
    toast.success(`Status perizinan ${selectedItem.value.student?.name} diperbarui!`)
    showConfirm.value = false
    loadMonitoring()
  } catch (err) {
    toast.error('Gagal memperbarui status perizinan.')
  }
}
</script>