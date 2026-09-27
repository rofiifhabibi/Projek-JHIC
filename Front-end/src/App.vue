<template>
  <ToastNotification />
  <router-view v-slot="{ Component }">
    <transition name="page-fade" mode="out-in">
      <component :is="Component" />
    </transition>
  </router-view>
</template>

<script setup>
import ToastNotification from '@/components/ui/ToastNotification.vue'
</script>
