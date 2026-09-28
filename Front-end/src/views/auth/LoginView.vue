<template>
  <div class="min-h-screen bg-canvas font-sans lg:grid lg:grid-cols-12">
    <!-- Brand Panel -->
    <div
      class="flex flex-col bg-primary-deep px-5 py-6 text-white sm:px-8 lg:col-span-5 lg:px-10 lg:py-10"
    >
      <router-link
        to="/"
        class="inline-flex min-h-11 w-fit shrink-0 items-center gap-2 rounded text-caption font-medium text-white/70 transition-colors duration-150 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70 focus-visible:ring-offset-2 focus-visible:ring-offset-primary-deep"
      >
        <ArrowLeft class="h-4 w-4" aria-hidden="true" />
        Kembali ke Beranda
      </router-link>

      <div class="flex flex-1 items-center justify-center py-6 lg:py-10">
        <div class="w-full max-w-sm">
          <div class="flex flex-col items-center gap-3.5 text-center lg:gap-4">
            <span
              class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg border border-white/20 text-body font-bold tracking-tight text-white lg:h-14 lg:w-14 lg:text-h3"
              aria-hidden="true"
            >
              SC
            </span>
            <div>
              <p class="text-h2 font-bold tracking-tight text-white">Student Care</p>
              <p class="mt-1.5 text-caption uppercase tracking-[0.18em] text-white/70">
                SMKN 2 Depok Sleman
              </p>
            </div>
          </div>

          <p class="mt-6 text-center text-body leading-relaxed text-white/80 lg:text-body-lg">
            Portal terpadu perizinan digital E-Permit, verifikasi gerbang pos satpam, pemantauan presensi kelas,
            dan konseling Care BK.
          </p>

          <div class="mt-7 hidden border-l-2 border-white/25 pl-4 sm:block">
            <p class="flex items-center gap-2 text-caption font-semibold text-white">
              <ShieldCheck class="h-4 w-4 shrink-0" aria-hidden="true" />
              Sistem Keamanan Terenkripsi
            </p>
            <p class="mt-1.5 text-caption leading-relaxed text-white/70">
              Otentikasi token persisten Laravel Sanctum dengan verifikasi hak akses berbasis peran (RBAC).
            </p>
          </div>
        </div>
      </div>

      <p class="shrink-0 text-micro text-white/70 lg:flex lg:min-h-11 lg:items-end">
        &copy; 2026 STEMBAYO &bull; Projek JHIC
      </p>
    </div>

    <!-- Login Form -->
    <div class="flex flex-col bg-card lg:col-span-7">
      <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center px-5 py-10 sm:px-8 lg:py-12">
        <div>
          <h1 class="text-h2 font-bold tracking-tight text-heading sm:text-h1">Masuk ke akun Anda</h1>
          <p class="mt-2 text-body text-muted">
            Gunakan identitas dan kata sandi terdaftar untuk mengakses portal Anda.
          </p>
        </div>

        <p
          v-if="authStore.error"
          role="alert"
          class="mt-6 flex items-start gap-3 rounded-lg border border-status-danger-border bg-status-danger-bg px-4 py-3"
        >
          <AlertCircle class="mt-0.5 h-4 w-4 shrink-0 text-status-danger-fg" aria-hidden="true" />
          <span class="text-body leading-relaxed text-status-danger-fg">{{ authStore.error }}</span>
        </p>

        <form @submit.prevent="handleLogin" class="mt-7 space-y-5">
          <BaseInput
            id="identity"
            label="NIS / NIP / Username / Email"
            placeholder="Masukkan NIS, NIP, atau username..."
            v-model="identity"
            required
          >
            <template #icon-left><User class="h-4 w-4" aria-hidden="true" /></template>
          </BaseInput>

          <PasswordInput
            id="password"
            label="Kata Sandi"
            placeholder="••••••••"
            v-model="password"
            required
          >
            <template #icon-left><Lock class="h-4 w-4" aria-hidden="true" /></template>
          </PasswordInput>

          <BaseButton type="submit" variant="primary" size="lg" block :loading="authStore.loading">
            Masuk ke Sistem
          </BaseButton>
        </form>

        <!-- Akses Cepat (Demo) -->
        <section class="mt-9 border-t border-border pt-6" aria-labelledby="quick-login-title">
          <div class="flex flex-wrap items-baseline justify-between gap-x-3 gap-y-1">
            <h2 id="quick-login-title" class="text-caption font-semibold uppercase tracking-[0.14em] text-muted">
              Akses Cepat &middot; Demo
            </h2>
            <span class="font-mono text-micro text-muted">pass: password</span>
          </div>

          <div
            class="mt-3 grid grid-cols-2 gap-2 sm:grid-cols-4"
            role="group"
            aria-labelledby="quick-login-title"
          >
            <button
              v-for="role in presets"
              :key="role.id"
              type="button"
              @click="quickLogin(role)"
              :disabled="authStore.loading"
              class="min-h-11 rounded-lg border px-3 py-2 text-left transition-colors duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
              :class="
                isSelected(role)
                  ? 'border-primary bg-primary-soft'
                  : 'border-border bg-card hover:border-border-strong hover:bg-subtle'
              "
            >
              <span
                class="block text-body font-semibold"
                :class="isSelected(role) ? 'text-primary-deep' : 'text-heading'"
              >
                {{ role.label }}
                <span v-if="isSelected(role)" class="sr-only">(terpilih)</span>
              </span>
              <span class="mt-0.5 block font-mono text-micro text-muted">{{ role.username }}</span>
            </button>
          </div>
        </section>
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
import {
  User,
  Lock,
  ArrowLeft,
  ShieldCheck,
  AlertCircle,
  GraduationCap,
  UserCheck,
  Shield,
  HeartHandshake
} from 'lucide-vue-next'

const authStore = useAuthStore()
const toast = useToast()

const presets = [
  { id: 'student', label: 'Siswa', icon: GraduationCap, username: 'siswa1', password: 'password' },
  { id: 'teacher', label: 'Guru', icon: UserCheck, username: 'guru1', password: 'password' },
  { id: 'satpam', label: 'Satpam', icon: Shield, username: 'satpam1', password: 'password' },
  { id: 'bk', label: 'Guru BK', icon: HeartHandshake, username: 'bk1', password: 'password' }
]

const identity = ref('siswa1')
const password = ref('password')

const isSelected = (rolePreset) => identity.value === rolePreset.username

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
