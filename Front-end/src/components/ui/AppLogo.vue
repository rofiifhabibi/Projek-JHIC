<template>
  <div
    class="inline-flex items-center justify-center shrink-0 transition-all select-none"
    :class="containerClasses"
  >
    <!-- Default / StudentCare Logo: School Shield Crest with Open Book -->
    <svg
      v-if="variant === 'default' || variant === 'sc'"
      :class="iconSizeClass"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      stroke-width="1.8"
      stroke-linecap="round"
      stroke-linejoin="round"
      aria-label="Logo StudentCare"
    >
      <path d="M12 2L4 5V11C4 16.5 7.4 21.6 12 23C16.6 21.6 20 16.5 20 11V5L12 2Z" fill="currentColor" fill-opacity="0.15" />
      <path d="M12 8.5V16" />
      <path d="M12 16C10 14.5 7.5 14.5 5.5 15.5V9.5C7.5 8.5 10 8.5 12 10" />
      <path d="M12 16C14 14.5 16.5 14.5 18.5 15.5V9.5C16.5 8.5 14 8.5 12 10" />
    </svg>

    <!-- Guru / Teacher Variant -->
    <svg
      v-else-if="variant === 'teacher' || variant === 'gp'"
      :class="iconSizeClass"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      stroke-width="1.8"
      stroke-linecap="round"
      stroke-linejoin="round"
      aria-label="Logo Portal Guru"
    >
      <path d="M22 10L12 5L2 10L12 15L22 10Z" fill="currentColor" fill-opacity="0.15" />
      <path d="M6 12V17C6 18.5 8.7 20 12 20C15.3 20 18 18.5 18 17V12" />
      <path d="M22 10V16" />
    </svg>

    <!-- BK / Counseling Variant -->
    <svg
      v-else-if="variant === 'bk'"
      :class="iconSizeClass"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      stroke-width="1.8"
      stroke-linecap="round"
      stroke-linejoin="round"
      aria-label="Logo Portal BK"
    >
      <path d="M12 2L4 5V11C4 16.5 7.4 21.6 12 23C16.6 21.6 20 16.5 20 11V5L12 2Z" fill="currentColor" fill-opacity="0.15" />
      <path d="M12 15.5L8.5 12C7.5 11 7.5 9.5 8.5 8.5C9.5 7.5 11 7.5 12 8.5C13 7.5 14.5 7.5 15.5 8.5C16.5 9.5 16.5 11 15.5 12L12 15.5Z" fill="currentColor" fill-opacity="0.25" />
    </svg>

    <!-- Satpam / Security Variant -->
    <svg
      v-else-if="variant === 'satpam' || variant === 'sp'"
      :class="iconSizeClass"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      stroke-width="1.8"
      stroke-linecap="round"
      stroke-linejoin="round"
      aria-label="Logo Pos Satpam"
    >
      <path d="M12 2L3 6V12C3 17.5 6.8 22.3 12 23.5C17.2 22.3 21 17.5 21 12V6L12 2Z" fill="currentColor" fill-opacity="0.15" />
      <path d="M9 12L11 14L15 10" stroke-width="2" />
    </svg>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: {
    type: String,
    default: 'default', // 'default' | 'sc' | 'teacher' | 'bk' | 'satpam'
  },
  size: {
    type: String,
    default: 'md', // 'sm' | 'md' | 'lg' | 'xl'
  },
  theme: {
    type: String,
    default: 'primary', // 'primary' | 'white-trans' | 'dark' | 'emerald'
  }
})

const containerClasses = computed(() => {
  const sizes = {
    sm: 'w-8 h-8 rounded-lg',
    md: 'w-10 h-10 rounded-xl',
    lg: 'w-12 h-12 rounded-2xl',
    xl: 'w-14 h-14 rounded-2xl'
  }
  const themes = {
    primary: 'bg-[#355245] text-white shadow-xs border border-white/10',
    'white-trans': 'bg-white/15 text-white border border-white/20 shadow-xs',
    dark: 'bg-slate-900 border border-slate-800 text-emerald-400 shadow-xs',
    emerald: 'bg-[#1c2e26]/80 text-emerald-300 border border-emerald-500/30 shadow-xs'
  }
  return `${sizes[props.size] || sizes.md} ${themes[props.theme] || themes.primary}`
})

const iconSizeClass = computed(() => {
  switch (props.size) {
    case 'sm': return 'w-4 h-4'
    case 'lg': return 'w-7 h-7'
    case 'xl': return 'w-8 h-8'
    case 'md':
    default: return 'w-5 h-5'
  }
})
</script>
