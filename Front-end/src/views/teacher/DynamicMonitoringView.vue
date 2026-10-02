<template>
  <div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#355245] to-[#273e34] text-white p-5 sm:p-7 rounded-2xl sm:rounded-3xl border border-white/10 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
      <div class="space-y-2">
        <div class="inline-flex items-center gap-2 bg-white/15 text-emerald-200 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full border border-white/20">
          <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
          <span>Presensi Siswa (Hari {{ permitStore.monitoringData.day || 'Senin' }})</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Monitoring Izin Kelas</h2>
        <p class="text-xs text-[#E8EFEA]/80">Pantau siswa yang sedang izin atau terlambat kembali ke kelas Anda.</p>
      </div>

      <div class="bg-black/25 backdrop-blur-xs p-4 sm:p-5 rounded-2xl border border-white/15 min-w-[240px] w-full md:w-auto flex flex-col gap-3 shrink-0 shadow-xs">
        <div>
          <div class="flex items-center justify-between gap-3">
            <span class="text-[11px] font-bold text-[#E8EFEA]/80 uppercase tracking-wider">Kelas yang Diajar</span>
            <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-emerald-300 bg-emerald-950/70 border border-emerald-500/30 px-2.5 py-0.5 rounded-full">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
              Jam Aktif
            </span>
          </div>
          <p class="text-xl sm:text-2xl font-black text-white tracking-tight mt-1.5">
            {{ permitStore.monitoringData.classes?.join(', ') || '12 SIJA B' }}
          </p>
        </div>

        <button
          type="button"
          @click="loadMonitoring"
          :disabled="isRefreshing"
          class="w-full inline-flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 active:scale-98 text-xs font-bold text-white transition border border-white/15 cursor-pointer touch-manipulation disabled:opacity-50"
        >
          <RefreshCw class="w-3.5 h-3.5" :class="{ 'animate-spin': isRefreshing }" />
          <span>Segarkan Data</span>
        </button>
      </div>
    </div>

    <!-- Peringatan Pengajuan Menunggu Persetujuan -->
    <div
      v-if="pendingCount > 0"
      class="bg-amber-50 border border-amber-200 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 transition-all"
    >
      <div class="flex items-start gap-3.5">
        <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-xs">
          <Clock class="w-5 h-5" />
        </div>
        <div class="space-y-0.5">
          <div class="flex items-center gap-2 flex-wrap">
            <h3 class="text-sm font-extrabold text-amber-950">
              Ada {{ pendingCount }} Pengajuan Izin Menunggu Persetujuan
            </h3>
            <span class="px-2 py-0.5 rounded-full bg-amber-200/80 text-amber-900 text-xs font-bold">
              Antrean
            </span>
          </div>
          <p class="text-xs text-amber-800 leading-relaxed">
            Siswa menunggu persetujuan Anda di ruang antrean sebelum kode QR aktif atau diizinkan meninggalkan kelas.
          </p>
        </div>
      </div>
      <router-link to="/teacher/approvals" class="w-full sm:w-auto shrink-0">
        <BaseButton variant="primary" size="sm" block class="gap-1.5">
          <span>Buka Persetujuan Izin</span>
          <ArrowRight class="w-4 h-4" />
        </BaseButton>
      </router-link>
    </div>

    <!-- Peringatan Siswa Terlambat -->
    <div
      v-if="overdueCount > 0"
      class="bg-rose-50 border border-rose-200 rounded-2xl p-5 shadow-xs flex items-start gap-4 transition-all"
    >
      <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-xs">
        <AlertTriangle class="w-6 h-6" />
      </div>
      <div class="space-y-1 flex-1">
        <div class="flex items-center gap-2 flex-wrap">
          <h3 class="text-base font-extrabold text-rose-950">
            PERINGATAN: {{ overdueCount }} Siswa Melewati Batas Waktu Izin!
          </h3>
          <span class="px-2.5 py-0.5 rounded-full bg-rose-200 text-rose-900 text-xs font-black">
            TERLAMBAT
          </span>
        </div>
        <p class="text-xs text-rose-800 leading-relaxed">
          Ada siswa yang belum kembali ke kelas padahal batas waktu izin sudah habis. Segera konfirmasi jika siswa sudah kembali, atau tandai <strong>Alpha</strong> jika siswa membolos.
        </p>
      </div>
    </div>

    <!-- Stat Widgets Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <MetricCard
        label="Sedang di Luar Kelas"
        :value="activeCount"
        color="warning"
        description="Siswa izin sementara yang aktif"
      >
        <template #icon><Clock class="w-6 h-6 text-amber-600" /></template>
      </MetricCard>

      <MetricCard
        label="Terlambat Kembali"
        :value="overdueCount"
        :color="overdueCount > 0 ? 'danger' : 'secondary'"
        description="Melewati batas waktu izin"
      >
        <template #icon><AlertCircle class="w-6 h-6 text-rose-600" /></template>
      </MetricCard>

      <MetricCard
        label="Total Izin Hari Ini"
        :value="totalMobilityCount"
        color="primary"
        description="Total pengajuan izin di kelas ini"
      >
        <template #icon><Users class="w-6 h-6 text-[#355245]" /></template>
      </MetricCard>
    </div>

    <!-- Table -->
    <DataTable
      :columns="columns"
      :data="permitStore.monitoringData.active_permits || []"
      search-placeholder="Cari nama siswa, NIS, kelas, atau jenis izin..."
    >
      <template #cell-student="{ row }">
        <div :class="{ 'pl-2 border-l-4 border-rose-500 rounded-l': row.status === 'OVERDUE' }">
          <p class="font-bold text-slate-900 text-sm flex items-center gap-2">
            <span>{{ row.student?.name }}</span>
            <span v-if="row.status === 'OVERDUE'" class="text-xs font-bold uppercase text-rose-600 bg-rose-100 px-2 py-0.5 rounded">
              TERLAMBAT
            </span>
          </p>
          <p class="text-slate-500 text-xs mt-0.5">
            NIS: <span class="font-mono font-semibold">{{ row.student?.username }}</span> • 
            <span class="font-bold text-[#355245]">{{ row.student?.class_name }}</span>
          </p>
          <p class="text-slate-500 text-xs mt-0.5">
            <template v-if="row.type === 'EXIT_SCHOOL'">
              <span class="font-medium text-slate-700">Izin Pulang ke Rumah</span>
              <span v-if="row.reason"> • Alasan: <strong class="text-slate-700 font-medium">"{{ row.reason }}"</strong></span>
            </template>
            <template v-else>
              Batas Waktu: <strong>{{ row.duration_minutes || 30 }} menit</strong>
              <span v-if="row.reason"> • {{ row.reason }}</span>
            </template>
          </p>
        </div>
      </template>

      <template #cell-type="{ value }">
        <span
          class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold"
          :class="value === 'TEMP' ? 'bg-slate-100 text-slate-700 border border-slate-200' : 'bg-amber-50 text-amber-800 border border-amber-200'"
        >
          {{ value === 'TEMP' ? 'Keluar Sementara' : 'Izin Pulang' }}
        </span>
      </template>

      <template #cell-status="{ value }">
        <BaseBadge :status="value" />
      </template>

      <template #cell-actions="{ row }">
        <div class="flex items-center justify-end gap-2 whitespace-nowrap shrink-0">
          <!-- 1. Kondisi PENDING: Masih menunggu persetujuan di ApprovalQueue -->
          <template v-if="row.status === 'PENDING'">
            <router-link to="/teacher/approvals">
              <BaseButton variant="outline" size="sm" class="gap-1.5 text-amber-700 border-amber-300 hover:bg-amber-50">
                <span>Tinjau Permohonan</span>
                <ArrowRight class="w-3.5 h-3.5" />
              </BaseButton>
            </router-link>
          </template>

          <!-- 2. Kondisi Izin Pulang (EXIT_SCHOOL): Siswa pulang ke rumah, tidak ada konfirmasi kembali atau tandai alpha di kelas -->
          <template v-else-if="row.type === 'EXIT_SCHOOL'">
            <span
              v-if="row.status === 'APPROVED'"
              class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-2.5 py-1 rounded-lg"
            >
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
              Menuju Gerbang (Pulang)
            </span>
            <span
              v-else-if="row.status === 'CLOSED'"
              class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg"
            >
              <CheckCircle class="w-3.5 h-3.5 text-emerald-600" />
              Sudah Pulang
            </span>
            <span
              v-else-if="row.status === 'REJECTED'"
              class="inline-flex items-center gap-1 text-xs font-medium text-rose-700 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded-lg"
            >
              Ditolak
            </span>
            <span
              v-else-if="row.status === 'CANCELLED'"
              class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg"
            >
              Dibatalkan
            </span>
            <span v-else class="text-xs text-slate-400">-</span>
          </template>

          <!-- 3. Kondisi Keluar Sementara (TEMP) Aktif atau Terlambat: Guru konfirmasi kembali atau tandai Alpha -->
          <template v-else-if="row.status === 'ACTIVE' || row.status === 'OVERDUE'">
            <BaseButton
              variant="primary"
              size="sm"
              @click="confirmAction(row, 'COMPLETED')"
            >
              Konfirmasi Kembali
            </BaseButton>
            <BaseButton
              :variant="row.status === 'OVERDUE' ? 'danger' : 'outline'"
              size="sm"
              @click="confirmAction(row, 'ALPHA')"
            >
              Tandai Alpha
            </BaseButton>
          </template>

          <!-- 4. Kondisi Keluar Sementara yang sudah selesai / diproses -->
          <template v-else>
            <span
              v-if="row.status === 'COMPLETED'"
              class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200/60"
            >
              <CheckCircle class="w-3.5 h-3.5 text-emerald-600" />
              Sudah Kembali
            </span>
            <span
              v-else-if="row.status === 'ALPHA'"
              class="inline-flex items-center gap-1 text-xs font-bold text-rose-700 bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200"
            >
              Tercatat Alpha
            </span>
            <span
              v-else-if="row.status === 'APPROVED'"
              class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg"
            >
              <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-ping"></span>
              Menuju Gerbang
            </span>
            <span
              v-else-if="row.status === 'REJECTED'"
              class="inline-flex items-center gap-1 text-xs font-medium text-rose-700 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded-lg"
            >
              Ditolak
            </span>
            <span
              v-else-if="row.status === 'CANCELLED'"
              class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg"
            >
              Dibatalkan
            </span>
            <span v-else class="text-xs text-slate-400">-</span>
          </template>
        </div>
      </template>
    </DataTable>

    <!-- Confirm Modal -->
    <ConfirmDialog
      :show="showConfirm"
      :title="selectedAction === 'COMPLETED' ? 'Konfirmasi Siswa Kembali' : 'Konfirmasi Status Alpha'"
      :message="selectedAction === 'COMPLETED' ? `Konfirmasi bahwa ${selectedItem?.student?.name} sudah kembali ke ruang kelas? Status izin akan diselesaikan.` : `Apakah Anda yakin ingin menandai ${selectedItem?.student?.name} sebagai Alpha (membolos)? Tindakan ini akan tercatat di sistem presensi dan diteruskan ke BK.`"
      :variant="selectedAction === 'ALPHA' ? 'danger' : 'primary'"
      :confirm-text="selectedAction === 'ALPHA' ? 'Ya, Tandai Alpha' : 'Ya, Sudah Kembali'"
      :loading="isResolving"
      @confirm="executeAction"
      @cancel="showConfirm = false"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { usePermitStore } from '@/stores/permit'
import { useToast } from '@/composables/useToast'
import MetricCard from '@/components/ui/MetricCard.vue'
import DataTable from '@/components/ui/DataTable.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue'
import { Clock, AlertCircle, Users, AlertTriangle, RefreshCw, ArrowRight, CheckCircle } from 'lucide-vue-next'

const permitStore = usePermitStore()
const toast = useToast()

const columns = [
  { key: 'student', label: 'Siswa & Keterangan Izin' },
  { key: 'type', label: 'Jenis Izin' },
  { key: 'status', label: 'Status' },
  { key: 'actions', label: 'Tindakan', class: 'text-right' }
]

const showConfirm = ref(false)
const selectedItem = ref(null)
const selectedAction = ref('')
const isRefreshing = ref(false)
const isResolving = ref(false)

const activeCount = computed(() => {
  return permitStore.monitoringData.active_permits?.filter(p => p.status === 'ACTIVE').length || 0
})

const overdueCount = computed(() => {
  return permitStore.monitoringData.active_permits?.filter(p => p.status === 'OVERDUE').length || 0
})

const pendingCount = computed(() => {
  return permitStore.monitoringData.active_permits?.filter(p => p.status === 'PENDING').length || 0
})

const totalMobilityCount = computed(() => {
  return permitStore.monitoringData.active_permits?.length || 0
})

const loadMonitoring = async () => {
  isRefreshing.value = true
  try {
    await permitStore.fetchTeacherMonitoring()
  } finally {
    setTimeout(() => {
      isRefreshing.value = false
    }, 350)
  }
}

onMounted(() => {
  loadMonitoring()
})

const confirmAction = (item, action) => {
  selectedItem.value = item
  selectedAction.value = action
  showConfirm.value = true
}

const executeAction = async () => {
  if (!selectedItem.value || isResolving.value) return
  isResolving.value = true
  try {
    await permitStore.resolvePermit(selectedItem.value.request_id, selectedAction.value)
    toast.success(`Status perizinan ${selectedItem.value.student?.name} diperbarui!`)
    showConfirm.value = false
  } catch (err) {
    toast.error('Gagal memperbarui status perizinan.')
  } finally {
    isResolving.value = false
  }
}
</script>