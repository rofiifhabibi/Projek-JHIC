<template>
  <div class="min-h-screen bg-[#F4F7F4] text-slate-800 font-sans selection:bg-[#355245] selection:text-white flex flex-col">
    <!-- Top Navbar -->
    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-200/80">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <AppLogo size="md" />
          <div>
            <h1 class="font-extrabold text-slate-900 text-base leading-tight tracking-tight">StudentCare</h1>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">SMK N 2 Depok Sleman</p>
          </div>
        </div>

        <nav class="hidden md:flex items-center gap-8 text-xs font-bold uppercase tracking-wider text-slate-600">
          <a href="#fitur" class="hover:text-[#355245] transition">Fitur Utama</a>
          <a href="#peran" class="hover:text-[#355245] transition">Portal Peran</a>
        </nav>

        <div class="flex items-center gap-3">
          <router-link to="/login">
            <BaseButton variant="primary" size="sm">
              <template #icon-left><LogIn class="w-4 h-4" /></template>
              Masuk Portal
            </BaseButton>
          </router-link>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="relative overflow-hidden py-16 sm:py-24 lg:py-28 bg-gradient-to-b from-white via-white to-[#F4F7F4]">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#E8EFEA] text-[#273e34] text-xs font-semibold uppercase tracking-wider">
          <ShieldCheck class="w-4 h-4 text-[#355245]" />
          Layanan Perizinan & Konseling Sekolah
        </div>

        <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 leading-[1.15] tracking-tight">
          Sistem Perizinan Siswa & Layanan Konseling Digital
        </h2>

        <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
          Menghubungkan siswa, bapak/ibu guru pengajar, petugas keamanan gerbang, dan tim bimbingan konseling dalam satu sistem yang tertib, transparan, dan nyaman.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
          <router-link to="/login" class="w-full sm:w-auto">
            <BaseButton variant="primary" size="lg" block>
              Masuk ke Portal
              <template #icon-right><ArrowRight class="w-4 h-4" /></template>
            </BaseButton>
          </router-link>
          <a href="#fitur" class="w-full sm:w-auto">
            <BaseButton variant="outline" size="lg" block>
              Pelajari Selengkapnya
            </BaseButton>
          </a>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-20 bg-white border-y border-slate-200/80">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center max-w-2xl mx-auto space-y-3">
          <span class="text-xs font-bold text-[#355245] uppercase tracking-widest">Keunggulan Sistem</span>
          <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">Solusi Digitalisasi Terpadu</h3>
          <p class="text-sm text-slate-600">Menggantikan kertas izin konvensional dengan surat izin digital ber-QR code dan batas waktu yang jelas.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="bg-[#F4F7F4] p-6 rounded-2xl border border-slate-200/80 space-y-3 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-[#355245] text-white flex items-center justify-center">
              <QrCode class="w-6 h-6" />
            </div>
            <h4 class="font-bold text-slate-900 text-base">Izin Keluar & Pulang Digital</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Pengajuan izin langsung dari ponsel siswa, disetujui guru pengajar saat jam pelajaran, dan diverifikasi di gerbang sekolah.</p>
          </div>

          <div class="bg-[#F4F7F4] p-6 rounded-2xl border border-slate-200/80 space-y-3 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-emerald-700 text-white flex items-center justify-center">
              <ShieldCheck class="w-6 h-6" />
            </div>
            <h4 class="font-bold text-slate-900 text-base">Pemeriksaan di Pos Satpam</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Petugas keamanan memindai kode QR siswa saat keluar dan mencatat waktu kembali siswa ke lingkungan sekolah.</p>
          </div>

          <div class="bg-[#F4F7F4] p-6 rounded-2xl border border-slate-200/80 space-y-3 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-slate-800 text-white flex items-center justify-center">
              <Users class="w-6 h-6" />
            </div>
            <h4 class="font-bold text-slate-900 text-base">Pemantauan Kelas & Presensi</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Guru dapat melihat siswa yang sedang izin, batas waktu tersisa, dan memastikan ketertiban selama jam belajar.</p>
          </div>

          <div class="bg-[#F4F7F4] p-6 rounded-2xl border border-slate-200/80 space-y-3 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-teal-700 text-white flex items-center justify-center">
              <HeartHandshake class="w-6 h-6" />
            </div>
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
          <span class="text-xs font-bold text-[#355245] uppercase tracking-widest">Hak Akses Pengguna</span>
          <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">Terhubung untuk 4 Peran</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-3 shadow-xs">
            <span class="px-2.5 py-1 rounded-full bg-[#E8EFEA] text-[#355245] text-xs font-bold uppercase">Siswa</span>
            <h4 class="font-bold text-slate-900 text-base">Portal Siswa</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Pengajuan izin keluar/pulang, tampilan surat izin digital, riwayat perizinan, dan layanan bimbingan konseling.</p>
          </div>

          <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-3 shadow-xs">
            <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold uppercase">Guru</span>
            <h4 class="font-bold text-slate-900 text-base">Portal Guru</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Persetujuan izin siswa yang diajar, penetapan durasi izin, dan pemantauan siswa yang sedang berada di luar kelas.</p>
          </div>

          <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-3 shadow-xs">
            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold uppercase">Satpam</span>
            <h4 class="font-bold text-slate-900 text-base">Pos Satpam</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Pemeriksaan surat izin siswa di gerbang utama sekolah menggunakan kamera pemindai atau input kode manual.</p>
          </div>

          <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-3 shadow-xs">
            <span class="px-2.5 py-1 rounded-full bg-teal-50 text-teal-800 border border-teal-200/60 text-xs font-bold uppercase">Guru BK</span>
            <h4 class="font-bold text-slate-900 text-base">Portal BK</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Papan penanganan konseling siswa, pencatatan hasil tindak lanjut, serta pemantauan keterlambatan izin seluruh sekolah.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Partners & Supporters Section -->
    <section class="py-8 sm:py-10 bg-white border-y border-slate-200/80">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
        <span class="text-xs font-bold text-slate-500 uppercase tracking-[0.2em]">
          Kontribusi & Dukungan Program
        </span>
        <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-8 lg:gap-12">
          <img src="/logos/jhic.png" alt="JHIC - Jagoan Hosting Innovation Competition 2026" class="h-12 sm:h-14 object-contain" />
          <img src="/logos/jagoan-hosting.png" alt="Jagoan Hosting" class="h-8 sm:h-9 object-contain" />
          <img src="/logos/komdigi.png" alt="Komdigi - Kementerian Komunikasi dan Digital" class="h-12 sm:h-14 object-contain" />
          <img src="/logos/garuda-spark.png" alt="Garuda Spark Innovation Hub" class="h-9 sm:h-10 object-contain" />
          <img src="/logos/ngalup.png" alt="Ngalup.co" class="h-7 sm:h-8 object-contain" />
        </div>
        <p class="text-xs text-slate-600 font-medium">
          Jagoan Hosting Innovation Competition 2026
        </p>
      </div>
    </section>

    <!-- Footer -->
    <footer class="mt-auto bg-slate-900 text-slate-400 py-10 border-t border-slate-800 text-xs">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
          <div>
            <p class="font-bold text-white text-sm">SMK N 2 Depok Sleman</p>
            <p class="mt-1 text-slate-500 max-w-sm sm:max-w-none mx-auto sm:mx-0">Jl. Mrican, Caturtunggal, Depok, Sleman, D.I. Yogyakarta 55281</p>
          </div>
          <div class="text-center sm:text-right text-slate-500">
            <p>&copy; 2026 StudentCare — Projek JHIC. All rights reserved.</p>
          </div>
        </div>

        <!-- Footer Partner Logos -->
        <div class="border-t border-slate-700/50 mt-6 pt-6 flex flex-wrap items-center justify-center gap-6 sm:gap-8">
          <img src="/logos/jhic.png" alt="JHIC" class="h-5 sm:h-6 object-contain brightness-0 invert opacity-40 hover:opacity-70 transition" />
          <img src="/logos/jagoan-hosting.png" alt="Jagoan Hosting" class="h-4 sm:h-5 object-contain brightness-0 invert opacity-40 hover:opacity-70 transition" />
          <img src="/logos/komdigi.png" alt="Komdigi" class="h-5 sm:h-6 object-contain brightness-0 invert opacity-40 hover:opacity-70 transition" />
          <img src="/logos/garuda-spark.png" alt="Garuda Spark" class="h-4 sm:h-5 object-contain brightness-0 invert opacity-40 hover:opacity-70 transition" />
          <img src="/logos/ngalup.png" alt="Ngalup" class="h-3.5 sm:h-4 object-contain brightness-0 invert opacity-40 hover:opacity-70 transition" />
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import AppLogo from '@/components/ui/AppLogo.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import { ArrowRight, QrCode, ShieldCheck, Users, HeartHandshake, LogIn } from 'lucide-vue-next'
</script>
