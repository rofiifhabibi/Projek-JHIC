<script setup>
import { ref, onMounted } from 'vue'

const message = ref('Loading...')

onMounted(async () => {
  const response = await fetch('http://127.0.0.1:8000/api/test')
  const data = await response.json()

  message.value = data.message
})
</script>

<template>
  <h1>{{ message }}</h1>
</template>