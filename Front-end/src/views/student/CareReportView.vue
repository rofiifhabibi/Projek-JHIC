<template>
  <div class="space-y-6">
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-6">
      <div class="border-b border-slate-100 pb-4">
        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-purple-50 text-purple-700 text-xs font-bold uppercase tracking-wider mb-2">
          <ShieldAlert class="w-3.5 h-3.5" /> Care BK — Layanan Konseling Rahasia
        </div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Formulir Pengaduan & Konseling Siswa</h2>
        <p class="text-xs text-slate-500 mt-0.5">Sampaikan kendala perundungan, fasilitas, akademik, atau pribadi secara aman ke Guru BK.</p>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Kategori Aduan -->
        <div class="space-y-3">
          <label class="text-xs font-semibold uppercase tracking-wider text-slate-700">
            1. Kategori Kasus / Aduan <span class="text-rose-500">*</span>
          </label>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <RadioCard v-model="form.category" value="BULLYING" name="report-cat" title="Perundungan (Bullying)" description="Intimidasi, pemerasan, atau kekerasan verbal/fisik." />
            <RadioCard v-model="form.category" value="FACILITY" name="report-cat" title="Fasilitas & Lab" description="Kerusakan alat praktikum, LCD, atau fasilitas kelas." />
            <RadioCard v-model="form.category" value="ACADEMIC" name="report-cat" title="Kendala Pembelajaran" description="Kesulitan belajar, tugas, atau konseling nilai." />
            <RadioCard v-model="form.category" value="PERSONAL" name="report-cat" title="Konseling Pribadi" description="Kesehatan mental, keluh kesah, atau masalah keluarga." />
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

        <!-- Anonymous Toggle -->
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-4">
          <div class="space-y-0.5">
            <div class="flex items-center gap-2 font-bold text-xs text-slate-900">
              <Shield class="w-4 h-4 text-purple-600" />
              Laporkan Secara Anonim (Rahasiakan Identitas)
            </div>
            <p class="text-[11px] text-slate-500">Nama dan kelas Anda tidak akan tercatat dalam laporan BK.</p>
          </div>
          <input
            type="checkbox"
            v-model="form.is_anonymous"
            class="w-5 h-5 accent-[#355245] rounded cursor-pointer"
          />
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
import { ShieldAlert, Shield } from 'lucide-vue-next'

const router = useRouter()
const reportStore = useReportStore()
const toast = useToast()

const form = ref({
  category: 'BULLYING',
  title: '',
  description: '',
  is_anonymous: false
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
