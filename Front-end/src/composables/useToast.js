import { ref } from 'vue'

const toasts = ref([])
let idCounter = 0

export function useToast() {
  const addToast = (message, type = 'info', duration = 3500) => {
    // Hindari menumpuk toast dengan pesan persis sama
    const existingIndex = toasts.value.findIndex((t) => t.message === message)
    if (existingIndex !== -1) {
      toasts.value[existingIndex].type = type
      return
    }

    // Batasi maksimal 2 toast sekaligus di layar HP agar tidak menutupi tombol UI
    if (toasts.value.length >= 2) {
      toasts.value.shift()
    }

    const id = ++idCounter
    toasts.value.push({ id, message, type })
    if (duration > 0) {
      setTimeout(() => {
        removeToast(id)
      }, duration)
    }
  }

  const removeToast = (id) => {
    toasts.value = toasts.value.filter((t) => t.id !== id)
  }

  const success = (message, duration) => addToast(message, 'success', duration)
  const error = (message, duration) => addToast(message, 'error', duration)
  const warning = (message, duration) => addToast(message, 'warning', duration)
  const info = (message, duration) => addToast(message, 'info', duration)

  return {
    toasts,
    addToast,
    removeToast,
    success,
    error,
    warning,
    info,
  }
}
