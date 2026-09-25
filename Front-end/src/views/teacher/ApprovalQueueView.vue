<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Antrean Persetujuan Surat Izin</h2>
        <p class="text-xs text-slate-500 mt-0.5">Daftar pengajuan perizinan siswa di kelas yang membutuhkan verifikasi Anda.</p>
      </div>
      <BaseButton variant="outline" size="sm" @click="loadRequests">
        <template #icon-left><RefreshCw class="w-3.5 h-3.5" /></template>
        Refresh Antrean
      </BaseButton>
    </div>

    <!-- Requests Grid -->
    <div v-if="permitStore.pendingApprovals && permitStore.pendingApprovals.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div
        v-for="req in permitStore.pendingApprovals"
        :key="req.request_id"
        class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4 hover:border-slate-300 transition"
      >
        <div class="flex items-start justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-[#E8EFEA] text-[#355245] flex items-center justify-center font-bold text-sm">
              {{ req.student?.name?.substring(0, 2) || 'SS' }}
            </div>
            <div>
              <h3 class="font-bold text-sm text-slate-900">{{ req.student?.name }}</h3>
              <p class="text-xs text-slate-500">NIS: {{ req.student?.username }} • {{ req.student?.class_name }}</p>
            </div>
          </div>
          <BaseBadge :status="req.status" />
        </div>

        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 text-xs space-y-2">
          <p class="text-slate-700 font-medium">
            <strong>Tipe:</strong> {{ req.type === 'TEMP' ? 'Keluar Sementara (TEMP)' : 'Izin Pulang Sekolah' }}
          </p>
          <p class="text-slate-700 font-medium">
            <strong>Durasi Izin:</strong> {{ req.duration_minutes || 30 }} Menit
          </p>
          <p class="text-slate-600">
            <strong>Alasan:</strong> {{ req.reason || 'Tidak ada alasan khusus.' }}
          </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2 pt-2">
          <BaseButton
            variant="primary"
            size="sm"
            block
            @click="handleApprove(req.request_id)"
          >
            <template #icon-left><CheckCircle2 class="w-4 h-4" /></template>
            Setujui (QR)
          </BaseButton>

          <BaseButton
            variant="danger"
            size="sm"
            @click="confirmReject(req, 'REJECTED')"
          >
            Tolak Izin
          </BaseButton>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <EmptyState
      v-else
      title="Tidak Ada Antrean Izin"
      description="Saat ini belum ada pengajuan perizinan siswa yang menunggu persetujuan Anda."
    />

    <!-- Reject Confirm Dialog -->
    <ConfirmDialog
      :show="showConfirm"
      title="Tolak Permohonan Izin"
      :message="`Apakah Anda yakin ingin menolak permohonan izin dari ${selectedReq?.student?.name}?`"
      variant="danger"
      confirm-text="Ya, Tolak Permohonan"
      @confirm="executeReject"
      @cancel="showConfirm = false"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePermitStore } from '@/stores/permit'
import { useToast } from '@/composables/useToast'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue'
import { CheckCircle2, RefreshCw } from 'lucide-vue-next'

const permitStore = usePermitStore()
const toast = useToast()

const showConfirm = ref(false)
const selectedReq = ref(null)

const loadRequests = async () => {
  await permitStore.fetchPendingApprovals()
}

onMounted(() => {
  loadRequests()
})

const handleApprove = async (id) => {
  try {
    await permitStore.approvePermit(id)
    toast.success('Surat izin disetujui & QR Code diterbitkan!')
    loadRequests()
  } catch (err) {
    toast.error(err.response?.data?.message || 'Gagal menyetujui izin.')
  }
}

const confirmReject = (req) => {
  selectedReq.value = req
  showConfirm.value = true
}

const executeReject = async () => {
  if (!selectedReq.value) return
  try {
    await permitStore.resolvePermit(selectedReq.value.request_id, 'REJECTED')
    toast.success('Permohonan izin ditolak!')
    showConfirm.value = false
    loadRequests()
  } catch (err) {
    toast.error('Gagal menolak permohonan izin.')
  }
}
</script>
