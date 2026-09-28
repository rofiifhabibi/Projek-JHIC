<template>
  <label
    class="relative flex cursor-pointer select-none items-start gap-3 rounded-lg border p-4 transition-colors focus-within:ring-2 focus-within:ring-primary focus-within:ring-offset-2"
    :class="[
      selected
        ? 'border-primary bg-primary-soft'
        : 'border-border bg-card hover:border-border-strong'
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
      class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 transition-colors"
      :class="selected ? 'border-primary bg-primary' : 'border-border-strong bg-card'"
    >
      <div v-if="selected" class="h-2 w-2 rounded-full bg-white" />
    </div>

    <div class="flex-1">
      <div class="flex items-center gap-2">
        <span class="text-body font-semibold text-heading">{{ title }}</span>
        <slot name="badge" />
      </div>
      <p v-if="description" class="mt-0.5 text-caption leading-relaxed text-muted">
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
