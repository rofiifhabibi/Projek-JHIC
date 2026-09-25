<template>
  <label
    class="relative flex items-start gap-3 p-4 rounded-2xl border-2 transition-all cursor-pointer select-none focus-within:ring-2 focus-within:ring-[#355245] focus-within:ring-offset-2"
    :class="[
      selected
        ? 'border-[#355245] bg-[#E8EFEA]/40 shadow-sm'
        : 'border-slate-200 bg-white hover:border-slate-300'
    ]"
  >
    <input
      type="radio"
      :name="name"
      :value="value"
      :checked="selected"
      @change="$emit('update:modelValue', value)"
      class="sr-only"
    />

    <div
      class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 mt-0.5 transition-colors"
      :class="selected ? 'border-[#355245] bg-[#355245]' : 'border-slate-300 bg-white'"
    >
      <div v-if="selected" class="w-2 h-2 rounded-full bg-white" />
    </div>

    <div class="flex-1">
      <div class="flex items-center gap-2">
        <span class="font-semibold text-sm text-slate-900">{{ title }}</span>
        <slot name="badge" />
      </div>
      <p v-if="description" class="text-xs text-slate-500 mt-0.5 leading-relaxed">
        {{ description }}
      </p>
    </div>
  </label>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: { type: [String, Number], required: true },
  value: { type: [String, Number], required: true },
  name: { type: String, default: 'radio-group' },
  title: { type: String, required: true },
  description: { type: String, default: '' },
})

defineEmits(['update:modelValue'])

const selected = computed(() => props.modelValue === props.value)
</script>
