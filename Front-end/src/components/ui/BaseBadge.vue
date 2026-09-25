<template>
  <span
    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold tracking-wide uppercase select-none transition-all"
    :class="badgeStyle"
  >
    <span class="w-1.5 h-1.5 rounded-full" :class="dotStyle" />
    <span>{{ labelText }}</span>
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  status: { type: String, required: true },
  label: { type: String, default: '' },
})

const badgeStyle = computed(() => {
  switch (props.status?.toUpperCase()) {
    case 'ACTIVE':
    case 'IN_PROGRESS':
      return 'bg-emerald-50 text-emerald-700 border border-emerald-200'
    case 'PENDING':
    case 'OPEN':
      return 'bg-amber-50 text-amber-700 border border-amber-200'
    case 'OVERDUE':
      return 'bg-orange-50 text-orange-700 border border-orange-200 animate-pulse'
    case 'COMPLETED':
    case 'RESOLVED':
    case 'CLOSED':
      return 'bg-slate-100 text-slate-700 border border-slate-200'
    case 'ALPHA':
    case 'REJECTED':
      return 'bg-rose-50 text-rose-700 border border-rose-200'
    default:
      return 'bg-slate-100 text-slate-600 border border-slate-200'
  }
})

const dotStyle = computed(() => {
  switch (props.status?.toUpperCase()) {
    case 'ACTIVE':
    case 'IN_PROGRESS':
      return 'bg-emerald-500'
    case 'PENDING':
    case 'OPEN':
      return 'bg-amber-500'
    case 'OVERDUE':
      return 'bg-orange-500'
    case 'COMPLETED':
    case 'RESOLVED':
    case 'CLOSED':
      return 'bg-slate-400'
    case 'ALPHA':
    case 'REJECTED':
      return 'bg-rose-500'
    default:
      return 'bg-slate-400'
  }
})

const labelText = computed(() => {
  if (props.label) return props.label
  switch (props.status?.toUpperCase()) {
    case 'ACTIVE': return 'Aktif'
    case 'PENDING': return 'Menunggu'
    case 'OVERDUE': return 'Terlambat'
    case 'COMPLETED': return 'Selesai'
    case 'ALPHA': return 'Alpha'
    case 'CLOSED': return 'Ditutup'
    case 'REJECTED': return 'Ditolak'
    case 'OPEN': return 'Baru'
    case 'IN_PROGRESS': return 'Diproses'
    case 'RESOLVED': return 'Tuntas'
    default: return props.status
  }
})
</script>
