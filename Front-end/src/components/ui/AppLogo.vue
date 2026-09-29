<template>
  <!-- Full Horizontal Lockup (Icon + Text) -->
  <div v-if="variant === 'full'" class="inline-flex items-center select-none shrink-0">
    <img
      :src="isWhiteVariant ? '/logos/studentcare-logo-white.png' : '/logos/studentcare-logo.png'"
      alt="Student Care — SMKN 2 Depok Sleman"
      :class="fullLogoHeightClass"
      class="object-contain"
    />
  </div>

  <!-- Icon Badge / Monogram -->
  <div
    v-else
    class="inline-flex items-center justify-center shrink-0 transition-all select-none overflow-hidden"
    :class="containerClasses"
  >
    <!-- Default / StudentCare Logo: Official SC Monogram Emblem -->
    <img
      v-if="variant === 'default' || variant === 'sc'"
      :src="isWhiteIcon ? '/logos/studentcare-icon-white.png' : '/logos/studentcare-icon.png'"
      alt="StudentCare Logo"
      :class="iconSizeClass"
      class="object-contain"
    />

    <!-- Guru / Teacher Variant -->
    <div v-else-if="variant === 'teacher' || variant === 'gp'" class="relative flex items-center justify-center">
      <img
        :src="isWhiteIcon ? '/logos/studentcare-icon-white.png' : '/logos/studentcare-icon.png'"
        alt="Guru Pengajar"
        :class="iconSizeClass"
        class="object-contain"
      />
    </div>

    <!-- BK / Counseling Variant -->
    <div v-else-if="variant === 'bk'" class="relative flex items-center justify-center">
      <img
        :src="isWhiteIcon ? '/logos/studentcare-icon-white.png' : '/logos/studentcare-icon.png'"
        alt="Bimbingan Konseling"
        :class="iconSizeClass"
        class="object-contain"
      />
    </div>

    <!-- Satpam / Security Variant -->
    <div v-else-if="variant === 'satpam' || variant === 'sp'" class="relative flex items-center justify-center">
      <img
        :src="isWhiteIcon ? '/logos/studentcare-icon-white.png' : '/logos/studentcare-icon.png'"
        alt="Satpam"
        :class="iconSizeClass"
        class="object-contain"
      />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: {
    type: String,
    default: 'default', // 'default' | 'sc' | 'full' | 'teacher' | 'bk' | 'satpam'
  },
  size: {
    type: String,
    default: 'md', // 'sm' | 'md' | 'lg' | 'xl'
  },
  theme: {
    type: String,
    default: 'white-tile', // 'white-tile' | 'white-trans' | 'primary' | 'dark' | 'emerald' | 'plain'
  }
})

const isWhiteVariant = computed(() => {
  return props.theme === 'white-trans' || props.theme === 'white'
})

const isWhiteIcon = computed(() => {
  return props.theme === 'white-trans' || props.theme === 'white'
})

const fullLogoHeightClass = computed(() => {
  switch (props.size) {
    case 'sm': return 'h-6 sm:h-7'
    case 'lg': return 'h-10 sm:h-12'
    case 'xl': return 'h-12 sm:h-14'
    case 'md':
    default: return 'h-7 sm:h-8'
  }
})

const containerClasses = computed(() => {
  const sizes = {
    sm: 'w-8 h-8 rounded-lg p-1',
    md: 'w-10 h-10 rounded-xl p-1.5',
    lg: 'w-12 h-12 rounded-2xl p-2',
    xl: 'w-16 h-16 rounded-2xl p-2.5'
  }
  const themes = {
    'white-tile': 'bg-white shadow-xs border border-slate-100',
    'white-trans': 'bg-white/15 border border-white/20 shadow-xs backdrop-blur-xs',
    white: 'bg-transparent',
    primary: 'bg-[#355245] text-white shadow-xs border border-white/10',
    dark: 'bg-slate-900 border border-slate-800 shadow-xs',
    emerald: 'bg-[#1c2e26]/90 border border-emerald-500/30 shadow-xs',
    plain: 'bg-transparent p-0'
  }
  return `${sizes[props.size] || sizes.md} ${themes[props.theme] || themes['white-tile']}`
})

const iconSizeClass = computed(() => {
  switch (props.size) {
    case 'sm': return 'w-5 h-5'
    case 'lg': return 'w-8 h-8'
    case 'xl': return 'w-10 h-10'
    case 'md':
    default: return 'w-6 h-6'
  }
})
</script>
