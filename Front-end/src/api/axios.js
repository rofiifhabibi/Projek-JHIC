import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

const getBaseUrl = () => {
  if (import.meta.env.VITE_API_BASE_URL) {
    return import.meta.env.VITE_API_BASE_URL
  }
  // Di production (dihosting satu domain dengan backend atau reverse proxy HTTPS)
  if (import.meta.env.PROD) {
    return '/api'
  }
  // Di development lokal (Vite dev server terpisah port)
  const host = typeof window !== 'undefined' ? window.location.hostname : '127.0.0.1'
  return `http://${host}:8000/api`
}

const api = axios.create({
  baseURL: getBaseUrl(),
  timeout: 15000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Request Interceptor: Sisipkan Bearer Token Sanctum
api.interceptors.request.use((config) => {
  const authStore = useAuthStore()
  if (authStore.token) {
    config.headers.Authorization = `Bearer ${authStore.token}`
  }
  return config;
})

// Response Interceptor: Handling Global Errors (401, 403, 422, 500)
api.interceptors.response.use(
  (response) => response,
  (error) => {
    const authStore = useAuthStore()
    const requestUrl = error.config?.url || ''
    // Jangan trigger logout otomatis jika error 401 berasal dari percobaan gagal submit /login
    if (error.response?.status === 401 && !requestUrl.includes('/login')) {
      authStore.logout()
    }
    return Promise.reject(error)
  }
)

export default api
