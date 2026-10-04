<template>
  <div class="min-h-screen bg-[#F4F7F4] flex items-center justify-center p-3.5 sm:p-6 lg:p-8 font-sans">
    <div class="w-full max-w-md md:max-w-5xl bg-white rounded-2xl sm:rounded-3xl shadow-sm md:shadow-md border border-slate-200 overflow-hidden grid grid-cols-1 md:grid-cols-12 md:min-h-[560px]">
      
      <!-- Left Column: Branding Showcase (Tablet & Desktop) -->
      <div class="hidden md:flex md:col-span-5 bg-gradient-to-br from-[#355245] via-[#273e34] to-[#1c2e26] text-white p-6 md:p-8 lg:p-12 flex-col justify-between relative overflow-hidden">
        <div class="relative z-10 space-y-4">
          <router-link to="/" class="inline-flex items-center gap-2 text-white/80 hover:text-white transition text-xs font-semibold uppercase tracking-wider">
            <ArrowLeft class="w-4 h-4" />
            Kembali ke Beranda
          </router-link>

          <div class="pt-4 lg:pt-6 space-y-2">
            <img src="/logos/studentcare-logo-white.png" alt="StudentCare SMKN 2 Depok Sleman" class="h-10 md:h-12 lg:h-14 object-contain" />
            <p class="text-[11px] lg:text-xs font-semibold text-emerald-300 uppercase tracking-widest">
              Sistem Perizinan & Konseling Siswa
            </p>
          </div>
        </div>

        <div class="relative z-10 space-y-3.5 my-6 lg:my-8">
          <p class="text-xs text-slate-300 leading-relaxed">
            Layanan izin keluar-masuk sekolah dan bimbingan konseling untuk warga SMKN 2 Depok Sleman.
          </p>
          <div class="p-3.5 lg:p-4 rounded-2xl bg-[#1c2e26]/60 border border-emerald-500/20 text-xs space-y-1">
            <div class="flex items-center gap-2 font-semibold text-emerald-200 text-xs">
              <ShieldCheck class="w-4 h-4 shrink-0" />
              Terhubung ke Guru & BK
            </div>
            <p class="text-xs text-slate-300/90 leading-relaxed">
              Setiap aktivitas izin dan konseling terhubung langsung dengan guru pengajar dan guru BK.
            </p>
          </div>
        </div>

        <div class="relative z-10 text-xs text-emerald-200/60 font-medium">
          &copy; 2026 StudentCare • SMKN 2 Depok Sleman
        </div>
      </div>

      <!-- Right Column: Login Form -->
      <div class="md:col-span-7 p-5 sm:p-8 lg:p-12 flex flex-col justify-center bg-white space-y-5 sm:space-y-6">
        <div>
          <!-- Mobile Top Bar: Back Link + Logo -->
          <div class="md:hidden flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <router-link to="/" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-[#355245] transition">
              <ArrowLeft class="w-4 h-4" />
              <span>Beranda</span>
            </router-link>
            <img src="/logos/studentcare-logo.png" alt="StudentCare" class="h-7 object-contain" />
          </div>

          <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Masuk ke Akun Anda</h1>
          <p class="text-xs text-slate-600 font-medium mt-1">Masukkan NIS, NIP, atau username beserta kata sandi Anda.</p>
        </div>

        <!-- Preset Selector for Development Testing (Only shown in DEV mode) -->
        <div v-if="isDevMode" class="space-y-3">
          <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-600">
            <span>Akun Uji Coba Cepat</span>
            <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded font-mono text-[11px]">Sandi: password</span>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-2.5">
            <button
              v-for="role in presets"
              :key="role.id"
              type="button"
              @click="quickLogin(role)"
              :disabled="authStore.loading || isLoggingIn"
              class="flex flex-col items-center justify-center p-2.5 sm:p-3 rounded-2xl border transition-all text-center group active:scale-95 disabled:opacity-50 shadow-xs cursor-pointer touch-manipulation"
              :class="identity === role.username
                ? 'border-[#355245] bg-[#E8EFEA] ring-2 ring-[#355245]/20'
                : 'border-slate-200 hover:border-[#355245] bg-slate-50/70 hover:bg-[#E8EFEA]/60'"
            >
              <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center mb-1 text-[#355245] group-hover:bg-[#355245] group-hover:text-white transition-all shadow-xs">
                <component :is="role.icon" class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
              </div>
              <span class="text-xs font-bold text-slate-800 group-hover:text-[#355245]">{{ role.label }}</span>
              <span class="text-[11px] text-slate-500 font-mono mt-0.5">{{ role.username }}</span>
            </button>
          </div>

          <div class="relative flex items-center justify-center pt-1">
            <div class="border-t border-slate-200 w-full"></div>
            <span class="bg-white px-3 text-xs font-bold uppercase tracking-widest text-slate-400 absolute">Atau Masuk Manual</span>
          </div>
        </div>

        <!-- Form -->
        <form @submit.prevent="handleLogin" class="space-y-4" novalidate>
          <BaseInput
            id="identity"
            label="NIS / NIP / Username"
            placeholder="Masukkan NIS, NIP, atau username..."
            v-model="identity"
            :error="errors.identity"
            @update:model-value="errors.identity = ''; errors.general = ''"
            required
          >
            <template #icon-left><User class="w-4 h-4" /></template>
          </BaseInput>

          <PasswordInput
            id="password"
            label="Kata Sandi"
            placeholder="••••••••"
            v-model="password"
            :error="errors.password"
            @update:model-value="errors.password = ''; errors.general = ''"
            required
          >
            <template #icon-left><Lock class="w-4 h-4" /></template>
          </PasswordInput>

          <div v-if="errors.general" class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 font-medium leading-relaxed">
            {{ errors.general }}
          </div>

          <BaseButton
            type="submit"
            variant="primary"
            size="lg"
            block
            :loading="authStore.loading || isLoggingIn"
            :disabled="authStore.loading || isLoggingIn"
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
import { User, Lock, ArrowLeft, ShieldCheck, GraduationCap, UserCheck, Shield, HeartHandshake } from 'lucide-vue-next'

const authStore = useAuthStore()
const toast = useToast()

import { useRoute } from 'vue-router'
import { onMounted } from 'vue'

const route = useRoute()
// Akun demo penguji selalu aktif agar juri lomba JHIC dapat menguji keempat peran secara instan
const isDevMode = true

const presets = [
  { id: 'student', label: 'Siswa', icon: GraduationCap, username: 'siswa1', password: 'password' },
  { id: 'teacher', label: 'Guru', icon: UserCheck, username: 'guru1', password: 'password' },
  { id: 'satpam', label: 'Satpam', icon: Shield, username: 'satpam1', password: 'password' },
  { id: 'bk', label: 'Guru BK', icon: HeartHandshake, username: 'bk1', password: 'password' }
]

const identity = ref('')
const password = ref('')

onMounted(() => {
  const queryRole = route.query.role
  if (queryRole) {
    const matched = presets.find(p => p.id === queryRole)
    if (matched) {
      identity.value = matched.username
      password.value = matched.password
    }
  }
})
const isLoggingIn = ref(false)

const errors = ref({
  identity: '',
  password: '',
  general: ''
})

const validate = () => {
  let valid = true
  errors.value = { identity: '', password: '', general: '' }
  if (!identity.value?.trim()) {
    errors.value.identity = 'NIS, NIP, atau username wajib diisi.'
    valid = false
  }
  if (!password.value) {
    errors.value.password = 'Kata sandi wajib diisi.'
    valid = false
  }
  return valid
}

const quickLogin = async (rolePreset) => {
  if (isLoggingIn.value || authStore.loading) return
  isLoggingIn.value = true
  identity.value = rolePreset.username
  password.value = rolePreset.password
  errors.value = { identity: '', password: '', general: '' }
  try {
    await authStore.login(identity.value, password.value)
    toast.success(`Berhasil masuk sebagai ${rolePreset.label}!`)
  } catch (err) {
    toast.error(err.response?.data?.message || 'Gagal masuk, periksa akun Anda.')
  } finally {
    isLoggingIn.value = false
  }
}

const handleLogin = async () => {
  if (!validate()) return
  if (isLoggingIn.value || authStore.loading) return
  isLoggingIn.value = true
  errors.value = { identity: '', password: '', general: '' }
  try {
    await authStore.login(identity.value, password.value)
    toast.success('Berhasil masuk!')
  } catch (err) {
    const msg = err.response?.data?.message || 'Identitas atau kata sandi tidak cocok. Silakan periksa kembali.'
    errors.value.general = msg
    errors.value.password = 'Periksa kembali kata sandi akun Anda.'
    toast.error(msg)
  } finally {
    isLoggingIn.value = false
  }
}
</script>
