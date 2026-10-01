<template>
  <div class="space-y-5">
    <!-- Header Page -->
    <div class="flex items-start justify-between gap-3">
      <div>
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-tight">
          Status & Riwayat Perizinan
        </h2>
        <p class="text-xs text-slate-500 mt-1">Pantau status izin dan tindak lanjut konseling BK kamu di sini.</p>
      </div>
      <BaseButton
        variant="outline"
        size="sm"
        @click="refreshData"
      >
        <template #icon-left><RefreshCw class="w-3.5 h-3.5" :class="{ 'animate-spin': isRefreshing }" /></template>
        <span>Segarkan</span>
      </BaseButton>
    </div>

    <!-- Active Permit Card -->
    <div
      v-if="permitStore.activePermit"
      class="bg-white rounded-2xl border shadow-sm overflow-hidden transition-all"
      :class="permitStore.activePermit.status === 'ALPHA' ? 'border-rose-300 ring-2 ring-rose-200' : (permitStore.activePermit.status === 'OVERDUE' ? 'border-rose-200 ring-1 ring-rose-200' : 'border-slate-200')"
    >
      <!-- Card Header Strip -->
      <div
        class="px-4 sm:px-5 py-3 border-b flex items-center justify-between"
        :class="permitStore.activePermit.status === 'ALPHA' || permitStore.activePermit.status === 'OVERDUE' ? 'bg-rose-50/80 border-rose-100' : 'bg-slate-50/80 border-slate-100'"
      >
        <div class="flex items-center gap-2.5">
          <div
            class="w-8 h-8 rounded-lg flex items-center justify-center font-bold"
            :class="permitStore.activePermit.status === 'ALPHA' || permitStore.activePermit.status === 'OVERDUE' ? 'bg-rose-100 text-rose-700' : 'bg-[#E8EFEA] text-[#355245]'"
          >
            <AlertOctagon v-if="permitStore.activePermit.status === 'ALPHA'" class="w-4 h-4 text-rose-700" />
            <QrCode v-else class="w-4 h-4" />
          </div>
          <div>
            <h3 class="text-sm font-bold text-slate-900 leading-tight">
              {{ permitStore.activePermit.status === 'ALPHA' ? 'Peringatan: Ditandai Alpha' : (permitStore.activePermit.status === 'APPROVED' ? 'Izin Disetujui (Siap ke Gerbang)' : (permitStore.activePermit.status === 'PENDING' ? 'Menunggu Persetujuan Guru' : 'Surat Izin Aktif')) }}
            </h3>
            <span class="text-xs text-slate-500 font-medium">Perizinan Siswa</span>
          </div>
        </div>
        <BaseBadge :status="permitStore.activePermit.status" />
      </div>

      <!-- Card Body -->
      <div class="p-4 sm:p-5 space-y-3.5">
        <!-- Disciplinary Alpha Notice if ALPHA -->
        <div
          v-if="permitStore.activePermit.status === 'ALPHA'"
          class="p-3.5 bg-rose-50 border border-rose-200 rounded-xl space-y-2 text-xs text-rose-900"
        >
          <div class="flex items-center gap-2 font-black text-rose-950">
            <AlertOctagon class="w-4 h-4 text-rose-600 shrink-0" />
            Status: Ditandai Alpha
          </div>
          <p class="text-xs text-rose-800 leading-relaxed">
            Izin ini telah ditandai Alpha oleh guru pengajar (<strong class="text-rose-950">{{ permitStore.activePermit.teacher?.name || 'Guru' }}</strong>).
          </p>
          <div
            v-if="pendingClarification"
            class="p-2.5 bg-amber-50 rounded-lg border border-amber-200 text-xs text-amber-900 font-semibold flex items-center gap-2"
          >
            <Clock class="w-4 h-4 text-amber-600 shrink-0 animate-spin" />
            <span>Klarifikasi kamu telah terkirim (Status: {{ pendingClarification.status === 'IN_PROGRESS' ? 'Sedang Ditangani' : 'Menunggu Tanggapan Guru BK' }}).</span>
          </div>
          <div
            v-else
            class="p-2.5 bg-white/80 rounded-lg border border-rose-200 text-xs text-rose-800 font-medium flex items-center gap-2"
          >
            <ShieldAlert class="w-4 h-4 text-rose-600 shrink-0" />
            <span>Harap segera kirim penjelasan ke BK atau temui guru yang bersangkutan.</span>
          </div>
        </div>

        <!-- Key Info Grid -->
        <div class="grid grid-cols-2 gap-2.5 text-xs">
          <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
            <span class="text-xs font-semibold text-slate-500 block">Jenis Izin</span>
            <span class="font-bold text-slate-800 mt-0.5 block truncate text-sm">
              {{ permitStore.activePermit.type === 'TEMP' ? 'Keluar Sementara' : 'Izin Pulang' }}
            </span>
          </div>
          <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
            <span class="text-xs font-semibold text-slate-500 block">{{ permitStore.activePermit.type === 'EXIT_SCHOOL' ? 'Ketentuan' : 'Batas Waktu' }}</span>
            <span class="font-bold text-slate-800 mt-0.5 block truncate text-sm">
              {{ permitStore.activePermit.type === 'EXIT_SCHOOL' ? 'Pulang ke Rumah' : (permitStore.activePermit.duration_minutes || 30) + ' Menit' }}
            </span>
          </div>
        </div>

        <!-- Detail Lines -->
        <div class="space-y-1.5 text-xs border-t border-slate-100 pt-2.5">
          <div class="flex items-center justify-between gap-3">
            <span class="text-slate-500 text-xs shrink-0">ID Surat Izin:</span>
            <span class="font-mono font-bold text-slate-800 text-right">#{{ permitStore.activePermit.request_id }}</span>
          </div>
          <div class="flex items-start justify-between gap-3">
            <span class="text-slate-500 text-xs shrink-0">Alasan:</span>
            <span class="font-semibold text-slate-800 text-right capitalize">
              {{ permitStore.activePermit.reason || '-' }}
            </span>
          </div>
          <div class="flex items-center justify-between gap-3">
            <span class="text-slate-500 text-xs shrink-0">Guru yang Mengajar:</span>
            <span class="font-semibold text-slate-800 text-right">
              {{ permitStore.activePermit.teacher?.name || '-' }}
            </span>
          </div>
        </div>

        <!-- Approved Notice Banner if Approved by Teacher -->
        <div
          v-if="permitStore.activePermit.status === 'APPROVED'"
          class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-2.5 text-xs text-emerald-900"
        >
          <CheckCircle class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
          <div class="leading-relaxed">
            <strong class="font-bold text-emerald-950">Izin Disetujui Guru!</strong>
            <p class="text-xs text-emerald-700 mt-0.5">Tunjukkan kode QR ke petugas satpam di gerbang sekolah saat hendak keluar.</p>
          </div>
        </div>

        <!-- Overdue Notice Banner if Late -->
        <div
          v-if="permitStore.activePermit.status === 'OVERDUE'"
          class="p-3 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-2.5 text-xs text-rose-900"
        >
          <AlertTriangle class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" />
          <div class="leading-relaxed">
            <strong class="font-bold text-rose-950">Waktu Izin Berakhir!</strong>
            <p class="text-xs text-rose-700 mt-0.5">Batas waktu izin kamu sudah habis. Harap segera kembali ke ruang kelas dan lapor ke guru pengajar.</p>
          </div>
        </div>

        <!-- Action Button -->
        <div v-if="permitStore.activePermit.status === 'ALPHA'" class="pt-1 flex flex-col sm:flex-row gap-2">
          <div v-if="pendingClarification" class="flex-1">
            <BaseButton variant="secondary" size="md" block disabled>
              <template #icon-left><Clock class="w-4 h-4 text-amber-600" /></template>
              Klarifikasi Terkirim (Menunggu BK)
            </BaseButton>
          </div>
          <BaseButton
            v-else
            :to="'/student/report/create?type=alpha&request_id=' + permitStore.activePermit.request_id"
            variant="danger"
            size="md"
            block
            class="flex-1"
          >
            <template #icon-left><HeartHandshake class="w-4 h-4" /></template>
            Kirim Klarifikasi ke BK
          </BaseButton>
          <BaseButton to="/student/permit/pass" variant="outline" size="md" block class="flex-1">
            Lihat Detail Izin
          </BaseButton>
        </div>
        <BaseButton v-else to="/student/permit/pass" variant="primary" size="md" block class="block pt-0.5">
          <template #icon-left><QrCode class="w-4 h-4" /></template>
          Tampilkan Kode QR Izin
        </BaseButton>
      </div>
    </div>

    <!-- Empty State for Permit -->
    <div v-else class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center justify-between gap-3">
      <div class="space-y-0.5">
        <p class="text-xs font-bold text-slate-800">Tidak Ada Izin Keluar Aktif</p>
        <p class="text-xs text-slate-500">Kamu terdaftar sedang berada di dalam kelas.</p>
      </div>
      <BaseButton to="/student/permit/create" variant="outline" size="sm" class="shrink-0">
        Buat Izin
      </BaseButton>
    </div>

    <!-- BK Reports Feed -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm space-y-4">
      <div class="flex items-center justify-between">
        <h3 class="text-xs font-bold text-slate-900 flex items-center gap-2">
          <ShieldAlert class="w-4 h-4 text-[#355245]" />
          Riwayat Pengaduan & Konseling BK
        </h3>
        <span class="text-xs text-slate-500 font-semibold">{{ reportStore.myReports?.length || 0 }} Laporan</span>
      </div>

      <div v-if="reportStore.myReports && reportStore.myReports.length > 0" class="space-y-3">
        <div
          v-for="rep in reportStore.myReports"
          :key="rep.report_id"
          class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3 shadow-xs"
        >
          <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2.5">
            <div class="space-y-1 min-w-0 flex-1">
              <div class="flex items-center gap-2 flex-wrap">
                <span class="px-2 py-0.5 rounded text-xs font-bold bg-[#E8EFEA] text-[#355245] border border-[#d8e3db] uppercase">
                  {{ formatCategory(rep.category) }}
                </span>
                <span class="text-xs font-mono text-slate-400">#REP-{{ rep.report_id }}</span>
                <h4 class="font-bold text-xs sm:text-sm text-slate-900 leading-snug">{{ rep.title }}</h4>
              </div>
              <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">{{ rep.description }}</p>
            </div>

            <BaseBadge :status="rep.status" class="self-start sm:self-center shrink-0" />
          </div>

          <!-- Jalur Bimbingan Pilihan Siswa -->
          <div class="flex items-center gap-1.5 text-xs text-slate-500 pt-2 border-t border-slate-200/70">
            <span>Pilihan Jalur:</span>
            <span class="font-semibold text-slate-800">{{ formatPreference(rep.follow_up_preference) }}</span>
          </div>

          <!-- Kotak Tanggapan Empati dari Guru BK -->
          <div v-if="rep.counselor_response" class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs space-y-1.5">
            <div class="flex items-center justify-between gap-2">
              <span class="font-bold text-emerald-900 flex items-center gap-1.5">
                <MessageSquare class="w-3.5 h-3.5 text-emerald-700 shrink-0" />
                Tanggapan dari Guru BK:
              </span>
              <span v-if="rep.counselor?.name" class="text-[11px] font-medium text-emerald-800">
                {{ rep.counselor.name }}
              </span>
            </div>
            <p class="text-emerald-950 whitespace-pre-line leading-relaxed">{{ rep.counselor_response }}</p>
          </div>
        </div>
      </div>

      <EmptyState
        v-else
        title="Belum Ada Aduan BK"
        description="Kamu belum pernah mengirimkan laporan konseling ke Guru BK."
      />
    </div>

    <!-- Riwayat Seluruh Surat Izin Siswa -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm space-y-4">
      <div class="flex items-center justify-between">
        <h3 class="text-xs font-bold text-slate-900 flex items-center gap-2">
          <History class="w-4 h-4 text-emerald-700" />
          Riwayat Perizinan Siswa
        </h3>
        <span class="text-xs text-slate-500 font-semibold">{{ permitStore.permitHistory?.length || 0 }} Riwayat</span>
      </div>

      <div v-if="permitStore.permitHistory && permitStore.permitHistory.length > 0" class="space-y-3">
        <div
          v-for="permit in permitStore.permitHistory"
          :key="permit.request_id"
          class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2.5 shadow-xs"
        >
          <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2">
            <div class="space-y-1 min-w-0 flex-1">
              <div class="flex items-center gap-2 flex-wrap">
                <span
                  class="px-2 py-0.5 rounded text-xs font-bold uppercase"
                  :class="permit.type === 'EXIT_SCHOOL' ? 'bg-amber-50 text-amber-800 border border-amber-200/60' : 'bg-emerald-50 text-emerald-800 border border-emerald-200/60'"
                >
                  {{ permit.type === 'EXIT_SCHOOL' ? 'Izin Pulang' : 'Izin Sementara' }}
                </span>
                <span class="text-xs font-mono text-slate-400">#{{ permit.request_id }}</span>
                <span class="text-xs text-slate-500 flex items-center gap-1">
                  <Calendar class="w-3 h-3 text-slate-400" />
                  {{ formatDate(permit.created_at) }}
                </span>
              </div>
              <p class="text-xs font-semibold text-slate-800 mt-1">
                Alasan: <span class="font-normal text-slate-600">{{ permit.reason || '-' }}</span>
              </p>
            </div>
            <BaseBadge :status="permit.status" class="self-start sm:self-center shrink-0" />
          </div>

          <div class="grid grid-cols-2 gap-2 text-xs text-slate-500 pt-2 border-t border-slate-200/70">
            <div>
              Guru Pengampu:
              <span class="font-semibold text-slate-700 block truncate">{{ permit.teacher?.name || '-' }}</span>
            </div>
            <div>
              {{ permit.type === 'EXIT_SCHOOL' ? 'Ketentuan:' : 'Durasi:' }}
              <span class="font-semibold text-slate-700 block">{{ permit.type === 'EXIT_SCHOOL' ? 'Pulang ke Rumah' : (permit.duration_minutes ? permit.duration_minutes + ' Menit' : '-') }}</span>
            </div>
          </div>
        </div>
      </div>

      <EmptyState
        v-else
        title="Belum Ada Riwayat Izin"
        description="Belum ada riwayat surat izin yang tercatat di akun kamu."
      />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useReportStore } from '@/stores/report'
import { usePermitStore } from '@/stores/permit'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import { RefreshCw, ShieldAlert, QrCode, AlertTriangle, AlertOctagon, HeartHandshake, Clock, CheckCircle, MessageSquare, History, Calendar } from 'lucide-vue-next'

const reportStore = useReportStore()
const permitStore = usePermitStore()
const isRefreshing = ref(false)

const formatCategory = (cat) => {
  switch (cat) {
    case 'BULLYING': return 'Perundungan'
    case 'ACADEMIC': return 'Masalah Belajar'
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

const formatDate = (dateStr) => {
  if (!dateStr) return '-'
  try {
    const d = new Date(dateStr)
    return d.toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'short',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })
  } catch (e) {
    return dateStr
  }
}

const pendingClarification = computed(() => {
  return reportStore.myReports?.find(r => 
    (r.category === 'OTHERS' || r.title?.toLowerCase().includes('klarifikasi')) &&
    r.status !== 'RESOLVED'
  )
})

const refreshData = async () => {
  isRefreshing.value = true
  try {
    await Promise.allSettled([
      reportStore.fetchMyReports(),
      permitStore.fetchActivePermit(),
      permitStore.fetchPermitHistory()
    ])
  } finally {
    setTimeout(() => {
      isRefreshing.value = false
    }, 400)
  }
}

onMounted(() => {
  refreshData()
})
</script>
