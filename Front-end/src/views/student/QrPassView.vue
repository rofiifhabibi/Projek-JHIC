<template>
  <div class="bg-white rounded-2xl border border-border p-6 shadow-sm flex flex-col items-center text-center">
    <div class="mb-2">
      <span class="bg-status-active text-white text-xs px-3 py-1 rounded-full font-medium">IZIN AKTIF</span>
    </div>
    <h2 class="text-xl font-bold text-gray-800 mb-1">E-Permit Pass</h2>
    <p class="text-sm text-gray-500 mb-6">Tunjukkan QR Code ini ke Satpam gerbang.</p>
    
    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-6">
      <qrcode-vue value="mock-token-uuid-12345" :size="200" level="H" />
    </div>

    <div class="w-full bg-surface-soft p-4 rounded-xl border border-secondary">
      <p class="text-sm font-medium text-primary mb-1">Sisa Waktu</p>
      <p class="text-3xl font-bold text-primary font-mono">29:59</p>
    </div>
  </div>
</template>

<script setup>
import QrcodeVue from 'qrcode.vue'
</script>
