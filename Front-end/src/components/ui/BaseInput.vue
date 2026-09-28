<template>
  <div class="flex w-full flex-col gap-1.5">
    <label
      v-if="label"
      :for="inputId"
      class="flex select-none items-center justify-between gap-2 text-caption font-semibold text-heading"
    >
      <span>{{ label }} <span v-if="required" class="text-danger">*</span></span>
      <span v-if="hint" class="text-caption font-normal text-muted">{{ hint }}</span>
    </label>

    <div class="relative flex items-center">
      <div v-if="$slots['icon-left']" class="pointer-events-none absolute left-3.5 text-muted">
        <slot name="icon-left" />
      </div>

      <input
        :id="inputId"
        :type="type"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        :readonly="readonly"
        :aria-invalid="error ? 'true' : undefined"
        @input="$emit('update:modelValue', $event.target.value)"
        class="h-11 w-full rounded-lg border bg-card px-3.5 text-body text-heading transition-colors placeholder:text-muted/70 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/25 disabled:cursor-not-allowed disabled:bg-subtle disabled:text-muted"
        :class="[
          error
            ? 'border-danger-border focus:border-danger focus-visible:ring-danger/20'
            : 'border-border hover:border-border-strong focus:border-primary',
          $slots['icon-left'] ? 'pl-10' : '',
          $slots['icon-right'] ? 'pr-10' : ''
        ]"
      />

      <div v-if="$slots['icon-right']" class="absolute right-3 text-muted">
        <slot name="icon-right" />
      </div>
    </div>

    <p v-if="error" class="mt-0.5 text-caption font-medium text-danger">
      {{ error }}
    </p>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: { type: [String, Number], default: '' },
  label: { type: String, default: '' },
  type: { type: String, default: 'text' },
  placeholder: { type: String, default: '' },
  error: { type: String, default: '' },
  hint: { type: String, default: '' },
  required: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  readonly: { type: Boolean, default: false },
  id: { type: String, default: '' }
})

defineEmits(['update:modelValue'])

const inputId = computed(() => props.id || `input-${Math.random().toString(36).substring(2, 9)}`)
</script>
