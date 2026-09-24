import { defineStore } from 'pinia'
import router from '@/router'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: null,
    user: null, // { name: 'Budi', role: 'student', nis: '12345' }
  }),
  getters: {
    isAuthenticated: (state) => !!state.user,
    userRole: (state) => state.user?.role || null,
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
    login(role) {
      // Mock login based on role selected
      this.token = 'mock-token-123';
      this.user = { name: 'Mock User', role: role, id: 1 };
      router.push(this.defaultRedirectRoute);
    },
    logout() {
      this.token = null;
      this.user = null;
      router.push({ name: 'login' });
    }
  }
})
