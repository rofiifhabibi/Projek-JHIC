<template>
  <button
    v-if="isSupported"
    type="button"
    @click="handleToggle"
    :title="buttonTitle"
    :aria-label="buttonTitle"
    class="relative transition-all duration-150 inline-flex items-center justify-center gap-1.5 cursor-pointer touch-manipulation select-none leading-none"
    :class="buttonClass"
  >
    <component :is="iconComponent" class="w-3.5 h-3.5 shrink-0" :class="iconClass" />
    <span v-if="showLabel" class="hidden sm:inline-flex items-center text-xs font-semibold leading-none">
      {{ labelText }}
    </span>
    <!-- Indikator titik hijau jika notifikasi aktif -->
    <span
      v-if="isGranted"
      class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-emerald-400 border border-[#355245]"
    />
  </button>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { Bell, BellOff } from 'lucide-vue-next'
import { useWebNotification } from '@/composables/useWebNotification'
import { useToast } from '@/composables/useToast'

const props = defineProps({
  variant: {
    type: String,
    default: 'dark' // 'dark' (latar hijau gelap) atau 'light' (latar putih/terang)
  },
  showLabel: {
    type: Boolean,
    default: true
  }
})

const { isSupported, permission, isGranted, requestPermission, showSystemNotification, checkPermission } = useWebNotification()
const toast = useToast()

onMounted(() => {
  checkPermission()
})

const buttonTitle = computed(() => {
  if (permission.value === 'granted') {
    return 'Notifikasi sistem aktif (Klik untuk mengirim tes notifikasi)'
  }
  if (permission.value === 'denied') {
    return 'Izin notifikasi diblokir di peramban'
  }
  return 'Aktifkan notifikasi sistem PWA'
})

const labelText = computed(() => {
  if (permission.value === 'granted') return 'Notif Aktif'
  if (permission.value === 'denied') return 'Notif Diblokir'
  return 'Notifikasi'
})

const iconComponent = computed(() => {
  return permission.value === 'denied' ? BellOff : Bell
})

const buttonClass = computed(() => {
  if (props.variant === 'light') {
    return 'px-2.5 sm:px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 active:scale-95 shadow-xs'
  }
  return 'px-2.5 sm:px-3 py-1.5 rounded-xl border border-white/15 bg-white/10 hover:bg-white/20 text-white active:scale-95 shadow-xs'
})

const iconClass = computed(() => {
  if (permission.value === 'granted') {
    return props.variant === 'light' ? 'text-emerald-600' : 'text-emerald-300'
  }
  if (permission.value === 'denied') {
    return props.variant === 'light' ? 'text-rose-500' : 'text-rose-300'
  }
  return props.variant === 'light' ? 'text-slate-500' : 'text-white/80'
})

const handleToggle = async () => {
  checkPermission()

  if (permission.value === 'default') {
    const granted = await requestPermission()
    if (granted) {
      toast.success('Notifikasi sistem berhasil diaktifkan.')
      await showSystemNotification('Notifikasi Sistem Aktif', {
        body: 'Pembaruan status izin dan laporan akan ditampilkan secara langsung.',
        url: window.location.pathname,
        skipDedupe: true
      })
    } else if (permission.value === 'denied') {
      toast.warning('Izin notifikasi ditolak oleh peramban.')
    }
    return
  }

  if (permission.value === 'granted') {
    const sent = await showSystemNotification('Tes Notifikasi Sistem', {
      body: 'Notifikasi sistem StudentCare aktif dan berfungsi normal.',
      url: window.location.pathname,
      skipDedupe: true
    })
    if (sent) {
      toast.info('Tes notifikasi sistem berhasil dikirim.')
    } else {
      toast.info('Notifikasi sistem aktif pada peramban ini.')
    }
    return
  }

  if (permission.value === 'denied') {
    toast.warning('Izin notifikasi diblokir oleh peramban. Silakan aktifkan izin notifikasi pada pengaturan situs peramban Anda.')
  }
}
</script>
