<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    class="inline-flex items-center justify-center rounded-lg font-semibold whitespace-nowrap transition-colors duration-150 select-none focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-canvas disabled:opacity-45 disabled:cursor-not-allowed disabled:pointer-events-none"
    :class="[variantClasses, sizeClasses, block ? 'w-full' : '']"
  >
    <Loader2 v-if="loading" class="w-4 h-4 shrink-0 animate-spin" :class="block || size !== 'sm' ? 'mr-2' : 'mr-1.5'" />
    <slot name="icon-left" />
    <slot />
    <slot name="icon-right" />
  </button>
</template>

<script setup>
import { computed } from 'vue'
import { Loader2 } from 'lucide-vue-next'

const props = defineProps({
  type: { type: String, default: 'button' },
  variant: { type: String, default: 'primary' }, // primary, dark, secondary, danger, outline, ghost
  size: { type: String, default: 'md' }, // sm, md, lg
  disabled: { type: Boolean, default: false },
  loading: { type: Boolean, default: false },
  block: { type: Boolean, default: false },
})

const variantClasses = computed(() => {
  switch (props.variant) {
    case 'primary':
      return 'bg-primary text-white hover:bg-primary-hover'
    case 'dark':
      return 'bg-primary-deep text-white hover:bg-primary-deep-hover'
    case 'secondary':
      return 'bg-primary-soft text-primary-deep border border-primary-border hover:bg-primary-soft-strong'
    case 'danger':
      return 'bg-danger text-white hover:bg-danger-hover'
    case 'outline':
      return 'bg-card text-body border border-border hover:bg-subtle hover:border-border-strong'
    case 'ghost':
      return 'bg-transparent text-muted hover:bg-subtle hover:text-body'
    default:
      return 'bg-primary text-white hover:bg-primary-hover'
  }
})

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'h-10 px-3.5 text-caption gap-1.5'
    case 'lg':
      return 'h-12 px-6 text-body-lg gap-2.5'
    default:
      return 'h-11 px-4 text-body gap-2'
  }
})
</script>
