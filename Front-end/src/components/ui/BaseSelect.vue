<template>
  <div class="flex flex-col gap-1.5 w-full">
    <label
      v-if="label"
      :for="selectId"
      class="text-xs font-semibold uppercase tracking-wider text-slate-700 select-none"
    >
      {{ label }} <span v-if="required" class="text-rose-500">*</span>
    </label>

    <div class="relative flex items-center">
      <select
        :id="selectId"
        :value="modelValue"
        :disabled="disabled"
        @change="$emit('update:modelValue', $event.target.value)"
        class="w-full appearance-none rounded-xl border bg-white px-3.5 py-2.5 pr-10 text-sm font-medium text-slate-900 transition-all focus:outline-none focus:ring-2 focus:ring-[#355245] disabled:bg-slate-50 disabled:text-slate-400 disabled:cursor-not-allowed"
        :class="error ? 'border-rose-300 focus:border-rose-500' : 'border-slate-200 focus:border-[#355245]'"
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

      <ChevronDown class="absolute right-3.5 w-4 h-4 text-slate-400 pointer-events-none" />
    </div>

    <p v-if="error" class="text-xs text-rose-600 font-medium mt-0.5">{{ error }}</p>
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
