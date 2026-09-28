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
        class="-mr-1.5 rounded-md p-2 text-muted transition-colors hover:text-body focus:outline-none focus-visible:ring-2 focus-visible:ring-primary"
        :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
      >
        <EyeOff v-if="showPassword" class="h-4 w-4" />
        <Eye v-else class="h-4 w-4" />
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
