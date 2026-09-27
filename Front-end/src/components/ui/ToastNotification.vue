<template>
  <Teleport to="body">
    <div
      class="fixed top-4 inset-x-0 z-50 flex flex-col items-center gap-2.5 px-4 pointer-events-none sm:top-5 sm:right-6 sm:left-auto sm:px-0 sm:items-end"
    >
      <TransitionGroup
        enter-active-class="transform transition duration-300 ease-out"
        enter-from-class="-translate-y-2 opacity-0 scale-95 sm:translate-y-0 sm:translate-x-3"
        enter-to-class="translate-y-0 opacity-100 scale-100 sm:translate-x-0"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
      >
        <div
          v-for="toast in toasts"
          :key="toast.id"
          class="pointer-events-auto flex items-start gap-3 p-3.5 sm:p-4 rounded-2xl shadow-xl border backdrop-blur-md transition-all w-full max-w-sm"
          :class="getToastClass(toast.type)"
        >
          <!-- Icon -->
          <component :is="getToastIcon(toast.type)" class="w-5 h-5 shrink-0 mt-0.5" />

          <!-- Message -->
          <div class="flex-1 text-xs sm:text-sm font-semibold leading-relaxed">
            {{ toast.message }}
          </div>

          <!-- Close button -->
          <button
            @click="removeToast(toast.id)"
            class="shrink-0 p-1 rounded-lg opacity-70 hover:opacity-100 hover:bg-black/5 transition"
            aria-label="Tutup notifikasi"
          >
            <X class="w-4 h-4" />
          </button>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup>
import { useToast } from '@/composables/useToast'
import { CheckCircle, AlertCircle, AlertTriangle, Info, X } from 'lucide-vue-next'

const { toasts, removeToast } = useToast()

const getToastClass = (type) => {
  switch (type) {
    case 'success':
      return 'bg-emerald-50/95 border-emerald-200 text-emerald-900'
    case 'error':
      return 'bg-rose-50/95 border-rose-200 text-rose-900'
    case 'warning':
      return 'bg-amber-50/95 border-amber-200 text-amber-900'
    default:
      return 'bg-slate-900/95 border-slate-700 text-white'
  }
}

const getToastIcon = (type) => {
  switch (type) {
    case 'success':
      return CheckCircle
    case 'error':
      return AlertCircle
    case 'warning':
      return AlertTriangle
    default:
      return Info
  }
}
</script>
