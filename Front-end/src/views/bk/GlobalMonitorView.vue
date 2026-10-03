<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Monitoring Izin Seluruh Siswa</h2>
        <p class="text-xs text-slate-500 mt-0.5">Pantauan status perizinan siswa dari seluruh kelas di SMKN 2 Depok Sleman.</p>
      </div>
      <BaseButton variant="outline" size="sm" @click="loadData">
        <template #icon-left><RefreshCw class="w-3.5 h-3.5" :class="{ 'animate-spin': isRefreshing }" /></template>
        Perbarui Data
      </BaseButton>
    </div>

    <!-- Peringatan Keterlambatan -->
    <div
      v-if="overdueList.length > 0"
      class="bg-rose-50 border border-rose-200 rounded-2xl p-5 shadow-xs flex items-start gap-4"
    >
      <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-xs">
        <AlertTriangle class="w-5 h-5" />
      </div>
      <div class="space-y-1 flex-1">
        <div class="flex items-center gap-2 flex-wrap">
          <h3 class="text-base font-bold text-rose-950">
            Perhatian: {{ overdueList.length }} Siswa Terlambat Kembali ke Sekolah
          </h3>
          <span class="px-2.5 py-0.5 rounded-full bg-rose-200 text-rose-800 text-xs font-bold">
            Terlambat
          </span>
        </div>
        <p class="text-xs text-rose-800 leading-relaxed">
          Terdapat siswa yang melewati batas durasi izin dan belum tercatat kembali di gerbang sekolah. Tim BK dapat berkoordinasi dengan guru pengampu atau wali kelas terkait.
        </p>
      </div>
    </div>

    <!-- Stat Widgets Grid for BK (Fully Symmetrical & Harmonized) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold text-slate-500">Total Izin Hari Ini</p>
          <p class="text-2xl font-black text-slate-900 mt-1">{{ todayPermitsCount }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
          <Users class="w-5 h-5" />
        </div>
      </div>

      <div
        class="bg-white p-4 rounded-2xl border transition-all shadow-xs flex items-center justify-between"
        :class="activeCount > 0 ? 'border-emerald-200 bg-emerald-50/20' : 'border-slate-200'"
      >
        <div>
          <p class="text-xs font-semibold" :class="activeCount > 0 ? 'text-emerald-800' : 'text-slate-500'">Sedang Di Luar (Aktif)</p>
          <p class="text-2xl font-black mt-1" :class="activeCount > 0 ? 'text-emerald-600' : 'text-slate-900'">{{ activeCount }}</p>
        </div>
        <div
          class="w-10 h-10 rounded-xl flex items-center justify-center font-bold transition-colors"
          :class="activeCount > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'"
        >
          <Clock class="w-5 h-5" />
        </div>
      </div>

      <div
        class="bg-white p-4 rounded-2xl border transition-all shadow-xs flex items-center justify-between"
        :class="overdueList.length > 0 ? 'border-rose-300 bg-rose-50/40' : 'border-slate-200'"
      >
        <div>
          <p class="text-xs font-semibold" :class="overdueList.length > 0 ? 'text-rose-700' : 'text-slate-500'">Terlambat Kembali</p>
          <p class="text-2xl font-black mt-1" :class="overdueList.length > 0 ? 'text-rose-600' : 'text-slate-900'">{{ overdueList.length }}</p>
        </div>
        <div
          class="w-10 h-10 rounded-xl flex items-center justify-center font-bold transition-colors"
          :class="overdueList.length > 0 ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-500'"
        >
          <AlertTriangle class="w-5 h-5" />
        </div>
      </div>

      <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold text-slate-500">Selesai / Sudah Kembali</p>
          <p class="text-2xl font-black text-slate-900 mt-1">{{ closedOrCompletedCount }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
          <CheckCircle class="w-5 h-5" />
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
        <div :class="{ 'pl-2.5 border-l-4 border-rose-500 rounded-l': row.status === 'OVERDUE' }">
          <p class="font-bold text-slate-900 text-sm flex items-center gap-2">
            <span>{{ row.student?.name }}</span>
            <span v-if="row.status === 'OVERDUE'" class="text-xs font-bold text-rose-700 bg-rose-100 px-2 py-0.5 rounded">
              Terlambat
            </span>
          </p>
          <p class="text-slate-500 text-xs mt-0.5">
            NIS: <span class="font-mono font-bold text-slate-700">{{ row.student?.username }}</span> • 
            <span class="font-bold text-[#355245] bg-[#E8EFEA] px-1.5 py-0.5 rounded text-xs">{{ row.student?.class_name }}</span>
          </p>
          <p v-if="row.student?.email" class="text-slate-500 text-xs mt-0.5">{{ row.student?.email }}</p>
          <p v-if="row.reason" class="text-slate-500 text-xs mt-1 italic">"{{ row.reason }}"</p>
        </div>
      </template>

      <template #cell-type="{ row }">
        <div class="space-y-0.5">
          <span class="font-bold text-xs text-slate-800 block">
            {{ row.type === 'TEMP' ? 'Keluar Sementara' : 'Izin Pulang' }}
          </span>
          <span v-if="row.type === 'TEMP'" class="text-xs text-slate-500 font-medium block">
            Durasi: <strong class="text-slate-700">{{ row.duration_minutes || 30 }} menit</strong>
          </span>
          <span v-else class="text-xs text-slate-500 font-medium block">
            Pulang ke Rumah
          </span>
        </div>
      </template>

      <template #cell-status="{ value }">
        <div class="space-y-1">
          <BaseBadge :status="value" />
          <p v-if="value === 'OVERDUE'" class="text-xs font-semibold text-rose-600">
            Perlu konfirmasi guru
          </p>
        </div>
      </template>

      <template #cell-teacher="{ row }">
        <div class="space-y-0.5">
          <span class="text-xs text-slate-800 font-bold block">
            {{ row.teacher?.name || '-' }}
          </span>
          <span v-if="row.teacher?.email" class="text-slate-500 text-xs block">
            {{ row.teacher?.email }}
          </span>
        </div>
      </template>
    </DataTable>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useReportStore } from '@/stores/report'
import DataTable from '@/components/ui/DataTable.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import { RefreshCw, AlertTriangle, Users, Clock, CheckCircle } from 'lucide-vue-next'

const reportStore = useReportStore()
const isRefreshing = ref(false)

const columns = [
  { key: 'student', label: 'Identitas Siswa' },
  { key: 'type', label: 'Jenis & Durasi Izin' },
  { key: 'status', label: 'Status Izin' },
  { key: 'teacher', label: 'Guru Pengajar' }
]

const allPermits = computed(() => reportStore.globalMobility || [])

const todayPermitsCount = computed(() => {
  const today = new Date().toISOString().slice(0, 10)
  return allPermits.value.filter(p => {
    if (!p.created_at) return true
    return p.created_at.slice(0, 10) === today
  }).length
})

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
})
</script>