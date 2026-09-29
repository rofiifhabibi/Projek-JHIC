<template>
  <div class="min-h-screen bg-[#F4F7F4] text-slate-800 font-sans selection:bg-[#355245] selection:text-white flex flex-col">
    <!-- Top Navbar -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200/80">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <router-link to="/" class="flex items-center gap-3">
          <img src="/logos/studentcare-logo.png" alt="Student Care SMKN 2 Depok Sleman" class="h-8 sm:h-9 object-contain" />
        </router-link>

        <nav class="hidden md:flex items-center gap-8 text-xs font-bold uppercase tracking-wider text-slate-600">
          <a href="#fitur" class="hover:text-[#355245] transition">Alur Layanan</a>
          <a href="#peran" class="hover:text-[#355245] transition">Akses Masuk</a>
        </nav>

        <div class="flex items-center gap-2.5 sm:gap-3">
          <router-link to="/login">
            <BaseButton variant="primary" size="sm">
              <template #icon-left><LogIn class="w-4 h-4" /></template>
              Masuk Akun
            </BaseButton>
          </router-link>

          <!-- Mobile Hamburger Toggle -->
          <button
            @click="isMobileMenuOpen = !isMobileMenuOpen"
            class="md:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition"
            aria-label="Buka menu navigasi"
          >
            <Menu v-if="!isMobileMenuOpen" class="w-5 h-5" />
            <X v-else class="w-5 h-5" />
          </button>
        </div>
      </div>

      <!-- Mobile Dropdown Menu -->
      <div
        v-if="isMobileMenuOpen"
        class="md:hidden border-t border-slate-100 bg-white px-4 py-3 space-y-2 shadow-md animate-fadeIn"
      >
        <a
          href="#fitur"
          @click="isMobileMenuOpen = false"
          class="block py-2 text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-[#355245]"
        >
          Alur Layanan
        </a>
        <a
          href="#peran"
          @click="isMobileMenuOpen = false"
          class="block py-2 text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-[#355245]"
        >
          Akses Masuk
        </a>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="relative overflow-hidden py-14 sm:py-20 lg:py-24 bg-gradient-to-b from-white via-white to-[#F4F7F4]">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-5 sm:space-y-6">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#E8EFEA] text-[#273e34] text-xs font-semibold uppercase tracking-wider">
          <ShieldCheck class="w-4 h-4 text-[#355245]" />
          Sistem Izin Keluar & Konseling Siswa
        </div>

        <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black text-slate-900 leading-[1.2] tracking-tight">
          Kelola Izin Siswa dan Layanan Konseling dalam Satu Sistem
        </h2>

        <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl mx-auto">
          Menghubungkan siswa, guru pengajar, satpam gerbang, dan guru BK agar alur perizinan tercatat jelas dan tertib.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 pt-2">
          <router-link to="/login" class="w-full sm:w-auto">
            <BaseButton variant="primary" size="lg" block>
              Masuk ke Portal
              <template #icon-right><ArrowRight class="w-4 h-4" /></template>
            </BaseButton>
          </router-link>
          <a href="#fitur" class="w-full sm:w-auto">
            <BaseButton variant="outline" size="lg" block>
              Lihat Alur Izin
            </BaseButton>
          </a>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-20 bg-white border-y border-slate-200/80">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center max-w-2xl mx-auto space-y-3">
          <span class="text-xs font-bold text-[#355245] uppercase tracking-widest">Alur Kerja Sistem</span>
          <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">Tertib Perizinan Tanpa Kertas</h3>
          <p class="text-sm text-slate-600">Menggantikan surat izin kertas manual dengan izin berkode QR dan batas waktu yang jelas.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="bg-[#F4F7F4] p-6 rounded-2xl border border-slate-200/80 space-y-3 hover:shadow-md transition">
            <QrCode class="w-7 h-7 text-[#355245]" :stroke-width="1.8" />
            <h4 class="font-bold text-slate-900 text-base">Pengajuan Izin Keluar & Pulang</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Pengajuan izin langsung dari ponsel siswa, disetujui guru pengajar saat jam pelajaran, dan diverifikasi di gerbang sekolah.</p>
          </div>

          <div class="bg-[#F4F7F4] p-6 rounded-2xl border border-slate-200/80 space-y-3 hover:shadow-md transition">
            <ShieldCheck class="w-7 h-7 text-[#355245]" :stroke-width="1.8" />
            <h4 class="font-bold text-slate-900 text-base">Pemeriksaan di Pos Satpam</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Petugas keamanan memindai kode QR siswa saat keluar dan mencatat waktu kembali siswa ke lingkungan sekolah.</p>
          </div>

          <div class="bg-[#F4F7F4] p-6 rounded-2xl border border-slate-200/80 space-y-3 hover:shadow-md transition">
            <Users class="w-7 h-7 text-[#355245]" :stroke-width="1.8" />
            <h4 class="font-bold text-slate-900 text-base">Presensi & Pantauan Jam Mengajar</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Guru dapat melihat siswa yang sedang izin, batas waktu tersisa, dan memastikan ketertiban selama jam belajar.</p>
          </div>

          <div class="bg-[#F4F7F4] p-6 rounded-2xl border border-slate-200/80 space-y-3 hover:shadow-md transition">
            <HeartHandshake class="w-7 h-7 text-[#355245]" :stroke-width="1.8" />
            <h4 class="font-bold text-slate-900 text-base">Layanan Konseling & BK</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Ruang aman bagi siswa untuk bercerita atau meminta bantuan konseling yang ditangani secara tertutup oleh Guru BK.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Role Portals Section -->
    <section id="peran" class="py-20 bg-[#F4F7F4]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center max-w-2xl mx-auto space-y-3">
          <span class="text-xs font-bold text-[#355245] uppercase tracking-widest">Peran Pengguna</span>
          <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">Disesuaikan untuk Kebutuhan Sekolah</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-3 shadow-xs">
            <div class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#355245]">
              <span class="w-1.5 h-1.5 rounded-full bg-[#355245]"></span>
              <span>Siswa</span>
            </div>
            <h4 class="font-bold text-slate-900 text-base">Portal Siswa</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Pengajuan izin keluar/pulang, tampilan surat izin digital, riwayat perizinan, dan layanan bimbingan konseling.</p>
          </div>

          <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-3 shadow-xs">
            <div class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-amber-700">
              <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
              <span>Guru</span>
            </div>
            <h4 class="font-bold text-slate-900 text-base">Portal Guru</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Persetujuan izin siswa yang diajar, penetapan durasi izin, dan pemantauan siswa yang sedang berada di luar kelas.</p>
          </div>

          <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-3 shadow-xs">
            <div class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-slate-600">
              <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
              <span>Satpam</span>
            </div>
            <h4 class="font-bold text-slate-900 text-base">Pos Satpam</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Pemeriksaan surat izin siswa di gerbang utama sekolah menggunakan kamera pemindai atau input kode manual.</p>
          </div>

          <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-3 shadow-xs">
            <div class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-teal-700">
              <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
              <span>Guru BK</span>
            </div>
            <h4 class="font-bold text-slate-900 text-base">Portal BK</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Pencatatan aduan konseling siswa, jadwal tindak lanjut, serta pantauan keterlambatan izin seluruh sekolah.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Partners & Supporters Section -->
    <section class="py-14 sm:py-16 bg-white border-y border-slate-200/80">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-[0.2em] mb-8 sm:mb-10">
          Didukung Oleh
        </p>
        <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-10 lg:gap-12 my-2">
          <div class="h-10 sm:h-12 flex items-center justify-center max-w-[140px]">
            <img src="/logos/jhic.png" alt="JHIC - Jagoan Hosting Innovation Competition 2026" class="max-h-full object-contain" />
          </div>
          <div class="h-8 sm:h-10 flex items-center justify-center max-w-[130px]">
            <img src="/logos/jagoan-hosting.png" alt="Jagoan Hosting" class="max-h-full object-contain" />
          </div>
          <div class="h-10 sm:h-12 flex items-center justify-center max-w-[140px]">
            <img src="/logos/komdigi.png" alt="Komdigi - Kementerian Komunikasi dan Digital" class="max-h-full object-contain" />
          </div>
          <div class="h-8 sm:h-10 flex items-center justify-center max-w-[130px]">
            <img src="/logos/garuda-spark.png" alt="Garuda Spark Innovation Hub" class="max-h-full object-contain" />
          </div>
          <div class="h-7 sm:h-8 flex items-center justify-center max-w-[120px]">
            <img src="/logos/ngalup.png" alt="Ngalup.co" class="max-h-full object-contain" />
          </div>
        </div>
        <p class="text-xs text-slate-500 font-medium mt-8 sm:mt-10">
          Jagoan Hosting Innovation Competition 2026
        </p>
      </div>
    </section>

    <!-- Footer -->
    <footer class="mt-auto bg-slate-900 text-slate-400 py-10 border-t border-slate-800 text-xs">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6 text-center sm:text-left">
          <div class="flex flex-col sm:flex-row items-center gap-4">
            <img src="/logos/studentcare-logo-white.png" alt="StudentCare" class="h-9 object-contain" />
            <div class="hidden sm:block w-px h-8 bg-slate-700"></div>
            <div>
              <p class="font-bold text-white text-sm">SMKN 2 Depok Sleman</p>
              <p class="mt-0.5 text-slate-400 max-w-sm sm:max-w-none mx-auto sm:mx-0">Jl. Mrican, Caturtunggal, Depok, Sleman, D.I. Yogyakarta 55281</p>
            </div>
          </div>
          <div class="text-center sm:text-right text-slate-500">
            <p>&copy; 2026 StudentCare • SMKN 2 Depok Sleman</p>
          </div>
        </div>

        <!-- Footer Partner Logos -->
        <div class="border-t border-slate-700/50 mt-6 pt-6 flex flex-wrap items-center justify-center gap-6 sm:gap-8">
          <img src="/logos/jhic-white.png" alt="JHIC" class="h-5 sm:h-6 object-contain opacity-50 hover:opacity-90 transition" />
          <img src="/logos/jagoan-hosting-white.png" alt="Jagoan Hosting" class="h-4 sm:h-5 object-contain opacity-50 hover:opacity-90 transition" />
          <img src="/logos/komdigi-white.png" alt="Komdigi" class="h-5 sm:h-6 object-contain opacity-50 hover:opacity-90 transition" />
          <img src="/logos/garuda-spark-white.png" alt="Garuda Spark" class="h-4 sm:h-5 object-contain opacity-50 hover:opacity-90 transition" />
          <img src="/logos/ngalup-white.png" alt="Ngalup" class="h-3.5 sm:h-4 object-contain opacity-50 hover:opacity-90 transition" />
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AppLogo from '@/components/ui/AppLogo.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import { ArrowRight, QrCode, ShieldCheck, Users, HeartHandshake, LogIn, Menu, X } from 'lucide-vue-next'

const isMobileMenuOpen = ref(false)
</script>
