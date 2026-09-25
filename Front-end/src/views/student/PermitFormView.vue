<template>
  <div class="space-y-6">
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-6">
      <div class="border-b border-slate-100 pb-4">
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Form Pengajuan Surat Izin</h2>
        <p class="text-xs text-slate-500 mt-0.5">Isi rincian permohonan izin untuk dikonfirmasi oleh guru pengampu kelas.</p>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Step 1: Jenis Perizinan -->
        <div class="space-y-3">
          <label class="text-xs font-semibold uppercase tracking-wider text-slate-700">
            1. Pilih Jenis Perizinan <span class="text-rose-500">*</span>
          </label>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <RadioCard
              v-model="form.type"
              value="TEMP"
              name="permit-type"
              title="Keluar Sementara (TEMP)"
              description="Ke UKS, Toilet, Tata Usaha, atau keperluan singkat lainnya."
            />
            <RadioCard
              v-model="form.type"
              value="EXIT_SCHOOL"
              name="permit-type"
              title="Izin Pulang Sekolah"
              description="Meninggalkan lingkungan sekolah karena sakit/keperluan mendesak."
            />
          </div>
        </div>

        <!-- Step 2: Durasi Izin (Hanya untuk TEMP) -->
        <div v-if="form.type === 'TEMP'" class="space-y-3">
          <div class="flex items-center justify-between">
            <label class="text-xs font-semibold uppercase tracking-wider text-slate-700">
              2. Estimasi Durasi Izin <span class="text-rose-500">*</span>
            </label>
            <span class="text-xs font-bold text-[#355245] bg-[#E8EFEA] px-2.5 py-0.5 rounded-full">
              {{ form.duration_minutes || 0 }} Menit
            </span>
          </div>

          <!-- Opsi Cepat (Quick Presets) -->
          <div class="grid grid-cols-4 gap-2">
            <button
              v-for="dur in [10, 15, 30, 45]"
              :key="dur"
              type="button"
              @click="setDuration(dur)"
              class="py-2.5 px-2 rounded-xl border text-xs font-bold transition text-center"
              :class="form.duration_minutes === dur ? 'border-[#355245] bg-[#E8EFEA] text-[#355245] shadow-xs' : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300'"
            >
              {{ dur }} Menit
            </button>
          </div>

          <!-- Input Durasi Kustom & Stepper -->
          <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 space-y-3">
            <div class="flex items-center justify-between text-xs">
              <span class="font-medium text-slate-700">Atur Waktu Bebas (Kustom):</span>
              <span class="text-[11px] text-slate-400">Rentang: 5 - 180 menit</span>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
              <!-- Tombol Kurang 5 Menit -->
              <button
                type="button"
                @click="adjustDuration(-5)"
                :disabled="form.duration_minutes <= 5"
                class="h-10 px-3 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center transition shadow-xs"
                title="Kurangi 5 menit"
              >
                -5 mnt
              </button>

              <!-- Input Angka Bebas -->
              <div class="relative flex-1">
                <input
                  v-model.number="form.duration_minutes"
                  type="number"
                  min="5"
                  max="180"
                  step="1"
                  required
                  placeholder="30"
                  class="w-full text-center font-bold text-slate-900 bg-white border border-slate-200 rounded-xl py-2 px-3 text-sm focus:border-[#355245] focus:ring-2 focus:ring-[#355245] focus:outline-none transition pr-14"
                />
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-medium text-slate-400 pointer-events-none">
                  Menit
                </span>
              </div>

              <!-- Tombol Tambah 5 Menit -->
              <button
                type="button"
                @click="adjustDuration(5)"
                :disabled="form.duration_minutes >= 180"
                class="h-10 px-3 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center transition shadow-xs"
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
          <label class="text-xs font-semibold uppercase tracking-wider text-slate-700">
            3. Alasan Izin Spesifik <span class="text-rose-500">*</span>
          </label>
          <textarea
            v-model="form.reason"
            rows="3"
            required
            placeholder="Jelaskan secara detail keperluan izin Anda (misal: Mengambil berkas di TU)..."
            class="w-full rounded-xl border border-slate-200 bg-white p-3.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#355245] focus:ring-2 focus:ring-[#355245] focus:outline-none transition"
          ></textarea>
        </div>

        <!-- Step 4: Pilih Guru Pengampu -->
        <BaseSelect
          v-model="form.teacher_id"
          label="4. Pilih Guru Pengampu Saat Ini"
          placeholder="-- Pilih Guru yang mengajar di kelas --"
          :options="teachersList"
          value-key="user_id"
          label-key="name"
          required
        />

        <!-- Action Button -->
        <div class="pt-2">
          <BaseButton
            type="submit"
            variant="primary"
            size="lg"
            block
            :loading="permitStore.loading"
          >
            Kirim Pengajuan Izin
          </BaseButton>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { usePermitStore } from '@/stores/permit'
import { useToast } from '@/composables/useToast'
import RadioCard from '@/components/ui/RadioCard.vue'
import BaseSelect from '@/components/ui/BaseSelect.vue'
import BaseButton from '@/components/ui/BaseButton.vue'

const router = useRouter()
const permitStore = usePermitStore()
const toast = useToast()

const form = ref({
  type: 'TEMP',
  duration_minutes: 30,
  reason: '',
  teacher_id: ''
})

const teachersList = computed(() => permitStore.teachersList)

const setDuration = (dur) => {
  form.value.duration_minutes = dur
}

const adjustDuration = (delta) => {
  const current = Number(form.value.duration_minutes) || 30
  const updated = Math.min(180, Math.max(5, current + delta))
  form.value.duration_minutes = updated
}

onMounted(async () => {
  await permitStore.fetchTeachers()
})

const handleSubmit = async () => {
  if (!form.value.teacher_id) {
    toast.warning('Pilih guru pengampu terlebih dahulu!')
    return
  }
  if (!form.value.reason.trim()) {
    toast.warning('Alasan izin wajib diisi secara rinci!')
    return
  }
  if (form.value.type === 'TEMP') {
    const dur = Number(form.value.duration_minutes)
    if (!dur || dur < 5 || dur > 180) {
      toast.warning('Durasi izin harus antara 5 sampai 180 menit!')
      return
    }
  }

  try {
    await permitStore.submitPermit(form.value)
    toast.success('Pengajuan izin berhasil dibuat!')
    router.push('/student/permit/pass')
  } catch (err) {
    toast.error(err.response?.data?.message || 'Gagal mengajukan izin.')
  }
}
</script>