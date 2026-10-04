<template>
  <div class="space-y-6">
    <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-slate-200 shadow-sm space-y-5 sm:space-y-6">
      <div class="border-b border-slate-100 pb-4">
        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#E8EFEA] text-[#355245] border border-[#d8e3db] text-xs font-bold uppercase tracking-wider mb-2">
          <ShieldAlert class="w-3.5 h-3.5" /> Layanan Bimbingan & Konseling (BK)
        </div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Pengaduan & Konseling Siswa</h2>
        <p class="text-xs text-slate-500 mt-0.5">Sampaikan kendala belajar, perundungan, atau keperluan konseling secara aman ke Guru BK.</p>
      </div>

      <!-- Banner Klarifikasi Terkait Izin Tertentu -->
      <div v-if="form.request_id" class="p-3.5 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-2.5 text-xs text-amber-900">
        <AlertOctagon class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" />
        <div class="leading-relaxed">
          <strong class="font-bold text-amber-950">Klarifikasi Terhubung dengan Izin #{{ form.request_id }}</strong>
          <p class="text-xs text-amber-700 mt-0.5">
            Laporan ini terhubung dengan izin kamu. Jika Guru BK menerima klarifikasi ini, status Alpha akan diperbarui menjadi Selesai.
          </p>
        </div>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-5 sm:space-y-6">
        <!-- Kategori Aduan (4 Kategori) -->
        <div class="space-y-3">
          <label class="text-xs font-semibold uppercase tracking-wider text-slate-700">
            1. Pilih Jenis Bantuan / Laporan <span class="text-rose-500">*</span>
          </label>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <RadioCard
              v-model="form.category"
              value="BULLYING"
              name="report-cat"
              title="Perundungan (Bullying)"
              description="Pemalakan, intimidasi, ejekan, atau kekerasan fisik."
            />
            <RadioCard
              v-model="form.category"
              value="ACADEMIC"
              name="report-cat"
              title="Masalah Belajar"
              description="Kesulitan memahami pelajaran, tugas menumpuk, atau remedial."
            />
            <RadioCard
              v-model="form.category"
              value="PERSONAL"
              name="report-cat"
              title="Konseling Pribadi"
              description="Keluh kesah pribadi, rasa cemas, atau masalah di rumah."
            />
            <RadioCard
              v-model="form.category"
              value="OTHERS"
              name="report-cat"
              title="Klarifikasi & Lainnya"
              description="Penjelasan izin Alpha atau kendala khusus lainnya."
            />
          </div>
        </div>

        <!-- Judul & Deskripsi Cerita -->
        <BaseInput
          id="report-title"
          label="2. Judul Laporan Singkat"
          :placeholder="categoryPlaceholder"
          v-model="form.title"
          :error="errors.title"
          @update:model-value="errors.title = ''"
          required
        />

        <div class="space-y-1.5">
          <label for="report-description" class="text-xs font-semibold uppercase tracking-wider text-slate-700">
            3. Ceritakan Masalah / Kejadiannya <span class="text-rose-500">*</span>
          </label>
          <textarea
            id="report-description"
            v-model="form.description"
            rows="4"
            required
            :placeholder="descriptionPlaceholder"
            @input="errors.description = ''"
            :aria-invalid="errors.description ? 'true' : undefined"
            :aria-describedby="errors.description ? 'report-desc-error' : undefined"
            class="w-full rounded-xl border bg-white p-3.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none transition"
            :class="errors.description ? 'border-rose-400 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20' : 'border-slate-200 focus:border-[#355245] focus:ring-2 focus:ring-[#355245]'"
          ></textarea>
          <p v-if="errors.description" id="report-desc-error" role="alert" class="text-xs text-rose-600 font-medium mt-0.5">
            {{ errors.description }}
          </p>
        </div>

        <!-- Pilihan Cara Bimbingan yang Membuat Siswa Nyaman -->
        <div class="space-y-3">
          <label class="text-xs font-semibold uppercase tracking-wider text-slate-700">
            4. Pilihan Cara Bimbingan yang Membuatmu Nyaman <span class="text-rose-500">*</span>
          </label>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <RadioCard
              v-model="form.follow_up_preference"
              value="WEB_MESSAGE"
              name="report-pref"
              title="Pesan Tertulis di Web"
              description="Ingin menerima saran atau tanggapan tertulis di sistem tanpa tatap muka langsung."
            />
            <RadioCard
              v-model="form.follow_up_preference"
              value="WHATSAPP"
              name="report-pref"
              title="Chat WhatsApp Pribadi"
              description="Bicara lebih santai melalui pesan chat dengan kontak khusus Guru BK."
            />
            <RadioCard
              v-model="form.follow_up_preference"
              value="NEUTRAL_MEET"
              name="report-pref"
              title="Janji Temu di Tempat Netral"
              description="Bertemu langsung di tempat yang tenang (misal: perpustakaan), bukan ruang BK umum."
            />
            <RadioCard
              v-model="form.follow_up_preference"
              value="INFO_ONLY"
              name="report-pref"
              title="Hanya Laporan Informasi"
              description="Sekolah cukup mengetahui kejadian ini, kamu belum perlu dihubungi."
            />
          </div>
        </div>

        <!-- Jaminan Kerahasiaan -->
        <div class="p-4 rounded-2xl bg-[#E8EFEA] border border-[#355245]/20 flex items-start gap-3.5">
          <div class="w-9 h-9 rounded-xl bg-[#355245] text-white flex items-center justify-center shrink-0 mt-0.5">
            <ShieldCheck class="w-5 h-5" />
          </div>
          <div class="space-y-1 text-xs">
            <div class="font-bold text-[#355245] text-sm">Kerahasiaan Terjamin</div>
            <p class="text-slate-600 leading-relaxed text-xs">
              Identitas dan isi ceritamu hanya bisa dibaca oleh Guru BK untuk pendampingan konseling. Laporan ini bersifat rahasia dan <strong>tidak dapat dilihat oleh siswa lain</strong>.
            </p>
          </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-2">
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
              :loading="reportStore.loading || isSubmitting"
              :disabled="reportStore.loading || isSubmitting"
            >
              Kirim Laporan BK
            </BaseButton>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useReportStore } from '@/stores/report'
import { useToast } from '@/composables/useToast'
import RadioCard from '@/components/ui/RadioCard.vue'
import BaseInput from '@/components/ui/BaseInput.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import { ShieldAlert, ShieldCheck, AlertOctagon } from 'lucide-vue-next'

const router = useRouter()
const route = useRoute()
const reportStore = useReportStore()
const toast = useToast()

const form = ref({
  category: 'BULLYING',
  follow_up_preference: 'WEB_MESSAGE',
  title: '',
  description: '',
  request_id: null
})

const categoryPlaceholder = computed(() => {
  switch (form.value.category) {
    case 'ACADEMIC':
      return 'Misal: Kendala motivasi belajar, penurunan nilai, atau konsentrasi di kelas...'
    case 'PERSONAL':
      return 'Misal: Kebingungan menentukan arah karir/kuliah atau masalah pribadi...'
    case 'BULLYING':
      return 'Misal: Laporan intimidasi, pemalakan, atau perlakuan tidak menyenangkan...'
    default:
      return 'Misal: Penjelasan klarifikasi izin atau kendala khusus...'
  }
})

const descriptionPlaceholder = computed(() => {
  switch (form.value.category) {
    case 'ACADEMIC':
      return 'Ceritakan kendala belajar yang sedang kamu alami (misal: sulit fokus, sering terlambat mengumpulkan tugas, atau penurunan motivasi sekolah)...'
    case 'PERSONAL':
      return 'Ceritakan hal yang sedang membebani pikiranmu atau hal yang ingin kamu konsultasikan secara rahasia...'
    case 'BULLYING':
      return 'Ceritakan waktu, tempat, atau perlakuan tidak menyenangkan yang kamu alami secara jelas...'
    default:
      return 'Tuliskan penjelasan atau klarifikasi secara rinci...'
  }
})

onMounted(() => {
  if (route.query.type === 'alpha') {
    form.value.category = 'OTHERS'
    form.value.title = 'Klarifikasi Status Alpha Izin'
    form.value.description = 'Yth. Bapak/Ibu Guru BK,\n\nSaya ingin menyampaikan penjelasan mengenai status izin saya yang ditandai Alpha hari ini karena:\n'
  }
  if (route.query.request_id || route.query.permit_id) {
    form.value.request_id = Number(route.query.request_id || route.query.permit_id)
  }
})

const isSubmitting = ref(false)

const errors = ref({
  title: '',
  description: ''
})

const validate = () => {
  let valid = true
  errors.value = { title: '', description: '' }
  if (!form.value.title.trim()) {
    errors.value.title = 'Judul laporan wajib diisi.'
    valid = false
  } else if (form.value.title.trim().length < 5) {
    errors.value.title = 'Judul laporan minimal 5 karakter.'
    valid = false
  }
  if (!form.value.description.trim()) {
    errors.value.description = 'Isi cerita masalah wajib diisi.'
    valid = false
  } else if (form.value.description.trim().length < 10) {
    errors.value.description = 'Ceritakan masalah secara lebih detail (minimal 10 karakter).'
    valid = false
  }
  return valid
}

const handleSubmit = async () => {
  if (!validate()) return
  if (isSubmitting.value || reportStore.loading) return

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
    await reportStore.submitReport(form.value)
    toast.success('Laporan berhasil dikirim ke Guru BK!')
    router.push('/student/tracking')
  } catch (err) {
    toast.error(err.response?.data?.message || 'Gagal mengirim laporan.')
  } finally {
    isSubmitting.value = false
  }
}
</script>