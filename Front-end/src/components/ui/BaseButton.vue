<template>
  <component
    :is="componentTag"
    :to="to || undefined"
    :href="componentTag === 'a' ? (href || undefined) : undefined"
    :type="componentTag === 'button' ? type : undefined"
    :disabled="componentTag === 'button' ? (disabled || loading) : undefined"
    class="inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#355245] focus-visible:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed select-none active:scale-[0.98] cursor-pointer touch-manipulation text-center leading-none"
    :class="[variantClasses, sizeClasses, block ? 'w-full !inline-flex !items-center !justify-center' : '']"
  >
    <Loader2 v-if="loading" class="w-4 h-4 animate-spin shrink-0" />
    <span v-if="$slots['icon-left']" class="inline-flex items-center justify-center shrink-0">
      <slot name="icon-left" />
    </span>
    <span class="inline-flex items-center justify-center leading-none">
      <slot />
    </span>
    <span v-if="$slots['icon-right']" class="inline-flex items-center justify-center shrink-0">
      <slot name="icon-right" />
    </span>
  </component>
</template>

<script setup>
import { computed } from 'vue'
import { RouterLink } from 'vue-router'
import { Loader2 } from 'lucide-vue-next'

const props = defineProps({
  to: { type: [String, Object], default: null },
  href: { type: String, default: null },
  type: { type: String, default: 'button' },
  variant: { type: String, default: 'primary' }, // primary, secondary, danger, outline, ghost
  size: { type: String, default: 'md' }, // sm, md, lg
  disabled: { type: Boolean, default: false },
  loading: { type: Boolean, default: false },
  block: { type: Boolean, default: false },
})

const componentTag = computed(() => {
  if (props.to) return RouterLink
  if (props.href) return 'a'
  return 'button'
})

const variantClasses = computed(() => {
  switch (props.variant) {
    case 'primary':
      return 'bg-[#355245] hover:bg-[#273e34] text-white shadow-sm shadow-[#355245]/20'
    case 'secondary':
      return 'bg-[#E8EFEA] hover:bg-[#d8e3db] text-[#273e34]'
    case 'danger':
      return 'bg-rose-600 hover:bg-rose-700 text-white shadow-sm shadow-rose-600/20'
    case 'outline':
      return 'border border-slate-300 hover:bg-slate-50 text-slate-700 bg-transparent'
    case 'ghost':
      return 'hover:bg-slate-100 text-slate-700 bg-transparent'
    default:
      return 'bg-[#355245] text-white'
  }
})

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'px-3.5 py-2 text-xs gap-1.5 min-h-[36px]'
    case 'lg':
      return 'px-6 py-3.5 text-base gap-2.5 min-h-[48px]'
    default:
      return 'px-4 py-2.5 text-sm gap-2 min-h-[42px]'
  }
})
</script>
