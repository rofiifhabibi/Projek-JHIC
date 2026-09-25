<template>
  <div class="min-h-screen bg-[#F4F7F4] text-slate-800 font-sans selection:bg-[#355245] selection:text-white flex flex-col">
    <!-- Top Navbar -->
    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-200/80">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-[#355245] flex items-center justify-center text-white shadow-md shadow-[#355245]/20 font-extrabold text-lg">
            SC
          </div>
          <div>
            <h1 class="font-extrabold text-slate-900 text-base leading-tight tracking-tight">StudentCare</h1>
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">SMKN 2 Depok Sleman</p>
          </div>
        </div>

        <nav class="hidden md:flex items-center gap-8 text-xs font-bold uppercase tracking-wider text-slate-600">
          <a href="#fitur" class="hover:text-[#355245] transition">Fitur Utama</a>
          <a href="#alur" class="hover:text-[#355245] transition">Alur Kerja</a>
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
          Sistem Digitalisasi Sekolah Terpadu
        </div>

        <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 leading-[1.15] tracking-tight">
          Manajemen Perizinan Siswa & Layanan BK Berstandar Modern
        </h2>

        <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
          Integrasi cerdas 4 entitas sekolah: <strong class="text-slate-900">Siswa, Guru Pengampu, Petugas Gerbang, dan Guru BK</strong>. Memastikan ketertiban, keamanan mobilitas, dan perlindungan siswa secara real-time.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
          <router-link to="/login" class="w-full sm:w-auto">
            <BaseButton variant="primary" size="lg" block>
              Buka Portal Login
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
          <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">Solusi Digitalisasi Terintegrasi</h3>
          <p class="text-sm text-slate-600">Menggantikan surat izin fisik Kertas yang rentan hilang dengan sistem QR Pass dinamis ber-timer presisi.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="bg-[#F4F7F4] p-6 rounded-2xl border border-slate-200/80 space-y-3 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-[#355245] text-white flex items-center justify-center">
              <QrCode class="w-6 h-6" />
            </div>
            <h4 class="font-bold text-slate-900 text-base">E-Permit & QR Pass</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Pengajuan izin online dari HP siswa dengan persetujuan guru & verifikasi QR code di gerbang satpam.</p>
          </div>

          <div class="bg-[#F4F7F4] p-6 rounded-2xl border border-slate-200/80 space-y-3 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-emerald-700 text-white flex items-center justify-center">
              <ShieldCheck class="w-6 h-6" />
            </div>
            <h4 class="font-bold text-slate-900 text-base">Gate Security Scanner</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Validasi pemindaian kamera fisik di pos satpam gerbang utama untuk mendeteksi siswa yang keluar/pulang.</p>
          </div>

          <div class="bg-[#F4F7F4] p-6 rounded-2xl border border-slate-200/80 space-y-3 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-slate-800 text-white flex items-center justify-center">
              <Users class="w-6 h-6" />
            </div>
            <h4 class="font-bold text-slate-900 text-base">Monitoring Kelas Real-time</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Guru pengampu memantau status presensi & mobilitas siswa di kelas binaan dengan kalkulasi otomatis.</p>
          </div>

          <div class="bg-[#F4F7F4] p-6 rounded-2xl border border-slate-200/80 space-y-3 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-teal-700 text-white flex items-center justify-center">
              <HeartHandshake class="w-6 h-6" />
            </div>
            <h4 class="font-bold text-slate-900 text-base">Care Report & Kanban BK</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Portal aduan konseling rahasia terisolasi yang dikelola Guru BK via papan Kanban interaktif.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Role Portals Section -->
    <section id="peran" class="py-20 bg-[#F4F7F4]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center max-w-2xl mx-auto space-y-3">
          <span class="text-xs font-bold text-[#355245] uppercase tracking-widest">Hak Akses Pengguna</span>
          <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">4 Role Terintegrasi</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-3 shadow-sm">
            <span class="px-2.5 py-1 rounded-full bg-[#E8EFEA] text-[#355245] text-[11px] font-bold uppercase">Siswa</span>
            <h4 class="font-bold text-slate-900 text-base">Portal Siswa</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Mobile-first app untuk pengajuan izin, tiket QR Pass, tracking status, dan pengaduan konseling BK.</p>
          </div>

          <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-3 shadow-sm">
            <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-[11px] font-bold uppercase">Guru</span>
            <h4 class="font-bold text-slate-900 text-base">Workstation Guru</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Antrean persetujuan izin siswa, penetapan durasi, dan dashboard pemantauan presensi rombel.</p>
          </div>

          <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-3 shadow-sm">
            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-[11px] font-bold uppercase">Satpam</span>
            <h4 class="font-bold text-slate-900 text-base">Gate Web Scanner</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Portal scanner kamera gerbang untuk validasi token QR siswa saat keluar atau izin pulang.</p>
          </div>

          <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-3 shadow-sm">
            <span class="px-2.5 py-1 rounded-full bg-teal-50 text-teal-800 border border-teal-200/60 text-[11px] font-bold uppercase">Guru BK</span>
            <h4 class="font-bold text-slate-900 text-base">Console BK & Mobility</h4>
            <p class="text-xs text-slate-600 leading-relaxed">Papan kerja Kanban aduan konseling, rekam investigasi, dan pengawasan mobilitas global siswa.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="mt-auto bg-slate-900 text-slate-400 py-10 border-t border-slate-800 text-xs">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
          <p class="font-bold text-white text-sm">SMKN 2 Depok Sleman (STEMBAYO)</p>
          <p class="mt-1 text-slate-500">Jl. Mrican, Caturtunggal, Depok, Sleman, D.I. Yogyakarta 55281</p>
        </div>
        <div class="text-center sm:text-right text-slate-500">
          <p>&copy; 2026 StudentCare — Projek JHIC. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import BaseButton from '@/components/ui/BaseButton.vue'
import { ArrowRight, QrCode, ShieldCheck, Users, HeartHandshake, LogIn } from 'lucide-vue-next'
</script>
