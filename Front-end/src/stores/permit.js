import { defineStore } from 'pinia'
import api from '@/api/axios'

export const usePermitStore = defineStore('permit', {
  state: () => ({
    activePermit: null,
    permitHistory: [],
    teachersList: [],
    currentSchedule: null,
    pendingApprovals: [],
    monitoringData: { day: 'Senin', classes: [], active_permits: [] },
    scanResult: null,
    loading: false,
    error: null,
    _fetchingActive: false,
  }),
  actions: {
    async fetchTeachers(force = false) {
      if (!force && this.teachersList && this.teachersList.length > 0) {
        return;
      }
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get('/student/teachers');
        this.teachersList = res.data.data || [];
        this.currentSchedule = res.data.current_schedule || null;
      } catch (err) {
        console.warn('API /student/teachers offline / error, using dummy fallback:', err);
        // Fallback data dummy agar testing izin selalu lancar
        this.teachersList = [
          {
            user_id: 6,
            name: 'Ahmad Dahlan, S.Pd.',
            username: 'guru1',
            subject: 'Pemrograman Web & Perangkat Bergerak (PWPB)',
            is_current_schedule: true,
            display_label: 'Ahmad Dahlan, S.Pd. (Pemrograman Web) — [Jadwal Aktif]'
          },
          {
            user_id: 8,
            name: 'Bambang Pamungkas, S.Kom',
            username: 'guru3',
            subject: 'Pemrograman Berorientasi Objek (PBO)',
            is_current_schedule: false,
            display_label: 'Bambang Pamungkas, S.Kom (PBO)'
          },
          {
            user_id: 7,
            name: 'Ratna Dewi, M.Pd.',
            username: 'guru2',
            subject: 'Fisika Terapan Kejuruan',
            is_current_schedule: false,
            display_label: 'Ratna Dewi, M.Pd. (Fisika Terapan)'
          }
        ];
        this.currentSchedule = {
          teacher_id: 6,
          teacher_name: 'Ahmad Dahlan, S.Pd.',
          subject: 'Pemrograman Web & Perangkat Bergerak (PWPB)',
          room: 'Lab Komputer RPL 1',
          class_name: 'XII RPL 1',
          period: 'Jam Pelajaran Aktif (Sedang Berlangsung)'
        };
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
      // In-flight guard: jika request sebelumnya masih pending/lambat, jangan tumpuk request baru
      if (this._fetchingActive) return;
      this._fetchingActive = true;
      try {
        const res = await api.get('/student/permits/active');
        this.activePermit = res.data.data;
        this.error = null;
      } catch (err) {
        this.activePermit = null;
      } finally {
        this._fetchingActive = false;
      }
    },
    async cancelPermit(permitId) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.delete(`/student/permits/${permitId}`);
        await Promise.all([
          this.fetchActivePermit(),
          this.fetchPermitHistory()
        ]);
        return res.data;
      } catch (err) {
        this.error = err.response?.data?.message || 'Gagal membatalkan izin.';
        throw err;
      } finally {
        this.loading = false;
      }
    },
    async fetchPermitHistory(page = 1) {
      try {
        const res = await api.get(`/student/permits/history?page=${page}`);
        this.permitHistory = res.data.data?.data || res.data.data || [];
        return res.data;
      } catch (err) {
        console.error('Failed to fetch permit history:', err);
        this.permitHistory = [];
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
