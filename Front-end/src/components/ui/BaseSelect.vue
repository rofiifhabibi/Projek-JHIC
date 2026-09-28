<template>
  <div class="flex w-full flex-col gap-1.5">
    <label
      v-if="label"
      :for="selectId"
      class="select-none text-caption font-semibold text-heading"
    >
      {{ label }} <span v-if="required" class="text-danger">*</span>
    </label>

    <div class="relative flex items-center">
      <select
        :id="selectId"
        :value="modelValue"
        :disabled="disabled"
        :aria-invalid="error ? 'true' : undefined"
        @change="$emit('update:modelValue', $event.target.value)"
        class="h-11 w-full appearance-none rounded-lg border bg-card py-0 pl-3.5 pr-10 text-body text-heading transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/25 disabled:cursor-not-allowed disabled:bg-subtle disabled:text-muted"
        :class="error
          ? 'border-danger-border focus:border-danger focus-visible:ring-danger/20'
          : 'border-border hover:border-border-strong focus:border-primary'"
      >
        <option v-if="placeholder" value="" disabled selected>{{ placeholder }}</option>
        <option
          v-for="opt in options"
          :key="getOptionValue(opt)"
          :value="getOptionValue(opt)"
        >
          {{ getOptionLabel(opt) }}
        </option>
      </select>

      <ChevronDown class="pointer-events-none absolute right-3.5 h-4 w-4 text-muted" />
    </div>

    <p v-if="error" class="mt-0.5 text-caption font-medium text-danger">{{ error }}</p>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { ChevronDown } from 'lucide-vue-next'

const props = defineProps({
  modelValue: { type: [String, Number], default: '' },
  options: { type: Array, default: () => [] },
  valueKey: { type: String, default: 'value' },
  labelKey: { type: String, default: 'label' },
  label: { type: String, default: '' },
  placeholder: { type: String, default: 'Pilih opsi...' },
  error: { type: String, default: '' },
  required: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  id: { type: String, default: '' }
})

defineEmits(['update:modelValue'])

const selectId = computed(() => props.id || `select-${Math.random().toString(36).substring(2, 9)}`)

const getOptionValue = (opt) => (typeof opt === 'object' ? opt[props.valueKey] : opt)
const getOptionLabel = (opt) => (typeof opt === 'object' ? opt[props.labelKey] : opt)
</script>
