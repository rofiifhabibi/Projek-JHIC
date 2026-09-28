<template>
  <div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-[#355245] to-[#273e34] rounded-3xl p-6 text-white shadow-xl shadow-[#355245]/10 relative overflow-hidden">
      <div class="relative z-10 space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-emerald-200 text-xs font-semibold backdrop-blur-md">
          <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
          {{ greetingText }}
        </div>
        <h2 class="text-2xl sm:text-3xl font-black tracking-tight">
          Selamat datang, {{ authStore.userName }}!
        </h2>
        <p class="text-xs sm:text-sm text-slate-200">
          Kelas: <span class="font-bold text-white">{{ authStore.userClass || 'XII RPL 1' }}</span> • Status Presensi Real-time Portal
        </p>
      </div>
    </div>

    <!-- Active E-Permit Ticket Card -->
    <div
      v-if="permitStore.activePermit"
      class="bg-white rounded-2xl border shadow-sm overflow-hidden transition-all"
      :class="permitStore.activePermit.status === 'OVERDUE' ? 'border-rose-200 ring-1 ring-rose-200' : 'border-slate-200'"
    >
      <!-- Ticket Header Strip -->
      <div
        class="px-4 sm:px-5 py-3 border-b flex items-center justify-between"
        :class="permitStore.activePermit.status === 'OVERDUE' ? 'bg-rose-50/70 border-rose-100' : 'bg-slate-50/80 border-slate-100'"
      >
        <div class="flex items-center gap-2.5">
          <div
            class="w-7 h-7 rounded-lg flex items-center justify-center font-bold"
            :class="permitStore.activePermit.status === 'OVERDUE' ? 'bg-rose-100 text-rose-700' : 'bg-[#E8EFEA] text-[#355245]'"
          >
            <QrCode class="w-4 h-4" />
          </div>
          <div>
            <h3 class="text-xs font-bold text-slate-900 leading-none">Izin Keluar Aktif</h3>
            <span class="text-[10px] text-slate-400 font-medium">Presensi Digital Siswa</span>
          </div>
        </div>
        <BaseBadge :status="permitStore.activePermit.status" />
      </div>

      <!-- Ticket Body -->
      <div class="p-4 sm:p-5 space-y-3.5">
        <!-- Key Info Grid -->
        <div class="grid grid-cols-2 gap-2.5 text-xs">
          <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Jenis Izin</span>
            <span class="font-bold text-slate-800 mt-0.5 block truncate">
              {{ permitStore.activePermit.type === 'TEMP' ? 'Keluar Sementara' : 'Izin Pulang' }}
            </span>
          </div>
          <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Durasi Alokasi</span>
            <span class="font-bold text-slate-800 mt-0.5 block truncate">
              {{ permitStore.activePermit.duration_minutes || 30 }} Menit
            </span>
          </div>
        </div>

        <!-- Detail Lines -->
        <div class="space-y-1.5 text-xs border-t border-slate-100 pt-2.5">
          <div class="flex items-start justify-between gap-3">
            <span class="text-slate-400 text-[11px] shrink-0">Alasan:</span>
            <span class="font-semibold text-slate-800 text-right capitalize">
              {{ permitStore.activePermit.reason || '-' }}
            </span>
          </div>
          <div class="flex items-center justify-between gap-3">
            <span class="text-slate-400 text-[11px] shrink-0">Guru Pengampu:</span>
            <span class="font-semibold text-slate-800 text-right">
              {{ permitStore.activePermit.teacher?.name || '-' }}
            </span>
          </div>
        </div>

        <!-- Overdue Notice Banner if Late -->
        <div
          v-if="permitStore.activePermit.status === 'OVERDUE'"
          class="p-3 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-2.5 text-xs text-rose-900"
        >
          <AlertTriangle class="w-4 h-4 text-rose-600 shrink-0 mt-0.5 animate-pulse" />
          <div class="leading-relaxed">
            <strong class="font-bold text-rose-950">Waktu Izin Berakhir!</strong>
            <p class="text-[11px] text-rose-700 mt-0.5">Anda tercatat terlambat oleh sistem. Harap segera kembali ke ruang kelas dan laporkan ke Guru Pengampu.</p>
          </div>
        </div>

        <!-- Action Button -->
        <router-link to="/student/permit/pass" class="block pt-0.5">
          <BaseButton variant="primary" size="md" block>
            <template #icon-left><QrCode class="w-4 h-4" /></template>
            Buka Tiket QR Pass Digital
          </BaseButton>
        </router-link>
      </div>
    </div>

    <!-- Normal Presence Banner -->
    <div v-else class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
      <div class="space-y-1">
        <div class="flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
          <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Status Presensi Belajar</span>
        </div>
        <h3 class="text-base sm:text-lg font-bold text-slate-900">Mengikuti Pembelajaran di Kelas</h3>
        <p class="text-xs text-slate-500">Anda tidak memiliki izin keluar aktif saat ini. Tetap fokus mengikuti KBM.</p>
      </div>
      <router-link to="/student/permit/create" class="shrink-0">
        <BaseButton variant="secondary" size="sm">
          Buat Izin
        </BaseButton>
      </router-link>
    </div>

    <!-- Quick Action Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <router-link to="/student/permit/create" class="group">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:border-[#355245] transition space-y-3">
          <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center group-hover:scale-105 transition">
            <FilePlus class="w-5 h-5" />
          </div>
          <div>
            <h4 class="font-bold text-sm text-slate-900 group-hover:text-[#355245]">Pengajuan Izin Mandiri</h4>
            <p class="text-xs text-slate-500 mt-0.5">Izin keluar kelas sementara ke UKS/TU atau izin pulang sekolah.</p>
          </div>
        </div>
      </router-link>

      <router-link to="/student/report/create" class="group">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:border-[#355245] transition space-y-3">
          <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center group-hover:scale-105 transition">
            <ShieldAlert class="w-5 h-5" />
          </div>
          <div>
            <h4 class="font-bold text-sm text-slate-900 group-hover:text-[#355245]">Care BK — Pengaduan Konseling</h4>
            <p class="text-xs text-slate-500 mt-0.5">Kirim laporan aduan atau konseling rahasia ke Guru BK.</p>
          </div>
        </div>
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { usePermitStore } from '@/stores/permit'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseBadge from '@/components/ui/BaseBadge.vue'
import { QrCode, FilePlus, ShieldAlert, AlertTriangle } from 'lucide-vue-next'

const authStore = useAuthStore()
const permitStore = usePermitStore()

const greetingText = computed(() => {
  const hour = new Date().getHours()
  if (hour < 11) return 'Selamat Pagi'
  if (hour < 15) return 'Selamat Siang'
  if (hour < 18) return 'Selamat Sore'
  return 'Selamat Malam'
})

onMounted(() => {
  permitStore.fetchActivePermit()
})
</script>
