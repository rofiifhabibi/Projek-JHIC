<template>
  <div class="min-h-screen bg-[#F4F7F4] flex items-center justify-center p-4 sm:p-6 lg:p-8 font-sans">
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl border border-slate-200/80 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[600px]">
      
      <!-- Left Column: Branding Showcase (Desktop) -->
      <div class="lg:col-span-5 bg-gradient-to-br from-[#355245] via-[#273e34] to-[#1c2e26] text-white p-8 lg:p-12 flex flex-col justify-between relative overflow-hidden">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-4">
          <router-link to="/" class="inline-flex items-center gap-2.5 text-white/80 hover:text-white transition text-xs font-semibold uppercase tracking-wider">
            <ArrowLeft class="w-4 h-4" />
            Kembali ke Beranda
          </router-link>

          <div class="pt-6">
            <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center font-extrabold text-2xl text-white shadow-lg mb-4">
              SC
            </div>
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
            Portal terpadu perizinan digital E-Permit, verifikasi gerbang pos satpam, pemantauan presensi kelas, dan konseling Care BK.
          </p>
          <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm text-xs space-y-2">
            <div class="flex items-center gap-2 font-semibold text-emerald-200">
              <ShieldCheck class="w-4 h-4" />
              Sistem Keamanan Terenkripsi
            </div>
            <p class="text-[11px] text-slate-400">
              Otentikasi token persisten Laravel Sanctum dengan verifikasi hak akses berbasis peran (RBAC).
            </p>
          </div>
        </div>

        <div class="relative z-10 text-[11px] text-slate-400 font-medium">
          &copy; 2026 STEMBAYO • Projek JHIC
        </div>
      </div>

      <!-- Right Column: Login Form -->
      <div class="lg:col-span-7 p-8 lg:p-12 flex flex-col justify-center bg-white space-y-6">
        <div>
          <h2 class="text-2xl font-black text-slate-900 tracking-tight">Masuk ke Akun Anda</h2>
          <p class="text-xs text-slate-500 font-medium mt-1">Masukkan kredensial terdaftar untuk menguji atau mengakses portal.</p>
        </div>

        <!-- Preset Selector for Testing -->
        <div class="space-y-2">
          <div class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider text-slate-400">
            <span>UJI COBA AKUN CEPAT (1-CLICK TEST)</span>
            <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded font-mono">pass: password</span>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
            <button
              v-for="role in presets"
              :key="role.id"
              type="button"
              @click="quickLogin(role)"
              :disabled="authStore.loading"
              class="flex flex-col items-center justify-center p-3 rounded-xl border border-slate-200 hover:border-[#355245] bg-slate-50/60 hover:bg-[#E8EFEA]/50 transition-all text-center group active:scale-95 disabled:opacity-50"
            >
              <span class="text-xl mb-1">{{ role.icon }}</span>
              <span class="text-xs font-bold text-slate-900 group-hover:text-[#355245]">{{ role.label }}</span>
              <span class="text-[10px] text-slate-400 font-mono mt-0.5">{{ role.username }}</span>
            </button>
          </div>
        </div>

        <div class="relative flex items-center justify-center">
          <div class="border-t border-slate-200 w-full"></div>
          <span class="bg-white px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 absolute">Atau Login Manual</span>
        </div>

        <!-- Form -->
        <form @submit.prevent="handleLogin" class="space-y-4">
          <BaseInput
            id="identity"
            label="NIS / NIP / Username / Email"
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
            Masuk ke Sistem
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
import { User, Lock, ArrowLeft, ShieldCheck } from 'lucide-vue-next'

const authStore = useAuthStore()
const toast = useToast()

const presets = [
  { id: 'student', label: 'Siswa', icon: '🧑‍🎓', username: 'siswa1', password: 'password' },
  { id: 'teacher', label: 'Guru', icon: '👨‍🏫', username: 'guru1', password: 'password' },
  { id: 'satpam', label: 'Satpam', icon: '👮', username: 'satpam1', password: 'password' },
  { id: 'bk', label: 'Guru BK', icon: '📋', username: 'bk1', password: 'password' }
]

const identity = ref('siswa1')
const password = ref('password')

const quickLogin = async (rolePreset) => {
  identity.value = rolePreset.username
  password.value = rolePreset.password
  try {
    await authStore.login(identity.value, password.value)
    toast.success(`Berhasil login sebagai ${rolePreset.label}!`)
  } catch (err) {
    toast.error(err.response?.data?.message || 'Login gagal, periksa kredensial.')
  }
}

const handleLogin = async () => {
  try {
    await authStore.login(identity.value, password.value)
    toast.success('Berhasil login!')
  } catch (err) {
    toast.error(err.response?.data?.message || 'Login gagal, periksa identitas dan kata sandi.')
  }
}
</script>
