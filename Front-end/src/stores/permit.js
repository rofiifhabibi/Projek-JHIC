import { defineStore } from 'pinia'
import api from '@/api/axios'

export const usePermitStore = defineStore('permit', {
  state: () => ({
    activePermit: null,
    teachersList: [],
    pendingApprovals: [],
    monitoringData: { day: 'Senin', classes: [], active_permits: [] },
    scanResult: null,
    loading: false,
    error: null,
  }),
  actions: {
    async fetchTeachers() {
      try {
        const res = await api.get('/student/teachers');
        this.teachersList = res.data.data;
      } catch (err) {
        console.error('Failed to fetch teachers:', err);
      }
    },
    async submitPermit(payload) {
      this.loading = true;
      try {
        const res = await api.post('/student/permits', payload);
        await this.fetchActivePermit();
        return res.data;
      } catch (err) {
        throw err;
      } finally {
        this.loading = false;
      }
    },
    async fetchActivePermit() {
      try {
        const res = await api.get('/student/permits/active');
        this.activePermit = res.data.data;
      } catch (err) {
        this.activePermit = null;
      }
    },
    async fetchPendingApprovals() {
      try {
        const res = await api.get('/teacher/permits/pending');
        this.pendingApprovals = res.data.data;
      } catch (err) {
        console.error('Failed to fetch pending approvals:', err);
      }
    },
    async fetchTeacherMonitoring() {
      try {
        const res = await api.get('/teacher/monitoring');
        this.monitoringData = res.data.data;
      } catch (err) {
        console.error('Failed to fetch monitoring:', err);
      }
    },
    async approvePermit(permitId) {
      try {
        const res = await api.patch(`/teacher/permits/${permitId}/approve`);
        await this.fetchPendingApprovals();
        await this.fetchTeacherMonitoring();
        return res.data;
      } catch (err) {
        throw err;
      }
    },
    async resolvePermit(permitId, action) {
      try {
        const res = await api.patch(`/teacher/permits/${permitId}/resolve`, { action });
        await this.fetchPendingApprovals();
        await this.fetchTeacherMonitoring();
        return res.data;
      } catch (err) {
        throw err;
      }
    },
    async scanQrToken(qrToken) {
      this.loading = true;
      try {
        const res = await api.post('/satpam/scan', { qr_token: qrToken });
        this.scanResult = { success: true, ...res.data };
        return res.data;
      } catch (err) {
        this.scanResult = { success: false, message: err.response?.data?.message || 'Validasi Gagal' };
        throw err;
      } finally {
        this.loading = false;
      }
    }
  }
})
