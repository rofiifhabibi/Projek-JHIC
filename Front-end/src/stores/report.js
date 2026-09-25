import { defineStore } from 'pinia'
import api from '@/api/axios'

export const useReportStore = defineStore('report', {
  state: () => ({
    myReports: [],
    kanban: { OPEN: [], IN_PROGRESS: [], RESOLVED: [] },
    metrics: { reports_open: 0, reports_in_progress: 0, reports_resolved: 0, permits_active_today: 0, permits_overdue_today: 0 },
    globalMobility: [],
    loading: false,
  }),
  actions: {
    async submitReport(payload) {
      this.loading = true;
      try {
        const res = await api.post('/student/reports', payload);
        return res.data;
      } catch (err) {
        throw err;
      } finally {
        this.loading = false;
      }
    },
    async fetchMyReports() {
      try {
        const res = await api.get('/student/reports/my');
        this.myReports = res.data.data;
      } catch (err) {
        console.error(err);
      }
    },
    async fetchBkMetrics() {
      try {
        const res = await api.get('/bk/metrics');
        this.metrics = res.data.data;
      } catch (err) {
        console.error(err);
      }
    },
    async fetchKanban() {
      try {
        const res = await api.get('/bk/kanban');
        this.kanban = res.data.data;
      } catch (err) {
        console.error(err);
      }
    },
    async updateReportStatus(id, status) {
      try {
        const res = await api.patch(`/bk/reports/${id}/status`, { status });
        await this.fetchKanban();
        await this.fetchBkMetrics();
        return res.data;
      } catch (err) {
        throw err;
      }
    },
    async addInvestigationNote(id, notes) {
      try {
        const res = await api.post(`/bk/reports/${id}/investigate`, { notes });
        await this.fetchKanban();
        return res.data;
      } catch (err) {
        throw err;
      }
    },
    async fetchGlobalMobility() {
      try {
        const res = await api.get('/bk/global-monitor');
        this.globalMobility = res.data.data;
      } catch (err) {
        console.error(err);
      }
    }
  }
})
