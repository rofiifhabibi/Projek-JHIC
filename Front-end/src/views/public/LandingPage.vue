<template>
  <div class="min-h-screen bg-[#F4F7F4] text-slate-800 font-sans selection:bg-[#355245] selection:text-white flex flex-col">
    <!-- Top Navbar -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80">
      <div class="max-w-7xl mx-auto px-3.5 sm:px-6 lg:px-8 h-15 sm:h-16 flex items-center justify-between">
        <router-link to="/" class="flex items-center gap-2 min-w-0">
          <img
            src="/logos/studentcare-logo.png"
            alt="StudentCare"
            class="h-7 sm:h-9 object-contain shrink-0 max-w-[160px] sm:max-w-none"
          />
        </router-link>

        <nav class="hidden md:flex items-center gap-8 text-xs font-bold uppercase tracking-wider text-slate-600">
          <a href="#fitur" class="hover:text-[#355245] transition">Alur Layanan</a>
          <a href="#peran" class="hover:text-[#355245] transition">Akses Masuk</a>
        </nav>

        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
          <BaseButton to="/login" variant="primary" size="sm" class="!px-3 sm:!px-4">
            <template #icon-left><LogIn class="w-3.5 h-3.5 sm:w-4 sm:h-4" /></template>
            <span class="inline sm:hidden text-xs">Masuk</span>
            <span class="hidden sm:inline text-xs">Masuk Akun</span>
          </BaseButton>

          <!-- Mobile Hamburger Toggle -->
          <button
            type="button"
            @click="isMobileMenuOpen = !isMobileMenuOpen"
            class="md:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition cursor-pointer touch-manipulation"
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
        class="md:hidden border-t border-slate-100 bg-white px-4 py-3 space-y-2 shadow-lg animate-fadeIn"
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
        <div class="pt-2 border-t border-slate-100">
          <BaseButton to="/login" @click="isMobileMenuOpen = false" variant="primary" size="sm" block>
            Masuk ke Akun
          </BaseButton>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="relative overflow-hidden py-10 sm:py-20 lg:py-24 bg-gradient-to-b from-white via-white to-[#F4F7F4]">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4 sm:space-y-6">
        <div class="inline-flex items-center gap-1.5 sm:gap-2 px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full bg-[#E8EFEA] text-[#273e34] text-[11px] sm:text-xs font-semibold uppercase tracking-wider max-w-full">
          <ShieldCheck class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#355245] shrink-0" />
          <span class="truncate">Sistem Izin Keluar & Konseling Siswa</span>
        </div>

        <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black text-slate-900 leading-[1.25] sm:leading-[1.2] tracking-tight">
          Kelola Izin Siswa dan Layanan Konseling dalam Satu Sistem
        </h2>

        <p class="text-xs sm:text-base text-slate-600 leading-relaxed max-w-2xl mx-auto px-1 sm:px-0">
          Menghubungkan siswa, guru pengajar, satpam gerbang, dan guru BK agar alur perizinan tercatat jelas dan tertib.
        </p>

        <!-- Hero CTAs -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-2.5 sm:gap-4 pt-1 sm:pt-2 max-w-xs sm:max-w-none mx-auto w-full">
          <BaseButton to="/login" variant="primary" size="lg" block class="shadow-sm">
            Masuk ke Akun
            <template #icon-right><ArrowRight class="w-4 h-4" /></template>
          </BaseButton>
          <BaseButton href="#fitur" variant="outline" size="lg" block>
            Lihat Alur Izin
          </BaseButton>
        </div>

        <!-- Trust / Highlight Badges on Mobile & Desktop -->
        <div class="pt-3 sm:pt-6 flex flex-wrap items-center justify-center gap-2 sm:gap-3 text-xs text-slate-500 font-medium">
          <span class="inline-flex items-center gap-1.5 bg-slate-100/90 px-2.5 py-1 rounded-lg">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            Izin QR Resmi
          </span>
          <span class="inline-flex items-center gap-1.5 bg-slate-100/90 px-2.5 py-1 rounded-lg">
            <span class="w-1.5 h-1.5 rounded-full bg-[#355245]"></span>
            Persetujuan Guru
          </span>
          <span class="inline-flex items-center gap-1.5 bg-slate-100/90 px-2.5 py-1 rounded-lg">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
            Verifikasi Gerbang
          </span>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-12 sm:py-20 bg-white border-y border-slate-200/80">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 sm:space-y-12">
        <div class="text-center max-w-2xl mx-auto space-y-2 sm:space-y-3">
          <span class="text-xs font-bold text-[#355245] uppercase tracking-widest">Alur Kerja Sistem</span>
          <h3 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Tertib Perizinan Tanpa Kertas</h3>
          <p class="text-xs sm:text-sm text-slate-600">Menggantikan surat izin kertas manual dengan izin berkode QR dan batas waktu yang jelas.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-6">
          <div class="bg-[#F4F7F4] p-4.5 sm:p-6 rounded-2xl border border-slate-200/80 space-y-2.5 sm:space-y-3 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-[#355245] shadow-xs">
              <QrCode class="w-5 h-5 sm:w-6 sm:h-6" :stroke-width="1.8" />
            </div>
            <h4 class="font-bold text-slate-900 text-sm sm:text-base">Pengajuan Izin Keluar & Pulang</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Pengajuan izin langsung dari ponsel siswa, disetujui guru pengajar saat jam pelajaran, dan diverifikasi di gerbang sekolah.</p>
          </div>

          <div class="bg-[#F4F7F4] p-4.5 sm:p-6 rounded-2xl border border-slate-200/80 space-y-2.5 sm:space-y-3 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-[#355245] shadow-xs">
              <ShieldCheck class="w-5 h-5 sm:w-6 sm:h-6" :stroke-width="1.8" />
            </div>
            <h4 class="font-bold text-slate-900 text-sm sm:text-base">Pemeriksaan di Pos Satpam</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Petugas keamanan memindai kode QR siswa saat keluar dan mencatat waktu kembali siswa ke lingkungan sekolah.</p>
          </div>

          <div class="bg-[#F4F7F4] p-4.5 sm:p-6 rounded-2xl border border-slate-200/80 space-y-2.5 sm:space-y-3 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-[#355245] shadow-xs">
              <Users class="w-5 h-5 sm:w-6 sm:h-6" :stroke-width="1.8" />
            </div>
            <h4 class="font-bold text-slate-900 text-sm sm:text-base">Presensi & Pantauan Jam Mengajar</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Guru dapat melihat siswa yang sedang izin, batas waktu tersisa, dan memastikan ketertiban selama jam belajar.</p>
          </div>

          <div class="bg-[#F4F7F4] p-4.5 sm:p-6 rounded-2xl border border-slate-200/80 space-y-2.5 sm:space-y-3 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-[#355245] shadow-xs">
              <HeartHandshake class="w-5 h-5 sm:w-6 sm:h-6" :stroke-width="1.8" />
            </div>
            <h4 class="font-bold text-slate-900 text-sm sm:text-base">Layanan Konseling & BK</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Ruang aman bagi siswa untuk bercerita atau meminta bantuan konseling yang ditangani secara tertutup oleh Guru BK.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Role Section -->
    <section id="peran" class="py-12 sm:py-20 bg-[#F4F7F4]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 sm:space-y-12">
        <div class="text-center max-w-2xl mx-auto space-y-2 sm:space-y-3">
          <span class="text-xs font-bold text-[#355245] uppercase tracking-widest">Peran Pengguna</span>
          <h3 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Disesuaikan untuk Kebutuhan Sekolah</h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-6">
          <router-link
            to="/login"
            class="bg-white p-4.5 sm:p-6 rounded-2xl border border-slate-200/90 space-y-3 shadow-xs hover:shadow-md hover:border-[#355245]/40 transition group flex flex-col justify-between"
          >
            <div class="space-y-3">
              <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-[#E8EFEA] text-[#355245] flex items-center justify-center font-bold shadow-xs group-hover:scale-105 transition">
                <GraduationCap class="w-5 h-5 sm:w-6 sm:h-6" :stroke-width="1.8" />
              </div>
              <h4 class="font-bold text-slate-900 text-sm sm:text-base group-hover:text-[#355245] transition-colors">Siswa</h4>
              <p class="text-xs text-slate-600 leading-relaxed">Pengajuan izin keluar atau pulang, surat izin resmi ber-QR code, riwayat perizinan, dan layanan bimbingan konseling.</p>
            </div>
            <div class="pt-2 flex items-center text-xs font-bold text-[#355245] group-hover:translate-x-0.5 transition-transform">
              <span>Masuk sebagai Siswa</span>
              <ArrowRight class="w-3.5 h-3.5 ml-1" />
            </div>
          </router-link>

          <router-link
            to="/login"
            class="bg-white p-4.5 sm:p-6 rounded-2xl border border-slate-200/90 space-y-3 shadow-xs hover:shadow-md hover:border-[#355245]/40 transition group flex flex-col justify-between"
          >
            <div class="space-y-3">
              <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-[#E8EFEA] text-[#355245] flex items-center justify-center font-bold shadow-xs group-hover:scale-105 transition">
                <UserCheck class="w-5 h-5 sm:w-6 sm:h-6" :stroke-width="1.8" />
              </div>
              <h4 class="font-bold text-slate-900 text-sm sm:text-base group-hover:text-[#355245] transition-colors">Guru Pengajar</h4>
              <p class="text-xs text-slate-600 leading-relaxed">Persetujuan izin siswa yang diajar, penentuan batas durasi izin, serta pemantauan siswa yang sedang di luar kelas.</p>
            </div>
            <div class="pt-2 flex items-center text-xs font-bold text-[#355245] group-hover:translate-x-0.5 transition-transform">
              <span>Masuk sebagai Guru</span>
              <ArrowRight class="w-3.5 h-3.5 ml-1" />
            </div>
          </router-link>

          <router-link
            to="/login"
            class="bg-white p-4.5 sm:p-6 rounded-2xl border border-slate-200/90 space-y-3 shadow-xs hover:shadow-md hover:border-[#355245]/40 transition group flex flex-col justify-between"
          >
            <div class="space-y-3">
              <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-[#E8EFEA] text-[#355245] flex items-center justify-center font-bold shadow-xs group-hover:scale-105 transition">
                <Shield class="w-5 h-5 sm:w-6 sm:h-6" :stroke-width="1.8" />
              </div>
              <h4 class="font-bold text-slate-900 text-sm sm:text-base group-hover:text-[#355245] transition-colors">Satpam</h4>
              <p class="text-xs text-slate-600 leading-relaxed">Pemeriksaan surat izin siswa di gerbang utama sekolah menggunakan kamera pemindai atau input kode manual.</p>
            </div>
            <div class="pt-2 flex items-center text-xs font-bold text-[#355245] group-hover:translate-x-0.5 transition-transform">
              <span>Masuk sebagai Satpam</span>
              <ArrowRight class="w-3.5 h-3.5 ml-1" />
            </div>
          </router-link>

          <router-link
            to="/login"
            class="bg-white p-4.5 sm:p-6 rounded-2xl border border-slate-200/90 space-y-3 shadow-xs hover:shadow-md hover:border-[#355245]/40 transition group flex flex-col justify-between"
          >
            <div class="space-y-3">
              <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-[#E8EFEA] text-[#355245] flex items-center justify-center font-bold shadow-xs group-hover:scale-105 transition">
                <HeartHandshake class="w-5 h-5 sm:w-6 sm:h-6" :stroke-width="1.8" />
              </div>
              <h4 class="font-bold text-slate-900 text-sm sm:text-base group-hover:text-[#355245] transition-colors">Bimbingan Konseling (BK)</h4>
              <p class="text-xs text-slate-600 leading-relaxed">Pencatatan aduan konseling siswa, jadwal tindak lanjut, serta pantauan keterlambatan izin seluruh sekolah.</p>
            </div>
            <div class="pt-2 flex items-center text-xs font-bold text-[#355245] group-hover:translate-x-0.5 transition-transform">
              <span>Masuk sebagai BK</span>
              <ArrowRight class="w-3.5 h-3.5 ml-1" />
            </div>
          </router-link>
        </div>
      </div>
    </section>

    <!-- Partners & Supporters Section -->
    <section class="py-10 sm:py-16 bg-white border-y border-slate-200/80">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6 sm:space-y-8">
        <div>
          <p class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-[0.2em]">
            Didukung Oleh
          </p>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-2.5 sm:gap-4 md:gap-5">
          <!-- JHIC 2026 -->
          <div class="w-[calc(50%-0.4rem)] sm:w-36 md:w-44 h-13 sm:h-15 md:h-16 flex items-center justify-center px-3 sm:px-4 py-2 rounded-2xl bg-slate-50/90 border border-slate-200/90 shadow-2xs hover:bg-white hover:border-[#355245]/30 hover:shadow-xs transition">
            <img src="/logos/jhic.png" alt="JHIC 2026" class="max-h-7 sm:max-h-8 max-w-[110px] sm:max-w-[120px] object-contain" />
          </div>

          <!-- Jagoan Hosting -->
          <div class="w-[calc(50%-0.4rem)] sm:w-36 md:w-44 h-13 sm:h-15 md:h-16 flex items-center justify-center px-3 sm:px-4 py-2 rounded-2xl bg-slate-50/90 border border-slate-200/90 shadow-2xs hover:bg-white hover:border-[#355245]/30 hover:shadow-xs transition">
            <img src="/logos/jagoan-hosting.png" alt="Jagoan Hosting" class="max-h-5 sm:max-h-6.5 max-w-[115px] sm:max-w-[125px] object-contain" />
          </div>

          <!-- Komdigi -->
          <div class="w-[calc(50%-0.4rem)] sm:w-36 md:w-44 h-13 sm:h-15 md:h-16 flex items-center justify-center px-3 sm:px-4 py-2 rounded-2xl bg-slate-50/90 border border-slate-200/90 shadow-2xs hover:bg-white hover:border-[#355245]/30 hover:shadow-xs transition">
            <img src="/logos/komdigi.png" alt="Komdigi" class="max-h-6.5 sm:max-h-7.5 max-w-[105px] sm:max-w-[115px] object-contain" />
          </div>

          <!-- Garuda Spark -->
          <div class="w-[calc(50%-0.4rem)] sm:w-36 md:w-44 h-13 sm:h-15 md:h-16 flex items-center justify-center px-3 sm:px-4 py-2 rounded-2xl bg-slate-50/90 border border-slate-200/90 shadow-2xs hover:bg-white hover:border-[#355245]/30 hover:shadow-xs transition">
            <img src="/logos/garuda-spark.png" alt="Garuda Spark" class="max-h-5 sm:max-h-6 max-w-[110px] sm:max-w-[120px] object-contain" />
          </div>

          <!-- Ngalup.co -->
          <div class="w-[calc(50%-0.4rem)] sm:w-36 md:w-44 h-13 sm:h-15 md:h-16 flex items-center justify-center px-3 sm:px-4 py-2 rounded-2xl bg-slate-50/90 border border-slate-200/90 shadow-2xs hover:bg-white hover:border-[#355245]/30 hover:shadow-xs transition">
            <img src="/logos/ngalup.png" alt="Ngalup.co" class="max-h-4.5 sm:max-h-5.5 max-w-[110px] sm:max-w-[120px] object-contain" />
          </div>
        </div>

        <p class="text-[11px] sm:text-xs text-slate-500 font-medium pt-1">
          Jagoan Hosting Innovation Competition 2026
        </p>
      </div>
    </section>

    <!-- Footer -->
    <footer class="mt-auto bg-slate-900 text-slate-400 py-8 sm:py-10 border-t border-slate-800 text-xs">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-5 text-center sm:text-left">
          <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4">
            <img src="/logos/studentcare-logo-white.png" alt="StudentCare" class="h-8 sm:h-9 object-contain" />
            <div class="hidden sm:block w-px h-8 bg-slate-700"></div>
            <div>
              <p class="font-bold text-white text-sm">SMKN 2 Depok Sleman</p>
              <p class="mt-0.5 text-slate-400 text-xs max-w-sm sm:max-w-none mx-auto sm:mx-0">Jl. Mrican, Caturtunggal, Depok, Sleman, D.I. Yogyakarta 55281</p>
            </div>
          </div>
          <div class="text-center sm:text-right text-slate-500 text-xs">
            <p>&copy; 2026 StudentCare • SMKN 2 Depok Sleman</p>
          </div>
        </div>

        <!-- Footer Partner Logos -->
        <div class="border-t border-slate-800 pt-5 flex flex-wrap items-center justify-center gap-4 sm:gap-8">
          <img src="/logos/jhic-white.png" alt="JHIC" class="h-5 sm:h-6 max-w-[90px] object-contain opacity-40 hover:opacity-90 transition" />
          <img src="/logos/jagoan-hosting-white.png" alt="Jagoan Hosting" class="h-4 sm:h-5 max-w-[95px] object-contain opacity-40 hover:opacity-90 transition" />
          <img src="/logos/komdigi-white.png" alt="Komdigi" class="h-5 sm:h-6 max-w-[85px] object-contain opacity-40 hover:opacity-90 transition" />
          <img src="/logos/garuda-spark-white.png" alt="Garuda Spark" class="h-4 sm:h-5 max-w-[95px] object-contain opacity-40 hover:opacity-90 transition" />
          <img src="/logos/ngalup-white.png" alt="Ngalup" class="h-3.5 sm:h-4.5 max-w-[95px] object-contain opacity-40 hover:opacity-90 transition" />
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import { ArrowRight, QrCode, ShieldCheck, Users, HeartHandshake, LogIn, Menu, X, GraduationCap, UserCheck, Shield } from 'lucide-vue-next'

const isMobileMenuOpen = ref(false)
</script>
