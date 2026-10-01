<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Penanganan Kasus & Konseling BK</h2>
        <p class="text-xs text-slate-500 mt-0.5">Kelola laporan siswa dan pantau perkembangan pendampingan konseling.</p>
      </div>
      <BaseButton variant="outline" size="sm" :loading="reportStore.loading" @click="loadKanban">
        <template #icon-left><RefreshCw class="w-3.5 h-3.5" /></template>
        Segarkan
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
          type="button"
          @click="selectedCategory = cat.value"
          class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap cursor-pointer touch-manipulation active:scale-95"
          :class="selectedCategory === cat.value ? 'bg-[#355245] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
        >
          {{ cat.label }}
        </button>
      </div>
    </div>

    <!-- Mobile/Tablet Column Selector (below xl) -->
    <div class="xl:hidden flex items-center bg-slate-200/70 p-1.5 rounded-2xl border border-slate-200 gap-1">
      <button
        type="button"
        @click="activeColumnTab = 'OPEN'"
        class="flex-1 py-2 px-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer touch-manipulation active:scale-[0.98]"
        :class="activeColumnTab === 'OPEN' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
      >
        <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
        <span class="truncate">Aduan Masuk</span>
        <span class="ml-0.5 px-1.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">{{ filteredOpen.length }}</span>
      </button>
      <button
        type="button"
        @click="activeColumnTab = 'IN_PROGRESS'"
        class="flex-1 py-2 px-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer touch-manipulation active:scale-[0.98]"
        :class="activeColumnTab === 'IN_PROGRESS' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
      >
        <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
        <span class="truncate">Ditangani</span>
        <span class="ml-0.5 px-1.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">{{ filteredInProgress.length }}</span>
      </button>
      <button
        type="button"
        @click="activeColumnTab = 'RESOLVED'"
        class="flex-1 py-2 px-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer touch-manipulation active:scale-[0.98]"
        :class="activeColumnTab === 'RESOLVED' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
      >
        <span class="w-2 h-2 rounded-full bg-slate-500 shrink-0"></span>
        <span class="truncate">Selesai</span>
        <span class="ml-0.5 px-1.5 py-0.5 rounded-full bg-slate-200 text-slate-700 text-[10px] font-bold">{{ filteredResolved.length }}</span>
      </button>
    </div>

    <!-- Kanban Board Columns -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
      <!-- COLUMN 1: OPEN -->
      <div
        class="bg-slate-100/70 p-4 rounded-3xl border border-slate-200/80 space-y-4 flex-col"
        :class="activeColumnTab === 'OPEN' ? 'flex' : 'hidden xl:flex'"
      >
        <div class="flex items-center justify-between px-2 shrink-0">
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
            <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider">Aduan Masuk</h3>
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
              <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-amber-50 text-amber-800 uppercase border border-amber-200">
                {{ formatCategory(rep.category) }}
              </span>
              <span class="text-xs font-mono text-slate-400">
                #REP-{{ rep.report_id }}
              </span>
            </div>

            <h4 class="font-bold text-sm text-slate-900 leading-snug">{{ rep.title }}</h4>
            
            <!-- Linked Permit Pill -->
            <div v-if="rep.permit || rep.request_id" class="px-2.5 py-1.5 rounded-xl bg-amber-50 border border-amber-200/80 text-xs text-amber-900 flex items-center justify-between">
              <span class="flex items-center gap-1 font-semibold">
                <AlertOctagon class="w-3.5 h-3.5 text-rose-600 shrink-0" />
                Terkait Izin #{{ rep.request_id }} ({{ rep.permit?.status || 'ALPHA' }})
              </span>
              <span class="text-xs text-amber-700">Klarifikasi</span>
            </div>

            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">{{ rep.description }}</p>

            <!-- Data Lengkap Siswa -->
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80 text-xs space-y-1">
              <div class="flex items-center justify-between">
                <span class="font-bold text-slate-900 flex items-center gap-1.5">
                  <User class="w-3.5 h-3.5 text-[#355245]" />
                  {{ rep.student?.name || 'Siswa' }}
                </span>
                <span class="text-xs font-bold text-[#355245] bg-[#E8EFEA] px-2 py-0.5 rounded">
                  {{ rep.student?.class_name || '-' }}
                </span>
              </div>
              <div class="flex items-center justify-between text-xs text-slate-500 pt-1 border-t border-slate-200/60">
                <span>NIS: <strong class="font-mono text-slate-700">{{ rep.student?.username || '-' }}</strong></span>
                <span v-if="rep.student?.email" class="text-slate-400 text-xs truncate max-w-[150px]">{{ rep.student?.email }}</span>
              </div>
            </div>

            <!-- Jalur Bimbingan Pilihan Siswa -->
            <div class="flex items-center justify-between text-xs text-slate-500 bg-slate-50 px-2.5 py-1.5 rounded-xl border border-slate-200/80">
              <span class="text-slate-500">Jalur Dipilih:</span>
              <span class="font-semibold text-slate-800">{{ formatPreference(rep.follow_up_preference) }}</span>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2 text-xs">
              <BaseButton variant="outline" size="sm" @click="openResponseModal(rep)">
                <template #icon-left><MessageSquare class="w-3.5 h-3.5" /></template>
                <span>Tanggapi</span>
              </BaseButton>
              <BaseButton variant="secondary" size="sm" @click="moveStatus(rep.report_id, 'IN_PROGRESS')">
                <span>Tindak Lanjuti</span>
                <template #icon-right><ArrowRight class="w-3.5 h-3.5" /></template>
              </BaseButton>
            </div>
          </div>

          <EmptyState
            v-if="!filteredOpen || filteredOpen.length === 0"
            title="Belum Ada Laporan"
            description="Tidak ada laporan siswa pada kolom ini yang sesuai filter."
          />
        </div>
      </div>

      <!-- COLUMN 2: IN_PROGRESS -->
      <div
        class="bg-slate-100/70 p-4 rounded-3xl border border-slate-200/80 space-y-4 flex-col"
        :class="activeColumnTab === 'IN_PROGRESS' ? 'flex' : 'hidden xl:flex'"
      >
        <div class="flex items-center justify-between px-2 shrink-0">
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
            <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider">Sedang Ditangani</h3>
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
              <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-emerald-50 text-emerald-800 uppercase border border-emerald-200">
                {{ formatCategory(rep.category) }}
              </span>
              <span class="text-xs font-mono text-slate-400">
                #REP-{{ rep.report_id }}
              </span>
            </div>

            <h4 class="font-bold text-sm text-slate-900 leading-snug">{{ rep.title }}</h4>

            <!-- Linked Permit Pill -->
            <div v-if="rep.permit || rep.request_id" class="px-2.5 py-1.5 rounded-xl bg-amber-50 border border-amber-200/80 text-xs text-amber-900 flex items-center justify-between">
              <span class="flex items-center gap-1 font-semibold">
                <AlertOctagon class="w-3.5 h-3.5 text-rose-600 shrink-0" />
                Terkait Izin #{{ rep.request_id }} ({{ rep.permit?.status || 'ALPHA' }})
              </span>
              <span class="text-xs text-amber-700">Klarifikasi</span>
            </div>

            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">{{ rep.description }}</p>

            <!-- Data Lengkap Siswa -->
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80 text-xs space-y-1">
              <div class="flex items-center justify-between">
                <span class="font-bold text-slate-900 flex items-center gap-1.5">
                  <User class="w-3.5 h-3.5 text-[#355245]" />
                  {{ rep.student?.name || 'Siswa' }}
                </span>
                <span class="text-xs font-bold text-[#355245] bg-[#E8EFEA] px-2 py-0.5 rounded">
                  {{ rep.student?.class_name || '-' }}
                </span>
              </div>
              <div class="flex items-center justify-between text-xs text-slate-500 pt-1 border-t border-slate-200/60">
                <span>NIS: <strong class="font-mono text-slate-700">{{ rep.student?.username || '-' }}</strong></span>
                <span v-if="rep.student?.email" class="text-slate-400 text-xs truncate max-w-[150px]">{{ rep.student?.email }}</span>
              </div>
            </div>

            <!-- Jalur Bimbingan Pilihan Siswa -->
            <div class="flex items-center justify-between text-xs text-slate-500 bg-slate-50 px-2.5 py-1.5 rounded-xl border border-slate-200/80">
              <span class="text-slate-500">Jalur Dipilih:</span>
              <span class="font-semibold text-slate-800">{{ formatPreference(rep.follow_up_preference) }}</span>
            </div>

            <!-- Preview Tanggapan Siswa jika sudah ada -->
            <div v-if="rep.counselor_response" class="bg-emerald-50/80 border border-emerald-200 rounded-xl p-2.5 text-xs space-y-1">
              <div class="flex items-center justify-between">
                <span class="font-bold text-emerald-800 flex items-center gap-1 text-[11px]">
                  <MessageSquare class="w-3 h-3 text-emerald-600" /> Tanggapan Terkirim:
                </span>
                <span v-if="rep.counselor?.name" class="text-[10px] text-emerald-700 font-medium truncate max-w-[120px]">
                  {{ rep.counselor.name }}
                </span>
              </div>
              <p class="text-emerald-950 line-clamp-2">{{ rep.counselor_response }}</p>
            </div>

            <div class="pt-2 border-t border-slate-100 flex flex-col gap-2">
              <BaseButton variant="outline" size="sm" block @click="openResponseModal(rep)">
                <template #icon-left><MessageSquare class="w-3.5 h-3.5" /></template>
                {{ rep.counselor_response ? 'Ubah Tanggapan & Catatan' : 'Tanggapi Siswa' }}
              </BaseButton>
              <BaseButton variant="primary" size="sm" block :loading="reportStore.loading" @click="requestResolve(rep)">
                <template #icon-left><CheckCircle class="w-3.5 h-3.5" :stroke-width="2.25" /></template>
                Tandai Selesai
              </BaseButton>
            </div>
          </div>

          <EmptyState
            v-if="!filteredInProgress || filteredInProgress.length === 0"
            title="Belum Ada Kasus Ditangani"
            description="Tidak ada kasus yang sedang ditangani pada filter ini."
          />
        </div>
      </div>

      <!-- COLUMN 3: RESOLVED -->
      <div
        class="bg-slate-100/70 p-4 rounded-3xl border border-slate-200/80 space-y-4 flex-col"
        :class="activeColumnTab === 'RESOLVED' ? 'flex' : 'hidden xl:flex'"
      >
        <div class="flex items-center justify-between px-2 shrink-0">
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-slate-500"></span>
            <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider">Selesai Ditangani</h3>
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
              <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-slate-100 text-slate-700 uppercase">
                {{ formatCategory(rep.category) }}
              </span>
              <BaseBadge status="RESOLVED" />
            </div>

            <h4 class="font-bold text-sm text-slate-900">{{ rep.title }}</h4>

            <!-- Linked Permit Pill -->
            <div v-if="rep.permit || rep.request_id" class="px-2.5 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 flex items-center justify-between">
              <span class="flex items-center gap-1 font-semibold">
                <CheckCircle class="w-3.5 h-3.5 text-emerald-600 shrink-0" :stroke-width="2.25" />
                Izin #{{ rep.request_id }} Dipulihkan
              </span>
              <span class="text-xs text-slate-500">Selesai</span>
            </div>

            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ rep.description }}</p>

            <!-- Data Lengkap Siswa -->
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80 text-xs space-y-1">
              <div class="flex items-center justify-between">
                <span class="font-bold text-slate-900 flex items-center gap-1.5">
                  <User class="w-3.5 h-3.5 text-[#355245]" />
                  {{ rep.student?.name || 'Siswa' }}
                </span>
                <span class="text-xs font-bold text-[#355245] bg-[#E8EFEA] px-2 py-0.5 rounded">
                  {{ rep.student?.class_name || '-' }}
                </span>
              </div>
              <div class="flex items-center justify-between text-xs text-slate-500 pt-1 border-t border-slate-200/60">
                <span>NIS: <strong class="font-mono text-slate-700">{{ rep.student?.username || '-' }}</strong></span>
                <span v-if="rep.student?.email" class="text-slate-400 text-xs truncate max-w-[150px]">{{ rep.student?.email }}</span>
              </div>
            </div>

            <!-- Jalur Bimbingan Pilihan Siswa -->
            <div class="flex items-center justify-between text-xs text-slate-500 bg-slate-50 px-2.5 py-1.5 rounded-xl border border-slate-200/80">
              <span class="text-slate-500">Jalur Dipilih:</span>
              <span class="font-semibold text-slate-800">{{ formatPreference(rep.follow_up_preference) }}</span>
            </div>

            <!-- Preview Tanggapan Siswa jika sudah ada -->
            <div v-if="rep.counselor_response" class="bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs space-y-1">
              <span class="font-bold text-slate-700 flex items-center gap-1 text-[11px]">
                <MessageSquare class="w-3 h-3 text-emerald-600" /> Tanggapan Diberikan:
              </span>
              <p class="text-slate-600 line-clamp-2">{{ rep.counselor_response }}</p>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2">
              <BaseButton variant="outline" size="sm" @click="openResponseModal(rep)">
                <template #icon-left><MessageSquare class="w-3.5 h-3.5" /></template>
                Lihat Tanggapan
              </BaseButton>
              <BaseButton variant="ghost" size="sm" @click="moveStatus(rep.report_id, 'IN_PROGRESS')">
                Buka Kembali Kasus
              </BaseButton>
            </div>
          </div>

          <EmptyState
            v-if="!filteredResolved || filteredResolved.length === 0"
            title="Belum Ada Kasus Selesai"
            description="Tidak ada kasus selesai yang sesuai filter."
          />
        </div>
      </div>
    </div>

    <!-- Modal Tindak Lanjut & Tanggapan Siswa (Dua Arah) -->
    <BaseModal
      :show="showResponseModal"
      title="Tindak Lanjut & Tanggapan Siswa"
      max-width="lg"
      @close="showResponseModal = false"
    >
      <div class="space-y-4">
        <!-- Rincian Identitas Siswa -->
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
          <div class="text-xs text-slate-500 flex items-center justify-between pt-1 border-t border-slate-200/60">
            <span>NIS: <strong class="font-mono text-slate-800">{{ selectedReport.student.username }}</strong></span>
            <span>{{ selectedReport.student.email }}</span>
          </div>
        </div>

        <!-- Rincian Aduan & Pilihan Siswa -->
        <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200/90 text-xs space-y-2">
          <div class="flex items-center justify-between">
            <span class="font-bold text-amber-950">Aduan: {{ selectedReport?.title }}</span>
            <span class="font-bold text-amber-900 bg-amber-100/90 px-2 py-0.5 rounded text-[11px]">
              {{ formatCategory(selectedReport?.category) }}
            </span>
          </div>
          <p class="text-amber-950 leading-relaxed whitespace-pre-line">{{ selectedReport?.description }}</p>
          <div class="pt-1.5 border-t border-amber-200 text-amber-900 flex items-center gap-1.5 font-medium">
            <span>Jalur Bimbingan Pilihan Siswa:</span>
            <strong class="underline font-bold">{{ formatPreference(selectedReport?.follow_up_preference) }}</strong>
          </div>
        </div>

        <!-- Pesan Tanggapan untuk Siswa (Dua Arah) -->
        <div class="space-y-1.5">
          <div class="flex items-center justify-between">
            <label class="text-xs font-semibold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
              <MessageSquare class="w-3.5 h-3.5 text-[#355245]" />
              Pesan Tanggapan untuk Siswa <span class="text-rose-500">*</span>
            </label>
            <span class="text-[11px] text-[#355245] font-semibold">Terbaca langsung di HP Siswa</span>
          </div>
          <textarea
            v-model="responseInput"
            rows="3"
            placeholder="Tulis pesan empati, saran, atau konfirmasi waktu/lokasi pertemuan yang aman..."
            class="w-full rounded-xl border border-slate-200 p-3 text-xs focus:border-[#355245] focus:outline-none transition leading-relaxed"
          ></textarea>
          <p class="text-[11px] text-slate-500 leading-tight">
            Pesan tanggapan ini akan langsung tampil di menu Konseling pada akun siswa yang bersangkutan.
          </p>
        </div>

        <!-- Catatan Investigasi Internal BK (Opsional) -->
        <div class="space-y-1.5">
          <label class="text-xs font-semibold uppercase tracking-wider text-slate-700">
            Catatan Investigasi Internal BK (Opsional)
          </label>
          <textarea
            v-model="internalNoteInput"
            rows="2"
            placeholder="Catatan tertutup untuk arsip tim BK (tidak dikirim ke siswa)..."
            class="w-full rounded-xl border border-slate-200 p-3 text-xs focus:border-[#355245] focus:outline-none bg-slate-50/50"
          ></textarea>
        </div>

        <!-- Status Kasus -->
        <div class="flex items-center gap-3 pt-1">
          <label class="text-xs font-semibold text-slate-700">Update Status:</label>
          <select
            v-model="statusAfterResponse"
            class="text-xs rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 font-medium text-slate-800 focus:border-[#355245] focus:outline-none"
          >
            <option value="IN_PROGRESS">Sedang Ditangani (In Progress)</option>
            <option value="RESOLVED">Selesai Ditangani (Resolved)</option>
            <option value="OPEN">Tetap Di Aduan Masuk (Open)</option>
          </select>
        </div>
      </div>

      <template #footer>
        <BaseButton variant="outline" size="sm" @click="showResponseModal = false">Batal</BaseButton>
        <BaseButton variant="primary" size="sm" :loading="reportStore.loading" @click="submitResponse">
          Kirim Tanggapan ke Siswa
        </BaseButton>
      </template>
    </BaseModal>

    <!-- Confirm Resolve Dialog -->
    <ConfirmDialog
      :show="showResolveConfirm"
      title="Selesaikan Kasus / Konseling"
      :message="resolveConfirmMessage"
      confirm-text="Ya, Tandai Selesai"
      variant="primary"
      @confirm="handleConfirmResolve"
      @cancel="cancelResolve"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useReportStore } from '@/stores/report'
import { useToast } from '@/composables/useToast'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import BaseModal from '@/components/ui/BaseModal.vue'
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import { RefreshCw, User, ArrowRight, CheckCircle, Search, AlertOctagon, MessageSquare } from 'lucide-vue-next'

const reportStore = useReportStore()
const toast = useToast()

const showResponseModal = ref(false)
const selectedReport = ref(null)
const responseInput = ref('')
const internalNoteInput = ref('')
const statusAfterResponse = ref('IN_PROGRESS')

const showResolveConfirm = ref(false)
const reportToResolve = ref(null)

const searchQuery = ref('')
const selectedCategory = ref('ALL')
const activeColumnTab = ref('OPEN')

const categoryFilters = [
  { label: 'Semua Kategori', value: 'ALL' },
  { label: 'Perundungan', value: 'BULLYING' },
  { label: 'Akademik', value: 'ACADEMIC' },
  { label: 'Konseling Pribadi', value: 'PERSONAL' },
  { label: 'Klarifikasi & Lainnya', value: 'OTHERS' }
]

const formatCategory = (cat) => {
  switch (cat) {
    case 'BULLYING': return 'Perundungan'
    case 'ACADEMIC': return 'Akademik'
    case 'PERSONAL': return 'Konseling Pribadi'
    case 'OTHERS': return 'Klarifikasi & Lainnya'
    default: return cat
  }
}

const formatPreference = (pref) => {
  switch (pref) {
    case 'WEB_MESSAGE': return 'Pesan Tertulis di Web'
    case 'WHATSAPP': return 'Chat WhatsApp Pribadi'
    case 'NEUTRAL_MEET': return 'Janji Temu di Tempat Netral'
    case 'INFO_ONLY': return 'Hanya Laporan Informasi'
    default: return 'Pesan Tertulis di Web'
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
    const statusLabels = {
      OPEN: 'Aduan Masuk',
      IN_PROGRESS: 'Sedang Ditangani',
      RESOLVED: 'Selesai Ditangani'
    }
    toast.success(`Status aduan diperbarui ke "${statusLabels[status] || status}".`)
  } catch (err) {
    toast.error('Gagal memperbarui status aduan.')
  }
}

const resolveConfirmMessage = computed(() => {
  if (!reportToResolve.value) return ''
  if (reportToResolve.value.request_id || reportToResolve.value.category === 'OTHERS') {
    return `Apakah penanganan siswa (${reportToResolve.value.student?.name || 'Siswa'}) sudah selesai? Status izin siswa akan diperbarui menjadi Selesai.`
  }
  return `Apakah Anda yakin ingin menandai aduan "${reportToResolve.value.title}" telah selesai ditangani oleh BK?`
})

const requestResolve = (rep) => {
  reportToResolve.value = rep
  showResolveConfirm.value = true
}

const cancelResolve = () => {
  showResolveConfirm.value = false
  reportToResolve.value = null
}

const handleConfirmResolve = async () => {
  if (!reportToResolve.value) return
  await moveStatus(reportToResolve.value.report_id, 'RESOLVED')
  cancelResolve()
}

const openResponseModal = (rep) => {
  selectedReport.value = rep
  responseInput.value = rep.counselor_response || ''
  internalNoteInput.value = ''
  statusAfterResponse.value = rep.status === 'RESOLVED' ? 'RESOLVED' : 'IN_PROGRESS'
  showResponseModal.value = true
}

const isSubmittingResponse = ref(false)

const submitResponse = async () => {
  if (!selectedReport.value || isSubmittingResponse.value) return
  if (!responseInput.value.trim()) {
    toast.warning('Pesan tanggapan untuk siswa wajib diisi!')
    return
  }

  isSubmittingResponse.value = true
  try {
    await reportStore.sendCounselorResponse(selectedReport.value.report_id, {
      response_message: responseInput.value,
      internal_notes: internalNoteInput.value,
      status: statusAfterResponse.value
    })
    toast.success('Tanggapan bimbingan konseling berhasil dikirim ke siswa!')
    showResponseModal.value = false
  } catch (err) {
    toast.error('Gagal mengirim tanggapan ke siswa.')
  } finally {
    isSubmittingResponse.value = false
  }
}
</script>