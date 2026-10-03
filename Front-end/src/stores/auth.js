import { defineStore } from 'pinia'
import api from '@/api/axios'
import router from '@/router'
import { usePermitStore } from '@/stores/permit'
import { useReportStore } from '@/stores/report'

// Safe storage wrapper to prevent crashes in private browsing or restrictive WebViews
const safeStorage = {
  get(key) {
    try {
      return typeof window !== 'undefined' ? localStorage.getItem(key) : null
    } catch {
      return null
    }
  },
  set(key, value) {
    try {
      if (typeof window !== 'undefined') localStorage.setItem(key, value)
    } catch {}
  },
  remove(key) {
    try {
      if (typeof window !== 'undefined') localStorage.removeItem(key)
    } catch {}
  }
}

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: safeStorage.get('token') || null,
    user: (() => {
      try {
        const stored = safeStorage.get('user')
        return stored ? JSON.parse(stored) : null
      } catch (e) {
        safeStorage.remove('user')
        return null
      }
    })(),
    loading: false,
    error: null,
  }),
  getters: {
    isAuthenticated: (state) => !!state.token,
    userRole: (state) => state.user?.role || null,
    userName: (state) => state.user?.name || '',
    userClass: (state) => state.user?.class_name || '',
    defaultRedirectRoute: (state) => {
      if (!state.user) return { name: 'login' };
      switch (state.user.role) {
        case 'student': return { name: 'student-dashboard' };
        case 'teacher': return { name: 'teacher-monitoring' };
        case 'satpam': return { name: 'satpam-scanner' };
        case 'bk': return { name: 'bk-kanban' };
        default: return { name: 'login' };
      }
    }
  },
  actions: {
    async login(identity, password) {
      this.loading = true;
      this.error = null;
      try {
        const response = await api.post('/login', { identity, password });
        const data = response.data.data;
        this.token = data.token;
        this.user = data.user;
        safeStorage.set('token', this.token);
        safeStorage.set('user', JSON.stringify(this.user));
        router.push(this.defaultRedirectRoute);
      } catch (err) {
        this.error = err.response?.data?.message || 'Gagal login. Periksa kembali NIS/Email dan kata sandi Anda.';
        throw err;
      } finally {
        this.loading = false;
      }
    },
    async fetchUser() {
      if (!this.token) return;
      try {
        const response = await api.get('/me');
        this.user = response.data.data;
        safeStorage.set('user', JSON.stringify(this.user));
      } catch (err) {
        this.logout();
      }
    },
    async logout() {
      try {
        if (this.token) {
          await api.post('/logout');
        }
      } catch (e) {}
      this.token = null;
      this.user = null;
      safeStorage.remove('token');
      safeStorage.remove('user');

      // Bersihkan seluruh store Pinia agar data akun sebelumnya tidak tersisa di memori
      try {
        const permitStore = usePermitStore();
        permitStore.$reset?.();
        const reportStore = useReportStore();
        reportStore.$reset?.();
      } catch (e) {}

      router.push({ name: 'login' });
    }
  }
})
