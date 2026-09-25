<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Papan Kerja Kanban BK</h2>
        <p class="text-xs text-slate-500 mt-0.5">Penanganan aduan konseling siswa terpusat dengan data profil siswa lengkap.</p>
      </div>
      <BaseButton variant="outline" size="sm" @click="loadKanban">
        <template #icon-left><RefreshCw class="w-3.5 h-3.5" /></template>
        Refresh Kanban
      </BaseButton>
    </div>

    <!-- Kanban Board Columns -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- COLUMN 1: OPEN -->
      <div class="bg-slate-100/70 p-4 rounded-3xl border border-slate-200/80 space-y-4">
        <div class="flex items-center justify-between px-2">
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
            <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider">Aduan Baru (OPEN)</h3>
          </div>
          <span class="px-2.5 py-0.5 rounded-full bg-amber-200/80 text-amber-900 text-xs font-bold">
            {{ reportStore.kanban.OPEN?.length || 0 }}
          </span>
        </div>

        <div class="space-y-3">
          <div
            v-for="rep in reportStore.kanban.OPEN"
            :key="rep.report_id"
            class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3 hover:border-amber-400 transition"
          >
            <div class="flex items-start justify-between gap-2">
              <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-800 uppercase border border-amber-200">
                {{ formatCategory(rep.category) }}
              </span>
              <span class="text-[10px] font-mono text-slate-400">
                #REP-{{ rep.report_id }}
              </span>
            </div>

            <h4 class="font-bold text-sm text-slate-900 leading-snug">{{ rep.title }}</h4>
            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">{{ rep.description }}</p>

            <!-- Data Lengkap Siswa -->
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80 text-xs space-y-1">
              <div class="flex items-center justify-between">
                <span class="font-bold text-slate-900 flex items-center gap-1.5">
                  <User class="w-3.5 h-3.5 text-[#355245]" />
                  {{ rep.student?.name || 'Siswa' }}
                </span>
                <span class="text-[10px] font-bold text-[#355245] bg-[#E8EFEA] px-2 py-0.5 rounded">
                  {{ rep.student?.class_name || '-' }}
                </span>
              </div>
              <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1 border-t border-slate-200/60">
                <span>NIS: <strong class="font-mono text-slate-700">{{ rep.student?.username || '-' }}</strong></span>
                <span v-if="rep.student?.email" class="text-slate-400 text-[10px] truncate max-w-[150px]">{{ rep.student?.email }}</span>
              </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-end text-xs">
              <BaseButton variant="secondary" size="sm" @click="moveStatus(rep.report_id, 'IN_PROGRESS')">
                <span>Mulai Investigasi</span>
                <template #icon-right><ArrowRight class="w-3.5 h-3.5" /></template>
              </BaseButton>
            </div>
          </div>

          <EmptyState
            v-if="!reportStore.kanban.OPEN || reportStore.kanban.OPEN.length === 0"
            title="Kosong"
            description="Tidak ada aduan baru di kolom ini."
          />
        </div>
      </div>

      <!-- COLUMN 2: IN_PROGRESS -->
      <div class="bg-slate-100/70 p-4 rounded-3xl border border-slate-200/80 space-y-4">
        <div class="flex items-center justify-between px-2">
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
            <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider">Diproses (IN PROGRESS)</h3>
          </div>
          <span class="px-2.5 py-0.5 rounded-full bg-emerald-200/80 text-emerald-900 text-xs font-bold">
            {{ reportStore.kanban.IN_PROGRESS?.length || 0 }}
          </span>
        </div>

        <div class="space-y-3">
          <div
            v-for="rep in reportStore.kanban.IN_PROGRESS"
            :key="rep.report_id"
            class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3 hover:border-emerald-400 transition"
          >
            <div class="flex items-start justify-between gap-2">
              <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-800 uppercase border border-emerald-200">
                {{ formatCategory(rep.category) }}
              </span>
              <span class="text-[10px] font-mono text-slate-400">
                #REP-{{ rep.report_id }}
              </span>
            </div>

            <h4 class="font-bold text-sm text-slate-900 leading-snug">{{ rep.title }}</h4>
            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">{{ rep.description }}</p>

            <!-- Data Lengkap Siswa -->
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80 text-xs space-y-1">
              <div class="flex items-center justify-between">
                <span class="font-bold text-slate-900 flex items-center gap-1.5">
                  <User class="w-3.5 h-3.5 text-[#355245]" />
                  {{ rep.student?.name || 'Siswa' }}
                </span>
                <span class="text-[10px] font-bold text-[#355245] bg-[#E8EFEA] px-2 py-0.5 rounded">
                  {{ rep.student?.class_name || '-' }}
                </span>
              </div>
              <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1 border-t border-slate-200/60">
                <span>NIS: <strong class="font-mono text-slate-700">{{ rep.student?.username || '-' }}</strong></span>
                <span v-if="rep.student?.email" class="text-slate-400 text-[10px] truncate max-w-[150px]">{{ rep.student?.email }}</span>
              </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex flex-col gap-2">
              <BaseButton variant="outline" size="sm" block @click="openNoteModal(rep)">
                <template #icon-left><FileText class="w-3.5 h-3.5" /></template>
                Catatan Investigasi
              </BaseButton>
              <BaseButton variant="primary" size="sm" block @click="moveStatus(rep.report_id, 'RESOLVED')">
                <template #icon-left><CheckCircle2 class="w-3.5 h-3.5" /></template>
                Tandai Tuntas (RESOLVED)
              </BaseButton>
            </div>
          </div>

          <EmptyState
            v-if="!reportStore.kanban.IN_PROGRESS || reportStore.kanban.IN_PROGRESS.length === 0"
            title="Kosong"
            description="Tidak ada kasus yang sedang diproses."
          />
        </div>
      </div>

      <!-- COLUMN 3: RESOLVED -->
      <div class="bg-slate-100/70 p-4 rounded-3xl border border-slate-200/80 space-y-4">
        <div class="flex items-center justify-between px-2">
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-slate-500"></span>
            <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider">Selesai (RESOLVED)</h3>
          </div>
          <span class="px-2.5 py-0.5 rounded-full bg-slate-200 text-slate-800 text-xs font-bold">
            {{ reportStore.kanban.RESOLVED?.length || 0 }}
          </span>
        </div>

        <div class="space-y-3">
          <div
            v-for="rep in reportStore.kanban.RESOLVED"
            :key="rep.report_id"
            class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3 opacity-90"
          >
            <div class="flex items-start justify-between gap-2">
              <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 uppercase">
                {{ formatCategory(rep.category) }}
              </span>
              <BaseBadge status="RESOLVED" />
            </div>

            <h4 class="font-bold text-sm text-slate-900 line-through decoration-slate-400">{{ rep.title }}</h4>
            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ rep.description }}</p>

            <!-- Data Lengkap Siswa -->
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80 text-xs space-y-1">
              <div class="flex items-center justify-between">
                <span class="font-bold text-slate-900 flex items-center gap-1.5">
                  <User class="w-3.5 h-3.5 text-[#355245]" />
                  {{ rep.student?.name || 'Siswa' }}
                </span>
                <span class="text-[10px] font-bold text-[#355245] bg-[#E8EFEA] px-2 py-0.5 rounded">
                  {{ rep.student?.class_name || '-' }}
                </span>
              </div>
              <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1 border-t border-slate-200/60">
                <span>NIS: <strong class="font-mono text-slate-700">{{ rep.student?.username || '-' }}</strong></span>
                <span v-if="rep.student?.email" class="text-slate-400 text-[10px] truncate max-w-[150px]">{{ rep.student?.email }}</span>
              </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex justify-end">
              <BaseButton variant="ghost" size="sm" @click="moveStatus(rep.report_id, 'IN_PROGRESS')">
                Re-open Kasus
              </BaseButton>
            </div>
          </div>

          <EmptyState
            v-if="!reportStore.kanban.RESOLVED || reportStore.kanban.RESOLVED.length === 0"
            title="Kosong"
            description="Belum ada arsip kasus selesai."
          />
        </div>
      </div>
    </div>

    <!-- Investigation Note Modal -->
    <BaseModal
      :show="showNoteModal"
      title="Tambah Catatan Investigasi Konseling"
      max-width="md"
      @close="showNoteModal = false"
    >
      <div class="space-y-4">
        <!-- Rincian Identitas Siswa Lengkap -->
        <div v-if="selectedReport?.student" class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 text-xs space-y-1.5">
          <div class="font-bold text-slate-900 flex items-center justify-between">
            <span class="flex items-center gap-1.5 text-sm">
              <User class="w-4 h-4 text-[#355245]" />
              {{ selectedReport.student.name }}
            </span>
            <span class="text-[#355245] font-bold bg-[#E8EFEA] px-2 py-0.5 rounded">
              {{ selectedReport.student.class_name }}
            </span>
          </div>
          <div class="text-[11px] text-slate-500 flex items-center justify-between pt-1 border-t border-slate-200/60">
            <span>NIS: <strong class="font-mono text-slate-800">{{ selectedReport.student.username }}</strong></span>
            <span>{{ selectedReport.student.email }}</span>
          </div>
        </div>

        <div class="bg-amber-50 p-3 rounded-xl border border-amber-200 text-xs">
          <p class="text-amber-800">
            Kasus: <strong class="text-amber-950">{{ selectedReport?.title }}</strong>
          </p>
        </div>

        <div class="space-y-1.5">
          <label class="text-xs font-semibold uppercase tracking-wider text-slate-700">Catatan Tindak Lanjut</label>
          <textarea
            v-model="noteInput"
            rows="4"
            placeholder="Ketik catatan hasil pemanggilan siswa / tindak lanjut BK..."
            class="w-full rounded-xl border border-slate-200 p-3 text-xs focus:border-[#355245] focus:outline-none"
          ></textarea>
        </div>
      </div>

      <template #footer>
        <BaseButton variant="outline" size="sm" @click="showNoteModal = false">Batal</BaseButton>
        <BaseButton variant="primary" size="sm" :loading="reportStore.loading" @click="saveNote">
          Simpan Catatan
        </BaseButton>
      </template>
    </BaseModal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useReportStore } from '@/stores/report'
import { useToast } from '@/composables/useToast'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import BaseModal from '@/components/ui/BaseModal.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import { RefreshCw, User, ArrowRight, FileText, CheckCircle2 } from 'lucide-vue-next'

const reportStore = useReportStore()
const toast = useToast()

const showNoteModal = ref(false)
const selectedReport = ref(null)
const noteInput = ref('')

const formatCategory = (cat) => {
  switch (cat) {
    case 'BULLYING': return 'Perundungan'
    case 'ACADEMIC': return 'Akademik'
    case 'PERSONAL': return 'Konseling Pribadi'
    default: return cat
  }
}

const loadKanban = async () => {
  await reportStore.fetchKanban()
}

onMounted(() => {
  loadKanban()
})

const moveStatus = async (id, status) => {
  try {
    await reportStore.updateReportStatus(id, status)
    toast.success(`Status aduan diperbarui ke ${status}!`)
    loadKanban()
  } catch (err) {
    toast.error('Gagal memperbarui status aduan.')
  }
}

const openNoteModal = (rep) => {
  selectedReport.value = rep
  noteInput.value = ''
  showNoteModal.value = true
}

const saveNote = async () => {
  if (!selectedReport.value || !noteInput.value.trim()) {
    toast.warning('Isi catatan terlebih dahulu!')
    return
  }
  try {
    await reportStore.addInvestigationNote(selectedReport.value.report_id, noteInput.value)
    toast.success('Catatan investigasi berhasil ditambahkan!')
    showNoteModal.value = false
    loadKanban()
  } catch (err) {
    toast.error('Gagal menambahkan catatan.')
  }
}
</script>