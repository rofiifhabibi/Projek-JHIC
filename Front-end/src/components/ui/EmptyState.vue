<template>
  <div class="flex flex-col items-center justify-center text-center p-8 sm:p-12 bg-white/60 backdrop-blur-sm rounded-2xl border border-dashed border-slate-200">
    <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
      <slot name="icon">
        <FolderOpen class="w-7 h-7" />
      </slot>
    </div>

    <h4 class="text-base font-bold text-slate-900 mb-1">
      {{ title }}
    </h4>

    <p class="text-xs sm:text-sm text-slate-500 max-w-sm mb-6 leading-relaxed">
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
