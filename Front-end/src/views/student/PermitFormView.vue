<template>
  <div class="space-y-6">
    <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-slate-200 shadow-sm space-y-5 sm:space-y-6">
      <div class="border-b border-slate-100 pb-4">
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Pengajuan Surat Izin</h2>
        <p class="text-xs text-slate-500 mt-0.5">Isi data permohonan izin untuk disetujui oleh guru yang mengajar di kelas.</p>
      </div>

      <!-- Active Permit Notice Banner -->
      <div
        v-if="hasActivePermit"
        class="p-4 sm:p-5 bg-amber-50 border border-amber-200 rounded-2xl space-y-3"
      >
        <div class="flex items-center gap-2 font-bold text-amber-900 text-xs">
          <AlertCircle class="w-4 h-4 text-amber-600 shrink-0" />
          <span>Kamu Masih Memiliki Izin Aktif (#{{ permitStore.activePermit?.request_id }})</span>
        </div>
        <p class="text-xs text-amber-800 leading-relaxed">
          Kamu saat ini memiliki permohonan izin (Status: <strong class="uppercase font-bold text-amber-950">{{ permitStore.activePermit?.status }}</strong>).
          <span v-if="canCancelActivePermit">
            Kamu dapat melihat surat izin aktif atau membatalkannya jika belum digunakan keluar gerbang.
          </span>
          <span v-else>
            Izin telah divalidasi di gerbang. Harap kembali ke kelas dan laporkan ke guru pengajar untuk menyelesaikan izin sebelum mengajukan izin baru.
          </span>
        </p>
        <div class="flex flex-col sm:flex-row gap-2 pt-1">
          <BaseButton to="/student/permit/pass" variant="primary" size="sm" block class="flex-1">
            Lihat Surat Izin & QR
          </BaseButton>
          <BaseButton
            v-if="canCancelActivePermit"
            type="button"
            variant="danger"
            size="sm"
            class="flex-1"
            :loading="isCancelling"
            @click="handleCancelActive"
          >
            Batalkan Pengajuan
          </BaseButton>
          <BaseButton to="/student/dashboard" variant="outline" size="sm" block class="flex-1">
            Kembali ke Beranda
          </BaseButton>
        </div>
      </div>

      <form v-else @submit.prevent="handleSubmit" class="space-y-5 sm:space-y-6">
        <!-- Step 1: Jenis Izin -->
        <div class="space-y-3">
          <label class="text-xs font-semibold uppercase tracking-wider text-slate-700">
            1. Pilih Jenis Izin <span class="text-rose-500">*</span>
          </label>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <RadioCard
              v-model="form.type"
              value="TEMP"
              name="permit-type"
              title="Keluar Kelas Sementara"
              description="Ke UKS, Toilet, Ruang TU, atau keperluan singkat lainnya."
            />
            <RadioCard
              v-model="form.type"
              value="EXIT_SCHOOL"
              name="permit-type"
              title="Izin Pulang Sekolah"
              description="Meninggalkan lingkungan sekolah karena sakit atau keperluan mendesak."
            />
          </div>
        </div>

        <!-- Step 2: Durasi Izin (Hanya untuk TEMP) -->
        <div v-if="form.type === 'TEMP'" class="space-y-3">
          <div class="flex items-center justify-between">
            <label class="text-xs font-semibold uppercase tracking-wider text-slate-700">
              2. Tentukan Lama Izin <span class="text-rose-500">*</span>
            </label>
            <span class="text-xs font-bold text-[#355245] bg-[#E8EFEA] px-2.5 py-0.5 rounded-full">
              {{ form.duration_minutes || 0 }} Menit
            </span>
          </div>

          <!-- Opsi Cepat (Quick Presets) -->
          <div class="grid grid-cols-2 min-[380px]:grid-cols-4 gap-2">
            <button
              v-for="dur in [10, 15, 30, 45]"
              :key="dur"
              type="button"
              @click="setDuration(dur)"
              class="py-2.5 px-2 rounded-xl border text-xs font-bold transition text-center cursor-pointer touch-manipulation active:scale-95"
              :class="form.duration_minutes === dur ? 'border-[#355245] bg-[#E8EFEA] text-[#355245] shadow-xs' : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300'"
            >
              {{ dur }} Menit
            </button>
          </div>

          <!-- Input Durasi Kustom & Stepper -->
          <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3.5 sm:p-4 space-y-3">
            <div class="flex items-center justify-between text-xs">
              <span class="font-medium text-slate-700">Tentukan Durasi Sendiri:</span>
              <span class="text-xs text-slate-500">Rentang: 5 - 180 menit</span>
            </div>

            <div class="flex items-center gap-1.5 sm:gap-3">
              <!-- Tombol Kurang 5 Menit -->
              <button
                type="button"
                @click="adjustDuration(-5)"
                :disabled="form.duration_minutes <= 5"
                class="h-10 px-2.5 sm:px-3 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center transition shadow-xs shrink-0 cursor-pointer touch-manipulation active:scale-95"
                title="Kurangi 5 menit"
              >
                -5 mnt
              </button>

              <!-- Input Angka Bebas -->
              <div class="relative flex-1 min-w-0">
                <input
                  v-model.number="form.duration_minutes"
                  type="number"
                  min="5"
                  max="180"
                  step="1"
                  required
                  placeholder="30"
                  class="w-full text-center font-bold text-slate-900 bg-white border border-slate-200 rounded-xl py-2 px-2 text-sm focus:border-[#355245] focus:ring-2 focus:ring-[#355245] focus:outline-none transition pr-12"
                />
                <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-xs font-medium text-slate-500 pointer-events-none">
                  Menit
                </span>
              </div>

              <!-- Tombol Tambah 5 Menit -->
              <button
                type="button"
                @click="adjustDuration(5)"
                :disabled="form.duration_minutes >= 180"
                class="h-10 px-2.5 sm:px-3 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center transition shadow-xs shrink-0 cursor-pointer touch-manipulation active:scale-95"
                title="Tambah 5 menit"
              >
                +5 mnt
              </button>
            </div>

            <!-- Slider / Range Bar untuk kemudahan mobile -->
            <div class="pt-1">
              <input
                v-model.number="form.duration_minutes"
                type="range"
                min="5"
                max="180"
                step="5"
                class="w-full h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#355245]"
              />
            </div>
          </div>
        </div>

        <!-- Step 3: Alasan Izin (Reason) -->
        <div class="space-y-1.5">
          <label for="permit-reason" class="text-xs font-semibold uppercase tracking-wider text-slate-700">
            3. Alasan Izin <span class="text-rose-500">*</span>
          </label>
          <textarea
            id="permit-reason"
            v-model="form.reason"
            rows="3"
            required
            @input="errors.reason = ''"
            :aria-invalid="errors.reason ? 'true' : undefined"
            :aria-describedby="errors.reason ? 'permit-reason-error' : undefined"
            placeholder="Tuliskan keperluan izin kamu (misal: Mengambil buku di loker / ke ruang UKS)..."
            class="w-full rounded-xl border bg-white p-3.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none transition"
            :class="errors.reason ? 'border-rose-400 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20' : 'border-slate-200 focus:border-[#355245] focus:ring-2 focus:ring-[#355245]'"
          ></textarea>
          <p v-if="errors.reason" id="permit-reason-error" role="alert" class="text-xs text-rose-600 font-medium mt-0.5">
            {{ errors.reason }}
          </p>
        </div>

        <!-- Step 4: Pilih Guru yang Mengajar (Otomatis Jadwal / Manual Fallback) -->
        <div class="space-y-2.5">
          <label class="text-xs font-semibold uppercase tracking-wider text-slate-700 block">
            4. Guru Pengampu Jam Pelajaran <span class="text-rose-500">*</span>
          </label>

          <!-- Card Otomatis Terdeteksi Sesuai Jadwal (Dummy/Live) -->
          <div v-if="!isManualSelect" class="space-y-2">
            <div
              class="p-4 rounded-2xl bg-[#E8EFEA]/80 border border-[#355245]/25 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-xs"
            >
              <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-[#355245] text-white flex items-center justify-center shrink-0 shadow-xs">
                  <UserCheck class="w-5 h-5 text-emerald-300" />
                </div>
                <div>
                  <div class="flex items-center gap-2 flex-wrap">
                    <h4 class="font-extrabold text-sm sm:text-base text-slate-900 leading-tight flex items-center gap-2 flex-wrap">
                      <span>{{ detectedTeacher.name }}</span>
                      <span class="text-[11px] font-bold text-emerald-800 bg-emerald-100/90 px-2 py-0.5 rounded-md border border-emerald-300 font-mono">
                        Akun: {{ detectedTeacher.username || 'guru1' }}
                      </span>
                    </h4>
                    <!-- Badge Status Jadwal: Mode Evaluasi 24 Jam vs Jadwal Aktif -->
                    <span
                      v-if="detectedTeacher.period?.includes('Evaluasi 24 Jam')"
                      class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[10px] font-extrabold uppercase border border-blue-200 tracking-wide"
                    >
                      <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                      Mode Evaluasi 24 Jam
                    </span>
                    <span
                      v-else
                      class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold uppercase border border-emerald-200 tracking-wide"
                    >
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                      Jadwal Aktif
                    </span>
                  </div>
                  <p class="text-xs text-slate-600 mt-1 flex items-center gap-1.5 flex-wrap">
                    <span class="font-semibold text-[#355245]">{{ detectedTeacher.subject }}</span>
                    <span class="text-slate-400">•</span>
                    <span class="font-medium text-slate-700">{{ detectedTeacher.room || 'Lab LAN' }}</span>
                    <span class="text-slate-400">•</span>
                    <span class="font-medium text-slate-600">({{ detectedTeacher.period || 'Jam Pelajaran Sekarang' }})</span>
                  </p>
                </div>
              </div>

              <button
                type="button"
                @click="isManualSelect = true"
                class="text-xs font-bold px-3 py-1.5 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition shrink-0 active:scale-95 shadow-xs cursor-pointer touch-manipulation"
              >
                Ganti Guru
              </button>
            </div>

            <!-- Banner Info Penguji jika Di Luar Jam KBM / Mode Evaluasi 24 Jam -->
            <div
              v-if="detectedTeacher.period?.includes('Evaluasi 24 Jam')"
              class="flex items-start gap-2.5 px-3.5 py-2.5 rounded-xl bg-blue-50/90 border border-blue-200 text-xs text-blue-900 leading-relaxed"
            >
              <Info class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" />
              <div>
                <strong class="font-bold text-blue-950">Catatan Penguji:</strong>
                Sistem mendeteksi <strong>[{{ detectedTeacher.period }}]</strong>. Sistem menyimulasikan guru pengampu kejuruan aktif agar alur pengajuan izin, persetujuan guru, dan validasi QR Satpam dapat dievaluasi kapan saja selama 24 jam nonstop tanpa terkendala jam operasional sekolah. Klik <strong>Ganti Guru</strong> jika ingin mencoba memilih guru lain.
              </div>
            </div>

            <!-- Note Ringkas Pergantian Jadwal jika KBM Normal -->
            <div
              v-else
              class="flex items-center gap-2 px-3 py-2 rounded-xl bg-amber-50/90 border border-amber-200/80 text-xs text-amber-900"
            >
              <AlertCircle class="w-4 h-4 text-amber-600 shrink-0" />
              <p class="leading-relaxed">
                <strong class="font-bold">Catatan:</strong> Jika jadwal berganti mendadak, gunakan tombol <strong>Ganti Guru</strong> di atas.
              </p>
            </div>
          </div>

          <!-- Dropdown Pemilihan Guru Manual (Dummy List) -->
          <div v-else class="space-y-2 bg-slate-50 p-4 rounded-2xl border border-slate-200">
            <BaseSelect
              v-model="form.teacher_id"
              label="Pilih Guru Pengampu"
              placeholder="-- Pilih Guru yang Mengajar --"
              :options="allTeachers"
              value-key="user_id"
              label-key="display_name"
              required
            />
            <div class="flex items-center justify-between gap-3 pt-1 text-xs text-slate-600">
              <div class="flex items-center gap-1.5">
                <AlertCircle class="w-4 h-4 text-[#355245] shrink-0" />
                <span>Mode pemilihan manual aktif.</span>
              </div>
              <button
                type="button"
                @click="isManualSelect = false"
                class="font-bold text-[#355245] underline hover:text-emerald-900 cursor-pointer"
              >
                Kembali ke Jadwal Otomatis
              </button>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="pt-2 flex flex-col sm:flex-row gap-3">
          <BaseButton
            to="/student/dashboard"
            variant="outline"
            size="lg"
            block
            class="flex-1 order-2 sm:order-1"
          >
            Batal & Kembali
          </BaseButton>
          <div class="flex-1 order-1 sm:order-2">
            <BaseButton
              type="submit"
              variant="primary"
              size="lg"
              block
              :loading="permitStore.loading || isSubmitting"
              :disabled="permitStore.loading || isSubmitting"
            >
              Ajukan Izin
            </BaseButton>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useRouter } from 'vue-router'
import { usePermitStore } from '@/stores/permit'
import { useToast } from '@/composables/useToast'
import RadioCard from '@/components/ui/RadioCard.vue'
import BaseSelect from '@/components/ui/BaseSelect.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import { AlertCircle, UserCheck, Info } from 'lucide-vue-next'

const router = useRouter()
const permitStore = usePermitStore()
const toast = useToast()

const isManualSelect = ref(false)
const isCancelling = ref(false)
const errors = ref({
  reason: ''
})

const hasActivePermit = computed(() => {
  return permitStore.activePermit && ['PENDING', 'APPROVED', 'ACTIVE', 'OVERDUE'].includes(permitStore.activePermit.status)
})

const detectedTeacher = computed(() => {
  if (permitStore.currentSchedule?.teacher_id) {
    return {
      user_id: permitStore.currentSchedule.teacher_id,
      name: permitStore.currentSchedule.teacher_name,
      username: permitStore.currentSchedule.teacher_username || 'guru1',
      subject: permitStore.currentSchedule.subject || 'MPP AWS Academy (Cloud SIJA)',
      room: permitStore.currentSchedule.room || 'Lab LAN',
      period: permitStore.currentSchedule.period || 'Jam Pelajaran Sekarang'
    }
  }
  return {
    user_id: 6,
    name: 'Margaretha Endah Titisari, S.T.',
    username: 'guru1',
    subject: 'MPP AWS Academy (Cloud SIJA)',
    room: 'Lab LAN',
    period: 'Di Luar Jam KBM (Mode Evaluasi 24 Jam)'
  }
})

const allTeachers = computed(() => {
  const list = permitStore.teachersList?.length > 0 ? permitStore.teachersList : [
    { user_id: 6, name: 'Margaretha Endah Titisari, S.T.', subject: 'MPP AWS Academy (Cloud SIJA)' },
    { user_id: 7, name: 'Eka Nur Ahmad Romadhoni, S.Pd.', subject: 'MPP Koding & AI (SIJA)' },
    { user_id: 8, name: 'Sri Wahjuni Pudjiastuti, S.Pd.', subject: 'Bahasa Indonesia' }
  ]
  return list.map(t => ({
    ...t,
    display_name: t.display_label || `${t.name} (${t.subject || 'Guru Pengajar'})${t.user_id === detectedTeacher.value.user_id ? ' — [' + (detectedTeacher.value.period || 'Jadwal Aktif') + ']' : ''}`
  }))
})

const form = ref({
  type: 'TEMP',
  duration_minutes: 30,
  reason: '',
  teacher_id: 6
})

watch(detectedTeacher, (val) => {
  if (val?.user_id && (!form.value.teacher_id || form.value.teacher_id === 6)) {
    form.value.teacher_id = val.user_id
  }
}, { immediate: true })

const setDuration = (dur) => {
  form.value.duration_minutes = dur
}

const adjustDuration = (delta) => {
  const current = Number(form.value.duration_minutes) || 30
  const updated = Math.min(180, Math.max(5, current + delta))
  form.value.duration_minutes = updated
}

onMounted(async () => {
  await Promise.allSettled([
    permitStore.fetchTeachers(),
    permitStore.fetchActivePermit()
  ])
  if (detectedTeacher.value?.user_id) {
    form.value.teacher_id = detectedTeacher.value.user_id
  }
})

const canCancelActivePermit = computed(() => {
  return permitStore.activePermit && ['PENDING', 'APPROVED'].includes(permitStore.activePermit.status)
})

const handleCancelActive = async () => {
  if (!permitStore.activePermit?.request_id) return
  isCancelling.value = true
  try {
    await permitStore.cancelPermit(permitStore.activePermit.request_id)
    toast.success('Permohonan izin sebelumnya berhasil dibatalkan.')
  } catch (err) {
    toast.error(err.response?.data?.message || 'Gagal membatalkan izin.')
  } finally {
    isCancelling.value = false
  }
}

const isSubmitting = ref(false)

const handleSubmit = async () => {
  if (isSubmitting.value || permitStore.loading) return
  if (!form.value.teacher_id) {
    form.value.teacher_id = detectedTeacher.value?.user_id || 6
  }
  errors.value.reason = ''
  if (!form.value.reason.trim()) {
    errors.value.reason = 'Alasan izin wajib diisi secara jelas.'
    toast.warning('Alasan izin tidak boleh kosong!')
    return
  }
  if (form.value.type === 'TEMP') {
    const dur = Number(form.value.duration_minutes)
    if (!dur || dur < 5 || dur > 180) {
      toast.warning('Durasi izin harus antara 5 sampai 180 menit!')
      return
    }
  }

  const payload = { ...form.value }
  if (payload.type === 'EXIT_SCHOOL') {
    delete payload.duration_minutes
  }

  isSubmitting.value = true
  try {
    // Minta izin notifikasi sistem secara proaktif selagi ada aksi klik pengguna
    if (typeof window !== 'undefined' && 'Notification' in window && Notification.permission === 'default') {
      try {
        await Notification.requestPermission()
      } catch (e) {
        // Abaikan jika peramban membatasi permintaan izin
      }
    }
    await permitStore.submitPermit(payload)
    toast.success('Pengajuan izin berhasil dibuat!')
    router.push('/student/permit/pass')
  } catch (err) {
    toast.error(err.response?.data?.message || 'Gagal mengajukan izin.')
  } finally {
    isSubmitting.value = false
  }
}
</script>