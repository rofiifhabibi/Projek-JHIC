import { ref } from 'vue'

const toasts = ref([])
let idCounter = 0

// Map to track active auto-dismiss timeouts by toast ID
const activeTimers = new Map()

// Map to track the timestamp of recent messages to enforce a debounce/cooldown window
const recentMessageTimes = new Map()

// Global timestamp of the last toast shown
let lastToastTimestamp = 0

// Cooldown period: within 2200ms, ignore duplicate messages completely when clicked repeatedly
const DUPLICATE_COOLDOWN_MS = 2200

// Anti-burst interval: prevent rapid successive toasts (within 400ms)
const MIN_TOAST_INTERVAL_MS = 400

// Maximum visible toasts on screen simultaneously
const MAX_VISIBLE_TOASTS = 2

export function useToast() {
  const addToast = (message, type = 'info', duration = 3500) => {
    if (!message || typeof message !== 'string') return
    const cleanMessage = message.trim()
    if (!cleanMessage) return

    const now = Date.now()
    const lastSeen = recentMessageTimes.get(cleanMessage) || 0

    // 1. Hindari spam pesan yang sama apabila tombol ditekan terus-menerus
    if (now - lastSeen < DUPLICATE_COOLDOWN_MS) {
      // Jika toast dengan pesan ini masih aktif di layar, segarkan durasinya saja
      const existingToast = toasts.value.find((t) => t.message === cleanMessage)
      if (existingToast) {
        existingToast.type = type
        if (activeTimers.has(existingToast.id)) {
          clearTimeout(activeTimers.get(existingToast.id))
        }
        if (duration > 0) {
          const timer = setTimeout(() => {
            removeToast(existingToast.id)
          }, duration)
          activeTimers.set(existingToast.id, timer)
        }
      }
      return
    }

    // 2. Anti-burst rate limiting: jika ada toast berbeda masuk dalam selang < 400ms
    if (now - lastToastTimestamp < MIN_TOAST_INTERVAL_MS && toasts.value.length >= 1) {
      if (toasts.value.length >= MAX_VISIBLE_TOASTS) {
        const oldest = toasts.value.shift()
        if (oldest && activeTimers.has(oldest.id)) {
          clearTimeout(activeTimers.get(oldest.id))
          activeTimers.delete(oldest.id)
        }
      }
    }

    // 3. Batasi maksimal 2 toast sekaligus di layar agar tidak menumpuk menutupi UI
    while (toasts.value.length >= MAX_VISIBLE_TOASTS) {
      const oldest = toasts.value.shift()
      if (oldest && activeTimers.has(oldest.id)) {
        clearTimeout(activeTimers.get(oldest.id))
        activeTimers.delete(oldest.id)
      }
    }

    const id = ++idCounter
    toasts.value.push({ id, message: cleanMessage, type })
    recentMessageTimes.set(cleanMessage, now)
    lastToastTimestamp = now

    // Bersihkan memori recentMessageTimes jika terlalu banyak
    if (recentMessageTimes.size > 50) {
      const cutoff = now - 15000
      for (const [msg, time] of recentMessageTimes.entries()) {
        if (time < cutoff) recentMessageTimes.delete(msg)
      }
    }

    if (duration > 0) {
      const timer = setTimeout(() => {
        removeToast(id)
      }, duration)
      activeTimers.set(id, timer)
    }
  }

  const removeToast = (id) => {
    if (activeTimers.has(id)) {
      clearTimeout(activeTimers.get(id))
      activeTimers.delete(id)
    }
    toasts.value = toasts.value.filter((t) => t.id !== id)
  }

  const clearAllToasts = () => {
    activeTimers.forEach((timer) => clearTimeout(timer))
    activeTimers.clear()
    toasts.value = []
  }

  const success = (message, duration) => addToast(message, 'success', duration)
  const error = (message, duration) => addToast(message, 'error', duration)
  const warning = (message, duration) => addToast(message, 'warning', duration)
  const info = (message, duration) => addToast(message, 'info', duration)

  return {
    toasts,
    addToast,
    removeToast,
    clearAllToasts,
    success,
    error,
    warning,
    info,
  }
}
