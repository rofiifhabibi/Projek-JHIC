<template>
  <div class="flex flex-col items-center justify-center text-center p-6 sm:p-10 bg-white/80 backdrop-blur-sm rounded-xl sm:rounded-2xl border border-dashed border-slate-200 shadow-2xs">
    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
      <slot name="icon">
        <FolderOpen class="w-6 h-6" />
      </slot>
    </div>

    <h4 class="text-sm sm:text-base font-bold text-slate-900 mb-1">
      {{ title }}
    </h4>

    <p
      class="text-xs sm:text-sm text-slate-500 max-w-sm leading-relaxed"
      :class="$slots.action ? 'mb-6' : 'mb-0'"
    >
      {{ description }}
    </p>

    <slot name="action" />
  </div>
</template>

<script setup>
import { FolderOpen } from 'lucide-vue-next'

defineProps({
  title: { type: String, default: 'Tidak ada data' },
  description: { type: String, default: 'Belum ada data yang tersedia saat ini.' }
})
</script>
