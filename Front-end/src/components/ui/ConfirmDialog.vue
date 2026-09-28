<template>
  <BaseModal :show="show" :title="title" max-width="sm" @close="$emit('cancel')">
    <template #icon>
      <AlertTriangle v-if="variant === 'danger'" class="h-5 w-5 text-danger" />
      <HelpCircle v-else class="h-5 w-5 text-primary" />
    </template>

    <p class="text-body leading-relaxed text-body">
      {{ message }}
    </p>

    <template #footer>
      <BaseButton variant="outline" size="sm" @click="$emit('cancel')">
        {{ cancelText }}
      </BaseButton>
      <BaseButton :variant="variant === 'danger' ? 'danger' : 'primary'" size="sm" :loading="loading" @click="$emit('confirm')">
        {{ confirmText }}
      </BaseButton>
    </template>
  </BaseModal>
</template>

<script setup>
import BaseModal from './BaseModal.vue'
import BaseButton from './BaseButton.vue'
import { AlertTriangle, HelpCircle } from 'lucide-vue-next'

defineProps({
  show: { type: Boolean, default: false },
  title: { type: String, default: 'Konfirmasi Tindakan' },
  message: { type: String, required: true },
  confirmText: { type: String, default: 'Ya, Lanjutkan' },
  cancelText: { type: String, default: 'Batal' },
  variant: { type: String, default: 'danger' }, // danger, primary
  loading: { type: Boolean, default: false }
})

defineEmits(['confirm', 'cancel'])
</script>
