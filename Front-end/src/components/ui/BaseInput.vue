<template>
  <div class="flex flex-col gap-1.5 w-full">
    <label
      v-if="label"
      :for="inputId"
      class="text-xs font-semibold uppercase tracking-wider text-slate-700 select-none flex items-center justify-between"
    >
      <span>{{ label }} <span v-if="required" class="text-rose-500">*</span></span>
      <span v-if="hint" class="text-[11px] font-normal lowercase text-slate-400 normal-case">{{ hint }}</span>
    </label>

    <div class="relative flex items-center">
      <div v-if="$slots['icon-left']" class="absolute left-3.5 text-slate-400 pointer-events-none">
        <slot name="icon-left" />
      </div>

      <input
        :id="inputId"
        :type="type"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        :readonly="readonly"
        @input="$emit('update:modelValue', $event.target.value)"
        class="w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition-all focus:outline-none focus:ring-2 focus:ring-[#355245] disabled:bg-slate-50 disabled:text-slate-400 disabled:cursor-not-allowed"
        :class="[
          error ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-[#355245]',
          $slots['icon-left'] ? 'pl-10' : '',
          $slots['icon-right'] ? 'pr-10' : ''
        ]"
      />

      <div v-if="$slots['icon-right']" class="absolute right-3.5 text-slate-400">
        <slot name="icon-right" />
      </div>
    </div>

    <p v-if="error" class="text-xs text-rose-600 font-medium flex items-center gap-1 mt-0.5">
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
