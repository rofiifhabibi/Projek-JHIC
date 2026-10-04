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

    // Hindari duplikasi spam notifikasi yang identik dalam interval 4 detik
    const dedupeKey = `${title}_${options.body || ''}`
    if (recentNotificationKeys.has(dedupeKey)) {
      return false
    }
    recentNotificationKeys.add(dedupeKey)
    setTimeout(() => {
      recentNotificationKeys.delete(dedupeKey)
    }, 4000)

    const payload = {
      body: options.body || '',
      icon: '/logos/icon-192.png',
      badge: '/logos/icon-192.png',
      vibrate: [100, 50, 100],
      tag: options.tag || dedupeKey,
      renotify: true,
      data: {
        url: options.url || '/'
      }
    }

    try {
      if ('serviceWorker' in navigator) {
        const registration = await navigator.serviceWorker.ready
        if (registration && registration.showNotification) {
          await registration.showNotification(title, payload)
          return true
        }
      }

      new Notification(title, payload)
      return true
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
