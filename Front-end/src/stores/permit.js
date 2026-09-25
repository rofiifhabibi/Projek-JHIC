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
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get('/student/teachers');
        this.teachersList = res.data.data;
      } catch (err) {
        this.error = 'Gagal memuat daftar guru.';
        console.error('Failed to fetch teachers:', err);
      } finally {
        this.loading = false;
      }
    },
    async submitPermit(payload) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.post('/student/permits', payload);
        await this.fetchActivePermit();
        return res.data;
      } catch (err) {
        this.error = err.response?.data?.message || 'Gagal mengajukan izin.';
        throw err;
      } finally {
        this.loading = false;
      }
    },
    async fetchActivePermit() {
      try {
        const res = await api.get('/student/permits/active');
        this.activePermit = res.data.data;
        this.error = null;
      } catch (err) {
        this.activePermit = null;
      }
    },
    async fetchPendingApprovals() {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get('/teacher/permits/pending');
        this.pendingApprovals = res.data.data;
      } catch (err) {
        this.error = 'Gagal memuat antrean persetujuan izin.';
        console.error('Failed to fetch pending approvals:', err);
      } finally {
        this.loading = false;
      }
    },
    async fetchTeacherMonitoring() {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get('/teacher/monitoring');
        this.monitoringData = res.data.data;
      } catch (err) {
        this.error = 'Gagal memuat data monitoring.';
        console.error('Failed to fetch monitoring:', err);
      } finally {
        this.loading = false;
      }
    },
    async approvePermit(permitId) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.patch(`/teacher/permits/${permitId}/approve`);
        await Promise.all([
          this.fetchPendingApprovals(),
          this.fetchTeacherMonitoring()
        ]);
        return res.data;
      } catch (err) {
        this.error = err.response?.data?.message || 'Gagal menyetujui izin.';
        throw err;
      } finally {
        this.loading = false;
      }
    },
    async resolvePermit(permitId, action) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.patch(`/teacher/permits/${permitId}/resolve`, { action });
        await Promise.all([
          this.fetchPendingApprovals(),
          this.fetchTeacherMonitoring()
        ]);
        return res.data;
      } catch (err) {
        this.error = err.response?.data?.message || 'Gagal memperbarui status izin.';
        throw err;
      } finally {
        this.loading = false;
      }
    },
    async scanQrToken(qrToken) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.post('/satpam/scan', { qr_token: qrToken });
        this.scanResult = { success: true, ...res.data };
        return res.data;
      } catch (err) {
        this.scanResult = { success: false, message: err.response?.data?.message || 'Validasi Gagal' };
        this.error = this.scanResult.message;
        throw err;
      } finally {
        this.loading = false;
      }
    }
  }
})
