<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    class="inline-flex items-center justify-center font-medium rounded-xl transition-all duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#355245] focus-visible:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed select-none active:scale-[0.98]"
    :class="[variantClasses, sizeClasses, block ? 'w-full' : '']"
  >
    <Loader2 v-if="loading" class="w-4 h-4 mr-2 animate-spin shrink-0" />
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
  variant: { type: String, default: 'primary' }, // primary, secondary, danger, outline, ghost
  size: { type: String, default: 'md' }, // sm, md, lg
  disabled: { type: Boolean, default: false },
  loading: { type: Boolean, default: false },
  block: { type: Boolean, default: false },
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
      return 'border border-slate-300 hover:bg-slate-50 text-slate-700'
    case 'ghost':
      return 'hover:bg-slate-100 text-slate-700'
    default:
      return 'bg-[#355245] text-white'
  }
})

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'px-3 py-1.5 text-xs gap-1.5'
    case 'lg':
      return 'px-6 py-3.5 text-base gap-2.5'
    default:
      return 'px-4 py-2.5 text-sm gap-2'
  }
})
</script>
