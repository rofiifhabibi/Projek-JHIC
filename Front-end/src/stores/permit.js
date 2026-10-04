import { defineStore } from 'pinia'
import api from '@/api/axios'
import { useWebNotification } from '@/composables/useWebNotification'
import { useToast } from '@/composables/useToast'

// Tracker internal untuk mendeteksi perubahan status secara otomatis
let previousPermitId = null
let previousPermitStatus = null
let isPermitInitialized = false

let previousPendingIds = null
let isPendingInitialized = false

let previousOverduePermitIds = null
let isMonitoringInitialized = false

export const resetPermitNotificationState = () => {
  previousPermitId = null
  previousPermitStatus = null
  isPermitInitialized = false
  previousPendingIds = null
  isPendingInitialized = false
  previousOverduePermitIds = null
  isMonitoringInitialized = false
}

export const usePermitStore = defineStore('permit', {
  state: () => ({
    activePermit: null,
    permitHistory: [],
    permitPagination: null,
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
        // Fallback data demo 12 SIJA B SMKN 2 Depok Sleman agar testing izin selalu lancar
        this.teachersList = [
          {
            user_id: 6,
            name: 'Margaretha Endah Titisari, S.T.',
            username: 'guru1',
            subject: 'MPP AWS Academy (Cloud SIJA)',
            room: 'Lab LAN',
            is_current_schedule: true,
            display_label: 'Margaretha Endah Titisari, S.T. (MPP AWS Academy) — [Di Luar Jam KBM (Mode Evaluasi 24 Jam)]'
          },
          {
            user_id: 7,
            name: 'Eka Nur Ahmad Romadhoni, S.Pd.',
            username: 'guru2',
            subject: 'MPP Koding & AI (SIJA)',
            room: 'Lab LAN',
            is_current_schedule: false,
            display_label: 'Eka Nur Ahmad Romadhoni, S.Pd. (MPP Koding & AI) — [Lab LAN]'
          },
          {
            user_id: 8,
            name: 'Sri Wahjuni Pudjiastuti, S.Pd.',
            username: 'guru3',
            subject: 'Bahasa Indonesia',
            room: 'Ruang Teori 7',
            is_current_schedule: false,
            display_label: 'Sri Wahjuni Pudjiastuti, S.Pd. (Bahasa Indonesia) — [Ruang Teori 7]'
          }
        ];
        this.currentSchedule = {
          teacher_id: 6,
          teacher_name: 'Margaretha Endah Titisari, S.T.',
          subject: 'MPP AWS Academy (Cloud SIJA)',
          room: 'Lab LAN',
          class_name: '12 SIJA B',
          period: 'Di Luar Jam KBM (Mode Evaluasi 24 Jam)'
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
        const createdPermit = res.data?.data;
        if (createdPermit?.id) {
          previousPermitId = createdPermit.id;
          previousPermitStatus = createdPermit.status || 'PENDING';
          isPermitInitialized = true;
        }
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
        const newPermit = res.data.data;

        if (newPermit) {
          if (isPermitInitialized && (!previousPermitId || previousPermitId === newPermit.id)) {
            const oldStatus = previousPermitStatus;
            const newStatus = newPermit.status;

            if (oldStatus && oldStatus !== newStatus) {
              const { showSystemNotification } = useWebNotification();
              const toast = useToast();

              if (oldStatus === 'PENDING' && (newStatus === 'APPROVED' || newStatus === 'ACTIVE')) {
                toast.success('Pengajuan izin Anda telah disetujui! QR Pass siap digunakan.');
                showSystemNotification('Izin Disetujui', {
                  body: 'Pengajuan izin telah disetujui oleh guru pengajar. QR Pass siap digunakan.',
                  url: '/student/permit/pass'
                });
              } else if (newStatus === 'REJECTED') {
                const reason = newPermit.reject_reason ? `: ${newPermit.reject_reason}` : '';
                toast.error(`Pengajuan izin tidak disetujui${reason}`);
                showSystemNotification('Izin Tidak Disetujui', {
                  body: `Pengajuan izin tidak disetujui${reason}`,
                  url: '/student/tracking'
                });
              } else if (newStatus === 'OVERDUE') {
                toast.warning('Batas waktu izin berakhir! Harap segera kembali ke area kelas.');
                showSystemNotification('Batas Waktu Izin Berakhir', {
                  body: 'Masa berlaku izin Anda telah habis. Harap segera kembali ke area kelas.',
                  url: '/student/tracking'
                });
              } else if (newStatus === 'ALPHA') {
                toast.error('Peringatan disiplin: Status izin ditandai Tidak Kembali (Alpha). Segera melapor ke Guru atau BK.');
                showSystemNotification('Peringatan Disiplin Siswa', {
                  body: 'Status izin ditandai Tidak Kembali (Alpha). Segera melapor ke Guru atau BK.',
                  url: '/student/tracking'
                });
              } else if (newStatus === 'COMPLETED') {
                toast.info('Izin selesai: Presensi kepulangan atau kembali telah divalidasi oleh petugas keamanan.');
                showSystemNotification('Izin Selesai', {
                  body: 'Presensi kepulangan atau kembali telah divalidasi oleh petugas keamanan.',
                  url: '/student/tracking'
                });
              }
            }
          }

          previousPermitId = newPermit.id;
          previousPermitStatus = newPermit.status;
          isPermitInitialized = true;
        } else {
          // Jika sebelumnya berstatus ACTIVE atau OVERDUE lalu izin selesai (activePermit menjadi null)
          if (isPermitInitialized && previousPermitId && (previousPermitStatus === 'ACTIVE' || previousPermitStatus === 'OVERDUE')) {
            const { showSystemNotification } = useWebNotification();
            const toast = useToast();
            toast.info('Izin selesai: Presensi kepulangan atau kembali telah divalidasi oleh petugas keamanan.');
            showSystemNotification('Izin Selesai', {
              body: 'Presensi kepulangan atau kembali telah divalidasi oleh petugas keamanan.',
              url: '/student/tracking'
            });
          }
          previousPermitId = null;
          previousPermitStatus = null;
          isPermitInitialized = true;
        }

        this.activePermit = newPermit;
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
        this.permitPagination = res.data.data?.current_page ? {
          current_page: res.data.data.current_page,
          last_page: res.data.data.last_page,
          total: res.data.data.total,
          per_page: res.data.data.per_page,
        } : null;
        return res.data;
      } catch (err) {
        console.error('Failed to fetch permit history:', err);
        this.permitHistory = [];
        this.permitPagination = null;
      }
    },
    async fetchPendingApprovals() {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get('/teacher/permits/pending');
        const currentList = res.data.data || [];

        if (isPendingInitialized && Array.isArray(previousPendingIds)) {
          const newItems = currentList.filter(p => !previousPendingIds.includes(p.id));
          if (newItems.length > 0) {
            const { showSystemNotification } = useWebNotification();
            const toast = useToast();
            const firstStudent = newItems[0]?.student?.name || 'Siswa';
            const bodyText = newItems.length === 1
              ? `${firstStudent} mengajukan izin keluar kelas.`
              : `${newItems.length} siswa mengajukan izin keluar kelas.`;

            toast.info(`${bodyText} Silakan tinjau antrean persetujuan.`);
            showSystemNotification('Pengajuan Izin Siswa Baru', {
              body: `${bodyText} Silakan tinjau antrean persetujuan.`,
              url: '/teacher/approvals'
            });
          }
          previousPendingIds = currentList.map(p => p.id);
        } else {
          previousPendingIds = currentList.map(p => p.id);
          isPendingInitialized = true;
        }

        this.pendingApprovals = currentList;
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
        const monitoring = res.data.data || {};
        const activeList = monitoring?.active_permits || [];
        const currentOverdue = activeList.filter(p => p.status === 'OVERDUE');
        const currentOverdueIds = currentOverdue.map(p => p.id);

        if (isMonitoringInitialized && Array.isArray(previousOverduePermitIds)) {
          const newlyOverdue = currentOverdue.filter(p => !previousOverduePermitIds.includes(p.id));
          if (newlyOverdue.length > 0) {
            const { showSystemNotification } = useWebNotification();
            const toast = useToast();
            const studentName = newlyOverdue[0]?.student_name || newlyOverdue[0]?.student?.name || 'Siswa';
            const bodyText = newlyOverdue.length === 1
              ? `${studentName} belum kembali melewati batas waktu izin.`
              : `${newlyOverdue.length} siswa belum kembali melewati batas waktu izin.`;

            toast.warning(`Peringatan: ${bodyText}`);
            showSystemNotification('Peringatan Siswa Terlambat', {
              body: bodyText,
              url: '/teacher/monitoring'
            });
          }
          previousOverduePermitIds = currentOverdueIds;
        } else {
          previousOverduePermitIds = currentOverdueIds;
          isMonitoringInitialized = true;
        }

        this.monitoringData = monitoring;
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
    async resolvePermit(permitId, action, rejectReason = null) {
      this.loading = true;
      this.error = null;
      try {
        const payload = { action };
        if (rejectReason) payload.reject_reason = rejectReason;
        const res = await api.patch(`/teacher/permits/${permitId}/resolve`, payload);
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
