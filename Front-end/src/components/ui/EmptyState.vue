<template>
  <div class="flex flex-col items-center justify-center px-4 py-8 text-center">
    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-subtle text-muted">
      <slot name="icon">
        <FolderOpen class="h-5 w-5" />
      </slot>
    </div>

    <h4 class="text-body font-semibold text-heading">
      {{ title }}
    </h4>

    <p v-if="description" class="mt-1 max-w-sm text-caption leading-relaxed text-muted">
      {{ description }}
    </p>

    <div v-if="$slots.action" class="mt-4">
      <slot name="action" />
    </div>
  </div>
</template>

<script setup>
import { FolderOpen } from 'lucide-vue-next'

defineProps({
  title: { type: String, default: 'Tidak ada data' },
  description: { type: String, default: 'Belum ada data yang tersedia saat ini.' }
})
</script>
