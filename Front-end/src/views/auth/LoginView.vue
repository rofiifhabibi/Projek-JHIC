<template>
  <div class="min-h-screen bg-[#F4F7F4] flex items-center justify-center p-4 sm:p-6 lg:p-8 font-sans">
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-md border border-slate-200 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[600px]">
      
      <!-- Left Column: Branding Showcase (Desktop) -->
      <div class="lg:col-span-5 bg-gradient-to-br from-[#355245] via-[#273e34] to-[#1c2e26] text-white p-8 lg:p-12 flex flex-col justify-between relative overflow-hidden">
        <div class="relative z-10 space-y-4">
          <router-link to="/" class="inline-flex items-center gap-2.5 text-white/80 hover:text-white transition text-xs font-semibold uppercase tracking-wider">
            <ArrowLeft class="w-4 h-4" />
            Kembali ke Beranda
          </router-link>

          <div class="pt-6">
            <AppLogo size="xl" theme="emerald" class="mb-4" />
            <h1 class="text-3xl lg:text-4xl font-black tracking-tight leading-tight">
              StudentCare
            </h1>
            <p class="text-xs font-semibold text-emerald-300 uppercase tracking-widest mt-1">
              SMKN 2 Depok Sleman
            </p>
          </div>
        </div>

        <div class="relative z-10 space-y-4 my-8">
          <p class="text-xs text-slate-300 leading-relaxed">
            Platform terpadu layanan perizinan siswa, pemantauan kelas, serta bimbingan dan konseling di lingkungan SMK N 2 Depok Sleman.
          </p>
          <div class="p-4 rounded-2xl bg-[#1c2e26]/60 border border-emerald-500/20 text-xs space-y-1.5">
            <div class="flex items-center gap-2 font-semibold text-emerald-200">
              <ShieldCheck class="w-4 h-4" />
              Aman & Terintegrasi
            </div>
            <p class="text-xs text-slate-300/90 leading-relaxed">
              Setiap aktivitas izin dan konseling terhubung langsung serta dipantau oleh bapak/ibu guru dan pihak sekolah.
            </p>
          </div>
        </div>

        <div class="relative z-10 text-xs text-emerald-200/60 font-medium">
          &copy; 2026 STEMBAYO • Projek JHIC
        </div>
      </div>

      <!-- Right Column: Login Form -->
      <div class="lg:col-span-7 p-8 lg:p-12 flex flex-col justify-center bg-white space-y-6">
        <div>
          <h2 class="text-2xl font-black text-slate-900 tracking-tight">Masuk ke Akun Anda</h2>
          <p class="text-xs text-slate-600 font-medium mt-1">Masukkan NIS, NIP, atau username beserta kata sandi Anda.</p>
        </div>

        <!-- Preset Selector for Development Testing -->
        <div v-if="isDevMode" class="space-y-2">
          <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-600">
            <span>Akun Uji Coba Cepat</span>
            <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded font-mono text-xs">Sandi: password</span>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
            <button
              v-for="role in presets"
              :key="role.id"
              type="button"
              @click="quickLogin(role)"
              :disabled="authStore.loading"
              class="flex flex-col items-center justify-center p-3 rounded-2xl border border-slate-200 hover:border-[#355245] bg-slate-50/70 hover:bg-[#E8EFEA]/60 transition-all text-center group active:scale-95 disabled:opacity-50 shadow-xs"
            >
              <div class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center mb-1.5 text-[#355245] group-hover:bg-[#355245] group-hover:text-white transition-all shadow-xs">
                <component :is="role.icon" class="w-4 h-4" />
              </div>
              <span class="text-xs font-bold text-slate-800 group-hover:text-[#355245]">{{ role.label }}</span>
              <span class="text-xs text-slate-600 font-mono mt-0.5">{{ role.username }}</span>
            </button>
          </div>
        </div>

        <div class="relative flex items-center justify-center">
          <div class="border-t border-slate-200 w-full"></div>
          <span class="bg-white px-3 text-xs font-bold uppercase tracking-widest text-slate-500 absolute">Atau Masuk Manual</span>
        </div>

        <!-- Form -->
        <form @submit.prevent="handleLogin" class="space-y-4">
          <BaseInput
            id="identity"
            label="NIS / NIP / Username"
            placeholder="Masukkan NIS, NIP, atau username..."
            v-model="identity"
            required
          >
            <template #icon-left><User class="w-4 h-4" /></template>
          </BaseInput>

          <PasswordInput
            id="password"
            label="Kata Sandi"
            placeholder="••••••••"
            v-model="password"
            required
          >
            <template #icon-left><Lock class="w-4 h-4" /></template>
          </PasswordInput>

          <BaseButton
            type="submit"
            variant="primary"
            size="lg"
            block
            :loading="authStore.loading"
          >
            Masuk
          </BaseButton>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useToast } from '@/composables/useToast'
import BaseInput from '@/components/ui/BaseInput.vue'
import PasswordInput from '@/components/ui/PasswordInput.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import AppLogo from '@/components/ui/AppLogo.vue'
import { User, Lock, ArrowLeft, ShieldCheck, GraduationCap, UserCheck, Shield, HeartHandshake } from 'lucide-vue-next'

const authStore = useAuthStore()
const toast = useToast()

const isDevMode = import.meta.env.DEV

const presets = [
  { id: 'student', label: 'Siswa', icon: GraduationCap, username: 'siswa1', password: 'password' },
  { id: 'teacher', label: 'Guru', icon: UserCheck, username: 'guru1', password: 'password' },
  { id: 'satpam', label: 'Satpam', icon: Shield, username: 'satpam1', password: 'password' },
  { id: 'bk', label: 'Guru BK', icon: HeartHandshake, username: 'bk1', password: 'password' }
]

const identity = ref(isDevMode ? 'siswa1' : '')
const password = ref(isDevMode ? 'password' : '')

const quickLogin = async (rolePreset) => {
  identity.value = rolePreset.username
  password.value = rolePreset.password
  try {
    await authStore.login(identity.value, password.value)
    toast.success(`Berhasil masuk sebagai ${rolePreset.label}!`)
  } catch (err) {
    toast.error(err.response?.data?.message || 'Gagal masuk, periksa akun Anda.')
  }
}

const handleLogin = async () => {
  try {
    await authStore.login(identity.value, password.value)
    toast.success('Berhasil masuk!')
  } catch (err) {
    toast.error(err.response?.data?.message || 'Gagal masuk, periksa kembali username dan kata sandi.')
  }
}
</script>
