<template>
  <div class="flex min-h-screen flex-col bg-canvas font-sans text-body selection:bg-primary-soft-strong selection:text-primary-deep">
    <!-- Header -->
    <header class="sticky top-0 z-40 border-b border-border bg-card">
      <div class="mx-auto flex w-full max-w-6xl items-center justify-between gap-4 px-4 py-3 sm:px-6 sm:py-4">
        <div class="flex min-w-0 items-center gap-3">
          <span
            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary-deep text-caption font-bold tracking-tight text-white"
            aria-hidden="true"
          >
            SC
          </span>
          <span class="min-w-0 leading-tight">
            <span class="block truncate text-body font-bold tracking-tight text-heading">Student Care</span>
            <span class="block truncate text-micro text-muted">SMKN 2 Depok Sleman</span>
          </span>
        </div>

        <nav class="hidden items-center gap-7 md:flex" aria-label="Navigasi utama">
          <a
            v-for="link in navLinks"
            :key="link.href"
            :href="link.href"
            class="rounded text-body text-muted transition-colors duration-150 hover:text-heading focus-ring"
          >
            {{ link.label }}
          </a>
        </nav>

        <router-link to="/login" tabindex="-1" class="shrink-0 rounded-lg">
          <BaseButton variant="primary" size="sm">
            <template #icon-left><LogIn class="h-4 w-4" aria-hidden="true" /></template>
            Masuk Portal
          </BaseButton>
        </router-link>
      </div>
    </header>

    <main class="flex-1">
      <!-- Hero -->
      <section class="border-b border-border bg-card">
        <div class="mx-auto w-full max-w-6xl px-4 py-14 sm:px-6 sm:py-20 lg:py-24">
          <div class="max-w-2xl">
            <p class="text-caption font-semibold uppercase tracking-[0.18em] text-primary">
              Appermit Student Care
            </p>
            <h1
              class="mt-4 text-h1 font-extrabold leading-[1.18] tracking-tight text-heading text-balance sm:text-hero"
            >
              Perizinan siswa dan pendampingan BK dalam satu sistem
            </h1>
            <p class="mt-5 max-w-xl text-body-lg leading-relaxed text-body">
              Integrasi empat entitas sekolah — siswa, guru pengampu, petugas gerbang, dan guru BK — untuk
              memastikan ketertiban, keamanan mobilitas, dan perlindungan siswa secara real-time.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
              <router-link to="/login" tabindex="-1" class="w-full sm:w-auto">
                <BaseButton variant="primary" size="lg" block>
                  Buka Portal Login
                  <template #icon-right><ArrowRight class="h-4 w-4" aria-hidden="true" /></template>
                </BaseButton>
              </router-link>
              <a href="#fitur" class="w-full sm:w-auto">
                <BaseButton variant="outline" size="lg" block>
                  Pelajari Selengkapnya
                </BaseButton>
              </a>
            </div>
          </div>
        </div>
      </section>

      <!-- Fitur Utama -->
      <section id="fitur" class="scroll-mt-20 border-b border-border sm:scroll-mt-24">
        <div class="mx-auto w-full max-w-6xl px-4 py-14 sm:px-6 sm:py-20">
          <div class="max-w-2xl">
            <p class="text-caption font-semibold uppercase tracking-[0.18em] text-primary">Fitur Utama</p>
            <h2 class="mt-3 text-h2 font-bold tracking-tight text-heading text-balance sm:text-h1">
              Solusi digitalisasi terintegrasi
            </h2>
            <p class="mt-3 text-body leading-relaxed text-muted">
              Menggantikan surat izin fisik kertas yang rentan hilang dengan sistem QR Pass dinamis
              ber-timer presisi.
            </p>
          </div>

          <div class="mt-10 grid gap-x-10 sm:grid-cols-2">
            <article
              v-for="item in features"
              :key="item.title"
              class="border-t border-border py-5"
            >
              <div class="flex items-center gap-2.5">
                <component :is="item.icon" class="h-4 w-4 shrink-0 text-primary" aria-hidden="true" />
                <h3 class="text-body font-semibold text-heading">{{ item.title }}</h3>
              </div>
              <p class="mt-2 text-body leading-relaxed text-muted">{{ item.description }}</p>
            </article>
          </div>
        </div>
      </section>

      <!-- Alur Kerja -->
      <section id="alur" class="scroll-mt-20 border-b border-border bg-card sm:scroll-mt-24">
        <div class="mx-auto w-full max-w-6xl px-4 py-14 sm:px-6 sm:py-20">
          <div class="max-w-2xl">
            <p class="text-caption font-semibold uppercase tracking-[0.18em] text-primary">Alur Kerja</p>
            <h2 class="mt-3 text-h2 font-bold tracking-tight text-heading text-balance sm:text-h1">
              Dari pengajuan sampai verifikasi gerbang
            </h2>
            <p class="mt-3 text-body leading-relaxed text-muted">
              Setiap antrean punya penanggung jawab yang jelas, sehingga tidak ada izin yang menggantung
              tanpa status.
            </p>
          </div>

          <ol class="mt-10 grid gap-x-10 sm:grid-cols-2 lg:grid-cols-4">
            <li v-for="(step, index) in steps" :key="step.title" class="border-t border-border py-5">
              <p class="font-mono text-body font-semibold text-primary">
                {{ String(index + 1).padStart(2, '0') }}
              </p>
              <h3 class="mt-2 text-body font-semibold text-heading">{{ step.title }}</h3>
              <p class="mt-1.5 text-body leading-relaxed text-muted">{{ step.description }}</p>
            </li>
          </ol>
        </div>
      </section>

      <!-- Portal Peran -->
      <section id="peran" class="scroll-mt-20 border-b border-border sm:scroll-mt-24">
        <div class="mx-auto w-full max-w-6xl px-4 py-14 sm:px-6 sm:py-20">
          <div class="max-w-2xl">
            <p class="text-caption font-semibold uppercase tracking-[0.18em] text-primary">
              Hak Akses Pengguna
            </p>
            <h2 class="mt-3 text-h2 font-bold tracking-tight text-heading text-balance sm:text-h1">
              Empat portal, satu basis data
            </h2>
          </div>

          <dl class="mt-10 grid gap-x-10 sm:grid-cols-2">
            <div v-for="role in roles" :key="role.name" class="border-t border-border py-5">
              <dt class="text-body font-semibold text-heading">{{ role.name }}</dt>
              <dd class="mt-1.5 text-body leading-relaxed text-muted">{{ role.description }}</dd>
            </div>
          </dl>
        </div>
      </section>

      <!-- Dukungan & Penyelenggaraan -->
      <section class="border-b border-border bg-card">
        <div class="mx-auto w-full max-w-6xl px-4 py-14 sm:px-6 sm:py-16">
          <div class="max-w-2xl">
            <p class="text-caption font-semibold uppercase tracking-[0.18em] text-primary">
              Dukungan & Penyelenggaraan
            </p>
            <h2 class="mt-3 text-h2 font-bold tracking-tight text-heading">Didukung & Diselenggarakan Oleh</h2>
          </div>

          <ul class="mt-10 grid grid-cols-2 gap-x-6 gap-y-9 sm:grid-cols-3 lg:grid-cols-5">
            <li
              v-for="supporter in supporters"
              :key="supporter.name"
              class="flex flex-col items-center gap-3 text-center"
            >
              <img
                :src="supporter.src"
                :alt="supporter.name"
                :width="supporter.width"
                :height="supporter.height"
                class="h-10 max-w-full object-contain sm:h-12"
                loading="lazy"
                decoding="async"
              />
              <span class="text-caption text-muted">{{ supporter.name }}</span>
            </li>
          </ul>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer class="mt-auto bg-primary-deep text-white">
      <div
        class="mx-auto flex w-full max-w-6xl flex-col gap-6 px-4 py-10 sm:px-6 md:flex-row md:items-start md:justify-between"
      >
        <div class="max-w-md">
          <p class="text-body font-bold text-white">SMKN 2 Depok Sleman (STEMBAYO)</p>
          <p class="mt-1.5 text-caption text-white/70">
            Jl. Mrican, Caturtunggal, Depok, Sleman, D.I. Yogyakarta 55281
          </p>
        </div>
        <p class="text-caption text-white/70 md:text-right">
          &copy; 2026 Appermit Student Care — Projek JHIC. All rights reserved.
        </p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import BaseButton from '@/components/ui/BaseButton.vue'
import { ArrowRight, QrCode, ShieldCheck, Users, HeartHandshake, LogIn } from 'lucide-vue-next'

const brandUrl = (file) => `/brand/${encodeURI(file)}`

const navLinks = [
  { label: 'Fitur Utama', href: '#fitur' },
  { label: 'Alur Kerja', href: '#alur' },
  { label: 'Portal Peran', href: '#peran' }
]

const features = [
  {
    title: 'E-Permit & QR Pass',
    description:
      'Pengajuan izin daring dari ponsel siswa, disetujui guru pengampu, lalu diverifikasi lewat QR Pass ber-timer di gerbang.',
    icon: QrCode
  },
  {
    title: 'Gate Security Scanner',
    description:
      'Validasi pemindaian di pos satpam untuk mendeteksi siswa yang keluar, pulang, dan masa berlaku izin.',
    icon: ShieldCheck
  },
  {
    title: 'Monitoring Kelas Real-time',
    description:
      'Guru pengampu memantau status presensi dan mobilitas siswa di kelas binaannya dengan perhitungan otomatis.',
    icon: Users
  },
  {
    title: 'Care Report & Kanban BK',
    description:
      'Portal aduan konseling yang dikelola Guru BK melalui papan Kanban beserta catatan tindak lanjut.',
    icon: HeartHandshake
  }
]

const steps = [
  {
    title: 'Siswa mengajukan izin',
    description:
      'Izin keluar sementara atau pulang sekolah diisi dari ponsel lengkap dengan durasi, alasan, dan guru pengampu.'
  },
  {
    title: 'Guru pengampu memproses',
    description:
      'Permintaan masuk antrean persetujuan guru, yang menyetujui dan menetapkan durasi atau menolak dengan catatan.'
  },
  {
    title: 'Petugas gerbang memverifikasi',
    description:
      'Siswa memindai QR Pass di pos satpam, sehingga catatan keluar dan pulang terpunktai otomatis.'
  },
  {
    title: 'Guru BK menangani laporan',
    description:
      'Care report masuk papan Kanban BK untuk ditindaklanjuti, sementara mobilitas siswa dipantau secara global.'
  }
]

const roles = [
  {
    name: 'Portal Siswa',
    description:
      'Mobile-first untuk pengajuan izin, tiket QR Pass, tracking status, dan pengaduan konseling ke Guru BK.'
  },
  {
    name: 'Workstation Guru',
    description:
      'Antrean persetujuan izin siswa, penetapan durasi, dan dashboard pemantauan presensi rombel.'
  },
  {
    name: 'Gate Web Scanner',
    description:
      'Portal pemindai kamera gerbang untuk validasi token QR siswa saat keluar maupun izin pulang.'
  },
  {
    name: 'Console BK & Mobility',
    description:
      'Papan kerja Kanban aduan konseling, catatan tindak lanjut, dan pengawasan mobilitas global siswa.'
  }
]

const supporters = [
  { name: 'JHIC 2.0', src: brandUrl('1. LOGO JHIC 2.0.png'), width: 3802, height: 2310 },
  { name: 'Jagoan Hosting', src: brandUrl('2. Logo Jagoan Hosting.png'), width: 6020, height: 1891 },
  { name: 'KOMDIGI', src: brandUrl('3. KOMDIGI.png'), width: 994, height: 720 },
  { name: 'Garuda Spark', src: brandUrl('4. Garuda Spark Full Color.png'), width: 3447, height: 1881 },
  { name: 'Ngalup', src: brandUrl('5. LOGO NGALUP.png'), width: 1983, height: 314 }
]
</script>
