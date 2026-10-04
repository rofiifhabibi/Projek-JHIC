import { ref, computed } from 'vue'

const permission = ref(typeof window !== 'undefined' && 'Notification' in window ? Notification.permission : 'default')
const isSupported = typeof window !== 'undefined' && 'Notification' in window
const recentNotificationKeys = new Set()

export function useWebNotification() {
  const isGranted = computed(() => permission.value === 'granted')

  const checkPermission = () => {
    if (isSupported) {
      permission.value = Notification.permission
    }
  }

  const requestPermission = async () => {
    if (!isSupported) {
      console.warn('Web Notification API tidak didukung di perangkat ini.')
      return false
    }

    try {
      const result = await Notification.requestPermission()
      permission.value = result
      return result === 'granted'
    } catch (err) {
      console.warn('Gagal meminta izin notifikasi:', err)
      return false
    }
  }

  const showSystemNotification = async (title, options = {}) => {
    if (!isSupported) {
      return false
    }

    checkPermission()
    if (permission.value !== 'granted') {
      return false
    }

    // Hindari duplikasi spam notifikasi yang identik dalam interval 2.5 detik
    const dedupeKey = `${title}_${options.body || ''}`
    if (!options.skipDedupe) {
      if (recentNotificationKeys.has(dedupeKey)) {
        return false
      }
      recentNotificationKeys.add(dedupeKey)
      setTimeout(() => {
        recentNotificationKeys.delete(dedupeKey)
      }, 2500)
    }

    const payload = {
      body: options.body || '',
      icon: '/logos/icon-192.png',
      badge: '/logos/icon-192.png',
      vibrate: [100, 50, 100],
      tag: options.tag || (options.skipDedupe ? `test_${Date.now()}` : dedupeKey),
      renotify: true,
      data: {
        url: options.url || '/'
      }
    }

    try {
      // Prioritaskan ServiceWorkerRegistration (wajib untuk browser mobile / Android Chrome)
      if (typeof navigator !== 'undefined' && 'serviceWorker' in navigator) {
        try {
          const registration = await Promise.race([
            navigator.serviceWorker.ready,
            new Promise((resolve) => setTimeout(() => resolve(null), 800))
          ])

          if (registration && typeof registration.showNotification === 'function') {
            await registration.showNotification(title, payload)
            return true
          }

          if (navigator.serviceWorker.getRegistration) {
            const activeReg = await navigator.serviceWorker.getRegistration()
            if (activeReg && typeof activeReg.showNotification === 'function') {
              await activeReg.showNotification(title, payload)
              return true
            }
          }
        } catch (swErr) {
          console.warn('Percobaan Service Worker notification dilewati:', swErr)
        }
      }

      // Fallback ke Web Notification standar (desktop browser)
      if (typeof Notification === 'function') {
        new Notification(title, payload)
        return true
      }

      return false
    } catch (err) {
      console.warn('Gagal memunculkan notifikasi sistem:', err)
      return false
    }
  }

  return {
    isSupported,
    permission,
    isGranted,
    checkPermission,
    requestPermission,
    showSystemNotification
  }
}
