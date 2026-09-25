<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Monitor Mobilitas Global Sekolah</h2>
        <p class="text-xs text-slate-500 mt-0.5">Pengawasan perizinan seluruh siswa lintas rombel dan kelas di SMKN 2 Depok Sleman.</p>
      </div>
      <BaseButton variant="outline" size="sm" @click="loadData">
        <template #icon-left><RefreshCw class="w-3.5 h-3.5" :class="{ 'animate-spin': isRefreshing }" /></template>
        Refresh Data
      </BaseButton>
    </div>

    <!-- OVERDUE ALERT BANNER FOR BK -->
    <div
      v-if="overdueList.length > 0"
      class="bg-rose-50 border-2 border-rose-300 rounded-3xl p-5 shadow-sm flex items-start gap-4"
    >
      <div class="w-10 h-10 rounded-2xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-md">
        <AlertTriangle class="w-6 h-6 animate-pulse" />
      </div>
      <div class="space-y-1 flex-1">
        <div class="flex items-center gap-2 flex-wrap">
          <h3 class="text-base font-extrabold text-rose-950">
            PERINGATAN MOBILITAS: {{ overdueList.length }} Siswa Melewati Batas Waktu Izin!
          </h3>
          <span class="px-2.5 py-0.5 rounded-full bg-rose-200 text-rose-900 text-xs font-black animate-pulse">
            OVERDUE
          </span>
        </div>
        <p class="text-xs text-rose-800 leading-relaxed">
          Terdapat siswa dari berbagai rombel yang tercatat terlambat kembali dari izin. Guru BK dapat memantau pergerakan siswa secara global untuk koordinasi dengan guru pengampu atau wali kelas.
        </p>
      </div>
    </div>

    <!-- Stat Widgets Grid for BK -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
      <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold text-slate-500">Total Izin Terdaftar</p>
          <p class="text-2xl font-black text-slate-900 mt-1">{{ allPermits.length }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
          <Users class="w-5 h-5" />
        </div>
      </div>

      <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold text-slate-500">Izin Aktif (Normal)</p>
          <p class="text-2xl font-black text-emerald-600 mt-1">{{ activeCount }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
          <Clock class="w-5 h-5" />
        </div>
      </div>

      <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between" :class="{ 'border-rose-300 bg-rose-50/40': overdueList.length > 0 }">
        <div>
          <p class="text-xs font-semibold" :class="overdueList.length > 0 ? 'text-rose-700' : 'text-slate-500'">Terlambat (Overdue)</p>
          <p class="text-2xl font-black mt-1" :class="overdueList.length > 0 ? 'text-rose-600' : 'text-slate-900'">{{ overdueList.length }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold" :class="overdueList.length > 0 ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-500'">
          <AlertTriangle class="w-5 h-5" />
        </div>
      </div>

      <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold text-slate-500">Izin Selesai / Pulang</p>
          <p class="text-2xl font-black text-slate-700 mt-1">{{ closedOrCompletedCount }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold">
          <CheckCircle2 class="w-5 h-5" />
        </div>
      </div>
    </div>

    <!-- Data Table -->
    <DataTable
      :columns="columns"
      :data="allPermits"
      search-placeholder="Cari nama siswa, NIS, kelas, atau status..."
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
            NIS: <span class="font-mono font-bold text-slate-700">{{ row.student?.username }}</span> • 
            <span class="font-bold text-[#355245] bg-[#E8EFEA] px-1.5 py-0.5 rounded text-[11px]">{{ row.student?.class_name }}</span>
          </p>
          <p v-if="row.student?.email" class="text-slate-400 text-[10px] mt-0.5">{{ row.student?.email }}</p>
          <p v-if="row.reason" class="text-slate-400 text-[11px] mt-0.5 italic">"{{ row.reason }}"</p>
        </div>
      </template>

      <template #cell-type="{ row }">
        <div class="space-y-0.5">
          <span class="font-bold text-xs text-slate-800 block">
            {{ row.type === 'TEMP' ? 'Keluar Sementara' : 'Izin Pulang' }}
          </span>
          <span class="text-[11px] text-slate-500 font-medium block">
            Alokasi: <strong class="text-slate-700">{{ row.duration_minutes || 30 }} menit</strong>
          </span>
        </div>
      </template>

      <template #cell-status="{ value }">
        <div class="space-y-1">
          <BaseBadge :status="value" />
          <p v-if="value === 'OVERDUE'" class="text-[10px] font-bold text-rose-600 uppercase tracking-wide">
            Perlu Konfirmasi Guru
          </p>
        </div>
      </template>

      <template #cell-teacher="{ row }">
        <div class="space-y-0.5">
          <span class="text-xs text-slate-800 font-bold block">
            {{ row.teacher?.name || '-' }}
          </span>
          <span v-if="row.teacher?.email" class="text-slate-400 text-[10px] block">
            {{ row.teacher?.email }}
          </span>
        </div>
      </template>
    </DataTable>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useReportStore } from '@/stores/report'
import DataTable from '@/components/ui/DataTable.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import { RefreshCw, AlertTriangle, Users, Clock, CheckCircle2 } from 'lucide-vue-next'

const reportStore = useReportStore()
const isRefreshing = ref(false)
let pollInterval = null

const columns = [
  { key: 'student', label: 'Data Lengkap Siswa' },
  { key: 'type', label: 'Jenis & Durasi Izin' },
  { key: 'status', label: 'Status Terkini' },
  { key: 'teacher', label: 'Guru Penanggung Jawab' }
]

const allPermits = computed(() => reportStore.globalMobility || [])

const overdueList = computed(() => {
  return allPermits.value.filter(p => p.status === 'OVERDUE')
})

const activeCount = computed(() => {
  return allPermits.value.filter(p => p.status === 'ACTIVE').length
})

const closedOrCompletedCount = computed(() => {
  return allPermits.value.filter(p => p.status === 'COMPLETED' || p.status === 'CLOSED').length
})

const loadData = async () => {
  isRefreshing.value = true
  try {
    await reportStore.fetchGlobalMobility()
  } finally {
    setTimeout(() => {
      isRefreshing.value = false
    }, 400)
  }
}

onMounted(() => {
  loadData()
  // Auto sync setiap 4 detik untuk update real-time BK
  pollInterval = setInterval(loadData, 4000)
})

onUnmounted(() => {
  if (pollInterval) clearInterval(pollInterval)
})
</script>