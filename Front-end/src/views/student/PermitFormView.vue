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
          <label class="text-xs font-semibold uppercase tracking-wider text-slate-700">
            2. Estimasi Durasi Izin <span class="text-rose-500">*</span>
          </label>
          <div class="grid grid-cols-4 gap-2">
            <button
              v-for="dur in [15, 30, 45, 60]"
              :key="dur"
              type="button"
              @click="form.duration_minutes = dur"
              class="py-2.5 px-3 rounded-xl border text-xs font-bold transition text-center"
              :class="form.duration_minutes === dur ? 'border-[#355245] bg-[#E8EFEA] text-[#355245]' : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300'"
            >
              {{ dur }} Menit
            </button>
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

  try {
    await permitStore.submitPermit(form.value)
    toast.success('Pengajuan izin berhasil dibuat!')
    router.push('/student/permit/pass')
  } catch (err) {
    toast.error(err.response?.data?.message || 'Gagal mengajukan izin.')
  }
}
</script>
