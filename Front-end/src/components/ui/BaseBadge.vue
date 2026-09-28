<template>
  <span
    class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-caption font-semibold whitespace-nowrap select-none"
    :class="badgeStyle"
  >
    <span class="h-1.5 w-1.5 shrink-0 rounded-full" :class="dotStyle" />
    <span>{{ labelText }}</span>
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  status: { type: String, required: true },
  label: { type: String, default: '' },
})

/* Status is a reserved semantic channel — never brand teal. */
const badgeStyle = computed(() => {
  switch (props.status?.toUpperCase()) {
    case 'ACTIVE':
    case 'IN_PROGRESS':
      return 'bg-status-active-bg text-status-active-fg border border-status-active-border'
    case 'PENDING':
    case 'OPEN':
      return 'bg-status-pending-bg text-status-pending-fg border border-status-pending-border'
    case 'OVERDUE':
      return 'bg-status-overdue-bg text-status-overdue-fg border border-status-overdue-border font-bold'
    case 'COMPLETED':
    case 'RESOLVED':
    case 'CLOSED':
      return 'bg-status-neutral-bg text-status-neutral-fg border border-status-neutral-border'
    case 'ALPHA':
    case 'REJECTED':
      return 'bg-status-danger-bg text-status-danger-fg border border-status-danger-border'
    default:
      return 'bg-subtle text-muted border border-border'
  }
})

const dotStyle = computed(() => {
  switch (props.status?.toUpperCase()) {
    case 'ACTIVE':
    case 'IN_PROGRESS':
      return 'bg-status-active-fg'
    case 'PENDING':
    case 'OPEN':
      return 'bg-status-pending-fg'
    case 'OVERDUE':
      return 'bg-status-overdue-fg'
    case 'ALPHA':
    case 'REJECTED':
      return 'bg-status-danger-fg'
    default:
      return 'bg-border-strong'
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
