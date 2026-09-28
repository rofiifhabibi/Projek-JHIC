<template>
  <div class="flex items-center justify-between gap-4 rounded-xl border border-border bg-card px-4 py-4">
    <div class="min-w-0">
      <p class="text-caption font-medium text-muted">
        {{ label }}
      </p>
      <div class="mt-1 flex items-baseline gap-1.5">
        <h3 class="text-h1 font-bold tracking-tight text-heading">
          {{ value }}
        </h3>
        <span v-if="unit" class="text-caption font-medium text-muted">{{ unit }}</span>
      </div>
      <p v-if="description" class="mt-1 text-caption text-muted">
        {{ description }}
      </p>
    </div>

    <div
      v-if="$slots.icon || icon"
      class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg"
      :class="iconBgClass"
    >
      <slot name="icon">
        <component :is="icon" class="h-5 w-5" :class="iconColorClass" />
      </slot>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  label: { type: String, required: true },
  value: { type: [String, Number], required: true },
  unit: { type: String, default: '' },
  description: { type: String, default: '' },
  icon: { type: Object, default: null },
  color: { type: String, default: 'primary' } // primary, success, warning, danger, info, slate
})

/* Brand teal is reserved for primary; status colours come from the
   semantic status tokens so they never read as brand. */
const iconBgClass = computed(() => {
  switch (props.color) {
    case 'success': return 'bg-status-active-bg text-status-active-fg'
    case 'warning': return 'bg-status-pending-bg text-status-pending-fg'
    case 'danger': return 'bg-status-overdue-bg text-status-overdue-fg'
    case 'info': return 'bg-status-info-bg text-status-info-fg'
    case 'slate': return 'bg-subtle text-muted'
    default: return 'bg-primary-soft text-primary'
  }
})

const iconColorClass = computed(() => {
  switch (props.color) {
    case 'success': return 'text-status-active-fg'
    case 'warning': return 'text-status-pending-fg'
    case 'danger': return 'text-status-overdue-fg'
    case 'info': return 'text-status-info-fg'
    case 'slate': return 'text-muted'
    default: return 'text-primary'
  }
})
</script>
