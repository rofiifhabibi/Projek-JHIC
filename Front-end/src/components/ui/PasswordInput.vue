<template>
  <BaseInput
    :id="id"
    :label="label"
    :type="showPassword ? 'text' : 'password'"
    :placeholder="placeholder"
    :model-value="modelValue"
    :error="error"
    :required="required"
    @update:model-value="$emit('update:modelValue', $event)"
  >
    <template #icon-left v-if="$slots['icon-left']">
      <slot name="icon-left" />
    </template>
    <template #icon-right>
      <button
        type="button"
        @click="showPassword = !showPassword"
        class="text-slate-400 hover:text-slate-600 focus:outline-none transition"
        :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
      >
        <EyeOff v-if="showPassword" class="w-4 h-4" />
        <Eye v-else class="w-4 h-4" />
      </button>
    </template>
  </BaseInput>
</template>

<script setup>
import { ref } from 'vue'
import BaseInput from './BaseInput.vue'
import { Eye, EyeOff } from 'lucide-vue-next'

defineProps({
  modelValue: { type: String, default: '' },
  label: { type: String, default: 'Kata Sandi' },
  placeholder: { type: String, default: '••••••••' },
  error: { type: String, default: '' },
  required: { type: Boolean, default: false },
  id: { type: String, default: '' }
})

defineEmits(['update:modelValue'])

const showPassword = ref(false)
</script>
