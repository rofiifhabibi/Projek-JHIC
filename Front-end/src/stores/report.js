import { defineStore } from 'pinia'
import api from '@/api/axios'

export const useReportStore = defineStore('report', {
  state: () => ({
    myReports: [],
    kanban: { OPEN: [], IN_PROGRESS: [], RESOLVED: [] },
    metrics: { reports_open: 0, reports_in_progress: 0, reports_resolved: 0, permits_active_today: 0, permits_overdue_today: 0 },
    globalMobility: [],
    loading: false,
    error: null,
  }),
  actions: {
    async submitReport(payload) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.post('/student/reports', payload);
        return res.data;
      } catch (err) {
        this.error = err.response?.data?.message || 'Gagal mengirim pengaduan.';
        throw err;
      } finally {
        this.loading = false;
      }
    },
    async fetchMyReports() {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get('/student/reports/my');
        this.myReports = res.data.data;
      } catch (err) {
        this.error = 'Gagal memuat riwayat pengaduan.';
        console.error(err);
      } finally {
        this.loading = false;
      }
    },
    async fetchBkMetrics() {
      try {
        const res = await api.get('/bk/metrics');
        this.metrics = res.data.data;
      } catch (err) {
        console.error('Failed to fetch BK metrics:', err);
      }
    },
    async fetchKanban() {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get('/bk/kanban');
        this.kanban = res.data.data;
      } catch (err) {
        this.error = 'Gagal memuat papan kerja konseling.';
        console.error(err);
      } finally {
        this.loading = false;
      }
    },
    async updateReportStatus(id, status) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.patch(`/bk/reports/${id}/status`, { status });
        await Promise.all([
          this.fetchKanban(),
          this.fetchBkMetrics()
        ]);
        return res.data;
      } catch (err) {
        this.error = err.response?.data?.message || 'Gagal memperbarui status pengaduan.';
        throw err;
      } finally {
        this.loading = false;
      }
    },
    async addInvestigationNote(id, notes) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.post(`/bk/reports/${id}/investigate`, { notes });
        await this.fetchKanban();
        return res.data;
      } catch (err) {
        this.error = err.response?.data?.message || 'Gagal menyimpan catatan.';
        throw err;
      } finally {
        this.loading = false;
      }
    },
    async fetchGlobalMobility() {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get('/bk/global-monitor');
        this.globalMobility = res.data.data;
      } catch (err) {
        this.error = 'Gagal memuat monitoring mobilitas.';
        console.error(err);
      } finally {
        this.loading = false;
      }
    }
  }
})
