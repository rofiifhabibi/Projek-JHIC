<template>
  <div class="space-y-6">
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-6">
      <div class="border-b border-slate-100 pb-4">
        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-purple-50 text-purple-700 text-xs font-bold uppercase tracking-wider mb-2">
          <ShieldAlert class="w-3.5 h-3.5" /> Care BK — Layanan Konseling Tertutup
        </div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Formulir Pengaduan & Konseling Siswa</h2>
        <p class="text-xs text-slate-500 mt-0.5">Sampaikan kendala perundungan, akademik, atau konseling pribadi secara aman ke Guru BK.</p>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Kategori Aduan (3 Kategori) -->
        <div class="space-y-3">
          <label class="text-xs font-semibold uppercase tracking-wider text-slate-700">
            1. Kategori Kasus / Aduan <span class="text-rose-500">*</span>
          </label>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <RadioCard
              v-model="form.category"
              value="BULLYING"
              name="report-cat"
              title="Perundungan (Bullying)"
              description="Intimidasi, pemerasan, atau kekerasan verbal/fisik."
            />
            <RadioCard
              v-model="form.category"
              value="ACADEMIC"
              name="report-cat"
              title="Kendala Pembelajaran"
              description="Kesulitan belajar, tugas, atau konseling nilai."
            />
            <RadioCard
              v-model="form.category"
              value="PERSONAL"
              name="report-cat"
              title="Konseling Pribadi"
              description="Kesehatan mental, keluh kesah, atau masalah keluarga."
            />
          </div>
        </div>

        <!-- Judul & Kronologi -->
        <BaseInput
          id="report-title"
          label="2. Judul Aduan Singkat"
          placeholder="Misal: Tindakan intimidasi di area kantin belakang..."
          v-model="form.title"
          required
        />

        <div class="space-y-1.5">
          <label class="text-xs font-semibold uppercase tracking-wider text-slate-700">
            3. Rincian Kronologi Kejadian <span class="text-rose-500">*</span>
          </label>
          <textarea
            v-model="form.description"
            rows="4"
            required
            placeholder="Ceritakan waktu, tempat, dan rincian kejadian secara lengkap..."
            class="w-full rounded-xl border border-slate-200 bg-white p-3.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#355245] focus:ring-2 focus:ring-[#355245] focus:outline-none transition"
          ></textarea>
        </div>

        <!-- Jaminan Kerahasiaan & Privasi Terisolasi -->
        <div class="p-4 rounded-2xl bg-[#E8EFEA] border border-[#355245]/20 flex items-start gap-3.5">
          <div class="w-9 h-9 rounded-xl bg-[#355245] text-white flex items-center justify-center shrink-0 mt-0.5">
            <ShieldCheck class="w-5 h-5" />
          </div>
          <div class="space-y-1 text-xs">
            <div class="font-bold text-[#355245] text-sm">Privasi Terisolasi & Kerahasiaan Terjamin</div>
            <p class="text-slate-600 leading-relaxed text-[11px]">
              Data identitas siswa (Nama, NIS, dan Kelas) hanya dapat diakses secara tertutup oleh Guru BK untuk pendampingan konseling dan penanganan kasus. Data ini terisolasi sepenuhnya dan <strong>tidak dapat dilihat oleh siswa lain</strong>.
            </p>
          </div>
        </div>

        <BaseButton
          type="submit"
          variant="primary"
          size="lg"
          block
          :loading="reportStore.loading"
        >
          Kirim Laporan BK
        </BaseButton>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useReportStore } from '@/stores/report'
import { useToast } from '@/composables/useToast'
import RadioCard from '@/components/ui/RadioCard.vue'
import BaseInput from '@/components/ui/BaseInput.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import { ShieldAlert, ShieldCheck } from 'lucide-vue-next'

const router = useRouter()
const reportStore = useReportStore()
const toast = useToast()

const form = ref({
  category: 'BULLYING',
  title: '',
  description: ''
})

const handleSubmit = async () => {
  if (!form.value.title.trim() || !form.value.description.trim()) {
    toast.warning('Judul dan kronologi aduan wajib diisi!')
    return
  }

  try {
    await reportStore.submitReport(form.value)
    toast.success('Laporan pengaduan berhasil dikirim ke Guru BK!')
    router.push('/student/tracking')
  } catch (err) {
    toast.error(err.response?.data?.message || 'Gagal mengirim laporan.')
  }
}
</script>