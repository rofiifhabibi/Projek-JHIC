<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Papan Kerja Kanban BK</h2>
        <p class="text-xs text-slate-500 mt-0.5">Penanganan aduan konseling siswa terpusat dengan data profil siswa lengkap.</p>
      </div>
      <BaseButton variant="outline" size="sm" :loading="reportStore.loading" @click="loadKanban">
        <template #icon-left><RefreshCw class="w-3.5 h-3.5" /></template>
        Refresh Kanban
      </BaseButton>
    </div>

    <!-- Search & Category Filter Toolbar -->
    <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
      <div class="relative flex-1">
        <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" />
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Cari nama siswa, NIS, atau kata kunci aduan..."
          class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2 text-xs text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#355245] focus:outline-none transition"
        />
      </div>

      <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 shrink-0">
        <button
          v-for="cat in categoryFilters"
          :key="cat.value"
          @click="selectedCategory = cat.value"
          class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap"
          :class="selectedCategory === cat.value ? 'bg-[#355245] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
        >
          {{ cat.label }}
        </button>
      </div>
    </div>

    <!-- Kanban Board Columns -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- COLUMN 1: OPEN -->
      <div class="bg-slate-100/70 p-4 rounded-3xl border border-slate-200/80 space-y-4 flex flex-col">
        <div class="flex items-center justify-between px-2 shrink-0">
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
            <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider">Aduan Baru (OPEN)</h3>
          </div>
          <span class="px-2.5 py-0.5 rounded-full bg-amber-200/80 text-amber-900 text-xs font-bold">
            {{ filteredOpen.length }}
          </span>
        </div>

        <div class="space-y-3 overflow-y-auto max-h-[calc(100vh-270px)] pr-1">
          <div
            v-for="rep in filteredOpen"
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
            v-if="!filteredOpen || filteredOpen.length === 0"
            title="Kosong"
            description="Tidak ada aduan di kolom ini yang sesuai filter."
          />
        </div>
      </div>

      <!-- COLUMN 2: IN_PROGRESS -->
      <div class="bg-slate-100/70 p-4 rounded-3xl border border-slate-200/80 space-y-4 flex flex-col">
        <div class="flex items-center justify-between px-2 shrink-0">
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
            <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider">Diproses (IN PROGRESS)</h3>
          </div>
          <span class="px-2.5 py-0.5 rounded-full bg-emerald-200/80 text-emerald-900 text-xs font-bold">
            {{ filteredInProgress.length }}
          </span>
        </div>

        <div class="space-y-3 overflow-y-auto max-h-[calc(100vh-270px)] pr-1">
          <div
            v-for="rep in filteredInProgress"
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
            v-if="!filteredInProgress || filteredInProgress.length === 0"
            title="Kosong"
            description="Tidak ada kasus yang sesuai filter di kolom ini."
          />
        </div>
      </div>

      <!-- COLUMN 3: RESOLVED -->
      <div class="bg-slate-100/70 p-4 rounded-3xl border border-slate-200/80 space-y-4 flex flex-col">
        <div class="flex items-center justify-between px-2 shrink-0">
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-slate-500"></span>
            <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider">Selesai (RESOLVED)</h3>
          </div>
          <span class="px-2.5 py-0.5 rounded-full bg-slate-200 text-slate-800 text-xs font-bold">
            {{ filteredResolved.length }}
          </span>
        </div>

        <div class="space-y-3 overflow-y-auto max-h-[calc(100vh-270px)] pr-1">
          <div
            v-for="rep in filteredResolved"
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
            v-if="!filteredResolved || filteredResolved.length === 0"
            title="Kosong"
            description="Tidak ada kasus selesai yang sesuai filter."
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
import { ref, computed, onMounted } from 'vue'
import { useReportStore } from '@/stores/report'
import { useToast } from '@/composables/useToast'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import BaseModal from '@/components/ui/BaseModal.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import { RefreshCw, User, ArrowRight, FileText, CheckCircle2, Search } from 'lucide-vue-next'

const reportStore = useReportStore()
const toast = useToast()

const showNoteModal = ref(false)
const selectedReport = ref(null)
const noteInput = ref('')

const searchQuery = ref('')
const selectedCategory = ref('ALL')

const categoryFilters = [
  { label: 'Semua Kategori', value: 'ALL' },
  { label: 'Perundungan', value: 'BULLYING' },
  { label: 'Akademik', value: 'ACADEMIC' },
  { label: 'Konseling Pribadi', value: 'PERSONAL' }
]

const formatCategory = (cat) => {
  switch (cat) {
    case 'BULLYING': return 'Perundungan'
    case 'ACADEMIC': return 'Akademik'
    case 'PERSONAL': return 'Konseling Pribadi'
    default: return cat
  }
}

const filterList = (list) => {
  if (!list) return []
  return list.filter(rep => {
    const matchesCat = selectedCategory.value === 'ALL' || rep.category === selectedCategory.value
    if (!matchesCat) return false
    if (!searchQuery.value.trim()) return true
    const q = searchQuery.value.toLowerCase()
    const studentName = rep.student?.name?.toLowerCase() || ''
    const studentNis = rep.student?.username?.toLowerCase() || ''
    const title = rep.title?.toLowerCase() || ''
    const desc = rep.description?.toLowerCase() || ''
    return studentName.includes(q) || studentNis.includes(q) || title.includes(q) || desc.includes(q)
  })
}

const filteredOpen = computed(() => filterList(reportStore.kanban.OPEN))
const filteredInProgress = computed(() => filterList(reportStore.kanban.IN_PROGRESS))
const filteredResolved = computed(() => filterList(reportStore.kanban.RESOLVED))

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
  } catch (err) {
    toast.error('Gagal menambahkan catatan.')
  }
}
</script>