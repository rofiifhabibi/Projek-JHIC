import { defineStore } from 'pinia'
import api from '@/api/axios'
import { useWebNotification } from '@/composables/useWebNotification'

let previousOpenReportIds = null
let isKanbanInitialized = false

// Map report id -> { status, responsesCount }
const previousReportSnapshots = new Map()
let isMyReportsInitialized = false

export const resetReportNotificationState = () => {
  previousOpenReportIds = null
  isKanbanInitialized = false
  previousReportSnapshots.clear()
  isMyReportsInitialized = false
}

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
        const reports = res.data.data || [];

        if (isMyReportsInitialized) {
          let hasUpdate = false;
          for (const r of reports) {
            const prev = previousReportSnapshots.get(r.id);
            if (prev) {
              const currentResponsesCount = Array.isArray(r.responses) ? r.responses.length : 0;
              if (prev.status !== r.status || currentResponsesCount > prev.responsesCount) {
                hasUpdate = true;
                break;
              }
            }
          }

          if (hasUpdate) {
            const { showSystemNotification } = useWebNotification();
            showSystemNotification('Pembaruan Laporan Konseling', {
              body: 'Guru BK telah memberikan tanggapan atau pembaruan status pada laporan Anda.',
              url: '/student/tracking'
            });
          }
        }

        previousReportSnapshots.clear();
        for (const r of reports) {
          previousReportSnapshots.set(r.id, {
            status: r.status,
            responsesCount: Array.isArray(r.responses) ? r.responses.length : 0
          });
        }
        isMyReportsInitialized = true;

        this.myReports = reports;
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
        const currentKanban = res.data.data;
        const openReports = currentKanban?.OPEN || [];
        const currentOpenIds = openReports.map(r => r.id);

        if (isKanbanInitialized && Array.isArray(previousOpenReportIds)) {
          const newOpen = openReports.filter(r => !previousOpenReportIds.includes(r.id));
          if (newOpen.length > 0) {
            const { showSystemNotification } = useWebNotification();
            const bodyText = newOpen.length === 1
              ? 'Terdapat 1 laporan konseling siswa baru yang perlu ditinjau.'
              : `Terdapat ${newOpen.length} laporan konseling siswa baru yang perlu ditinjau.`;

            showSystemNotification('Laporan Konseling Siswa Baru', {
              body: bodyText,
              url: '/bk/kanban'
            });
          }
          previousOpenReportIds = currentOpenIds;
        } else {
          previousOpenReportIds = currentOpenIds;
          isKanbanInitialized = true;
        }

        this.kanban = currentKanban;
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
    async sendCounselorResponse(id, payload) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.post(`/bk/reports/${id}/respond`, payload);
        await Promise.all([
          this.fetchKanban(),
          this.fetchBkMetrics()
        ]);
        return res.data;
      } catch (err) {
        this.error = err.response?.data?.message || 'Gagal mengirim tanggapan konseling.';
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
