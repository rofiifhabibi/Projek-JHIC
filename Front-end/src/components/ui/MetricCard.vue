<template>
  <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between hover:border-slate-300 transition group">
    <div>
      <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">
        {{ label }}
      </p>
      <div class="flex items-baseline gap-2">
        <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
          {{ value }}
        </h3>
        <span v-if="unit" class="text-xs font-medium text-slate-400">{{ unit }}</span>
      </div>
      <p v-if="description" class="text-xs text-slate-500 mt-1">
        {{ description }}
      </p>
    </div>

    <div
      v-if="$slots.icon || icon"
      class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 transition-transform group-hover:scale-105"
      :class="iconBgClass"
    >
      <slot name="icon">
        <component :is="icon" class="w-6 h-6" :class="iconColorClass" />
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

const iconBgClass = computed(() => {
  switch (props.color) {
    case 'success': return 'bg-emerald-50 text-emerald-600'
    case 'warning': return 'bg-amber-50 text-amber-600'
    case 'danger': return 'bg-rose-50 text-rose-600'
    case 'info': return 'bg-sky-50 text-sky-600'
    case 'slate': return 'bg-slate-100 text-slate-600'
    default: return 'bg-[#E8EFEA] text-[#355245]'
  }
})

const iconColorClass = computed(() => {
  switch (props.color) {
    case 'success': return 'text-emerald-600'
    case 'warning': return 'text-amber-600'
    case 'danger': return 'text-rose-600'
    case 'info': return 'text-sky-600'
    case 'slate': return 'text-slate-600'
    default: return 'text-[#355245]'
  }
})
</script>
