<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Persetujuan Izin Siswa</h2>
        <p class="text-xs text-slate-500 mt-0.5">Daftar permohonan izin siswa yang menunggu persetujuan Bapak/Ibu guru.</p>
      </div>
      <BaseButton variant="outline" size="sm" @click="loadRequests">
        <template #icon-left><RefreshCw class="w-3.5 h-3.5" /></template>
        Segarkan
      </BaseButton>
    </div>

    <!-- Requests Grid -->
    <div v-if="permitStore.pendingApprovals && permitStore.pendingApprovals.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div
        v-for="req in permitStore.pendingApprovals"
        :key="req.request_id"
        class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-slate-200 shadow-sm space-y-4 hover:border-slate-300 transition"
      >
        <div class="flex items-start justify-between gap-3">
          <div class="flex items-center gap-3 min-w-0">
            <div class="w-10 h-10 rounded-full bg-[#E8EFEA] text-[#355245] flex items-center justify-center font-bold text-sm shrink-0">
              {{ req.student?.name?.substring(0, 2) || 'SS' }}
            </div>
            <div class="min-w-0">
              <h3 class="font-bold text-sm text-slate-900 truncate">{{ req.student?.name }}</h3>
              <p class="text-xs text-slate-500 truncate">NIS: {{ req.student?.username }} • {{ req.student?.class_name }}</p>
            </div>
          </div>
          <BaseBadge :status="req.status" class="shrink-0" />
        </div>

        <div class="bg-slate-50 p-3.5 sm:p-4 rounded-2xl border border-slate-100 text-xs space-y-2">
          <p class="text-slate-700 font-medium">
            <strong>Jenis Izin:</strong> {{ req.type === 'TEMP' ? 'Keluar Kelas Sementara' : 'Izin Pulang Sekolah' }}
          </p>
          <p class="text-slate-700 font-medium">
            <strong>Batas Waktu:</strong> {{ req.duration_minutes || 30 }} Menit
          </p>
          <p class="text-slate-600">
            <strong>Alasan:</strong> {{ req.reason || 'Tidak ada alasan khusus.' }}
          </p>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 min-[380px]:grid-cols-2 gap-2.5 pt-1">
          <BaseButton
            variant="primary"
            size="sm"
            block
            @click="confirmApprove(req)"
          >
            <template #icon-left><CheckCircle class="w-4 h-4" /></template>
            Setujui Izin
          </BaseButton>

          <BaseButton
            variant="danger"
            size="sm"
            block
            @click="confirmReject(req)"
          >
            <template #icon-left><XCircle class="w-4 h-4" /></template>
            Tolak Izin
          </BaseButton>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <EmptyState
      v-else
      title="Tidak Ada Pengajuan Menunggu"
      description="Semua permohonan izin siswa pada jam pelajaran ini telah selesai ditinjau."
    />

    <!-- Approve Confirm Dialog -->
    <ConfirmDialog
      :show="showApproveConfirm"
      title="Setujui Permohonan Izin"
      :message="`Apakah Anda yakin ingin menyetujui izin dari ${selectedReq?.student?.name} (${selectedReq?.duration_minutes ? selectedReq.duration_minutes + ' menit' : 'Izin Pulang'})? Kode QR izin akan langsung dibuat untuk siswa.`"
      variant="warning"
      confirm-text="Ya, Setujui Izin"
      :loading="isApproving"
      @confirm="executeApprove"
      @cancel="showApproveConfirm = false"
    />

    <!-- Reject Modal with Reason Input -->
    <BaseModal
      :show="showConfirm"
      title="Tolak Permohonan Izin"
      max-width="sm"
      @close="showConfirm = false"
    >
      <template #icon>
        <XCircle class="w-5 h-5 text-rose-600" />
      </template>

      <div class="space-y-3">
        <p class="text-sm text-slate-600 leading-relaxed">
          Apakah Anda yakin ingin menolak izin dari <strong class="text-slate-900">{{ selectedReq?.student?.name }}</strong>?
        </p>
        <div class="space-y-1 text-left">
          <label class="text-xs font-semibold text-slate-700">Alasan Penolakan (Opsional)</label>
          <textarea
            v-model="rejectReason"
            rows="2"
            placeholder="Misal: Sedang ada ulangan/praktik penting, batas waktu tidak sesuai..."
            class="w-full rounded-xl border border-slate-200 bg-slate-50 p-2.5 text-xs text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-rose-500 focus:ring-1 focus:ring-rose-500 focus:outline-none transition"
          ></textarea>
        </div>
      </div>

      <template #footer>
        <BaseButton variant="outline" size="sm" @click="showConfirm = false">
          Batal
        </BaseButton>
        <BaseButton variant="danger" size="sm" :loading="isRejecting" @click="executeReject">
          Tolak Izin
        </BaseButton>
      </template>
    </BaseModal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePermitStore } from '@/stores/permit'
import { useToast } from '@/composables/useToast'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import BaseModal from '@/components/ui/BaseModal.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue'
import { CheckCircle, XCircle, RefreshCw } from 'lucide-vue-next'

const permitStore = usePermitStore()
const toast = useToast()

const showApproveConfirm = ref(false)
const showConfirm = ref(false)
const selectedReq = ref(null)
const rejectReason = ref('')
const isApproving = ref(false)
const isRejecting = ref(false)

const loadRequests = async () => {
  await permitStore.fetchPendingApprovals()
}

onMounted(() => {
  loadRequests()
})

const confirmApprove = (req) => {
  selectedReq.value = req
  showApproveConfirm.value = true
}

const executeApprove = async () => {
  if (!selectedReq.value || isApproving.value) return
  isApproving.value = true
  try {
    await permitStore.approvePermit(selectedReq.value.request_id)
    toast.success('Izin siswa berhasil disetujui!')
    showApproveConfirm.value = false
  } catch (err) {
    toast.error(err.response?.data?.message || 'Gagal menyetujui izin.')
  } finally {
    isApproving.value = false
  }
}

const confirmReject = (req) => {
  selectedReq.value = req
  rejectReason.value = ''
  showConfirm.value = true
}

const executeReject = async () => {
  if (!selectedReq.value || isRejecting.value) return
  isRejecting.value = true
  try {
    await permitStore.resolvePermit(selectedReq.value.request_id, 'REJECTED', rejectReason.value.trim() || null)
    toast.success('Permohonan izin ditolak!')
    showConfirm.value = false
    rejectReason.value = ''
  } catch (err) {
    toast.error('Gagal menolak permohonan izin.')
  } finally {
    isRejecting.value = false
  }
}
</script>
