<template>
  <Teleport to="body">
    <div class="pointer-events-none fixed right-4 top-4 z-50 flex w-full max-w-sm flex-col gap-2 px-4 sm:px-0">
      <TransitionGroup
        enter-active-class="transform transition duration-200 ease-out"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-for="toast in toasts"
          :key="toast.id"
          class="pointer-events-auto flex items-start gap-3 rounded-lg border p-4 shadow-overlay"
          :class="getToastClass(toast.type)"
        >
          <!-- Icon -->
          <component :is="getToastIcon(toast.type)" class="mt-0.5 h-5 w-5 shrink-0" />

          <!-- Message -->
          <div class="flex-1 text-body font-medium leading-relaxed">
            {{ toast.message }}
          </div>

          <!-- Close button -->
          <button
            @click="removeToast(toast.id)"
            class="-mr-1 -mt-1 shrink-0 rounded-md p-2 opacity-70 transition hover:bg-black/5 hover:opacity-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-current"
            aria-label="Tutup notifikasi"
          >
            <X class="h-4 w-4" />
          </button>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup>
import { useToast } from '@/composables/useToast'
import { CheckCircle2, AlertCircle, AlertTriangle, Info, X } from 'lucide-vue-next'

const { toasts, removeToast } = useToast()

/* Semantic status tokens — teal is never used to signal a status. */
const getToastClass = (type) => {
  switch (type) {
    case 'success':
      return 'bg-status-active-bg border-status-active-border text-status-active-fg'
    case 'error':
      return 'bg-status-overdue-bg border-status-overdue-border text-status-overdue-fg'
    case 'warning':
      return 'bg-status-pending-bg border-status-pending-border text-status-pending-fg'
    default:
      return 'bg-heading text-white border-heading'
  }
}

const getToastIcon = (type) => {
  switch (type) {
    case 'success':
      return CheckCircle2
    case 'error':
      return AlertCircle
    case 'warning':
      return AlertTriangle
    default:
      return Info
  }
}
</script>
