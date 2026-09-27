# 📌 Roadmap & Rencana Fitur Lanjutan — StudentCare (JHIC 2.0)
*Dokumen ini mencatat seluruh hasil diskusi arsitektur, ide inovasi, dan solusi operasional sekolah untuk pengembangan selanjutnya.*

---

## 1. Integrasi Jadwal & Jam Pelajaran (School Time Slots)

### Latar Belakang
Saat ini tabel `schedules` baru memiliki `period_number` (1, 2, 3.. 12) tanpa jam dinding riil. Siswa memilih guru pengampu secara manual via dropdown.

### Rencana Solusi
1. **Pemetaan Jam Pelajaran ke Jam Dinding:**
   * Jam ke-1: 07.00 – 07.45 WIB
   * Jam ke-2: 07.45 – 08.30 WIB
   * *Istirahat 1:* 08.30 – 08.45 WIB
   * Jam ke-3: 08.45 – 09.30 WIB
   * Jam ke-4: 09.30 – 10.15 WIB, dst.
2. **Format Data:** Disediakan seeder / CSV untuk memasukkan jadwal kelas dengan kolom: `class_name`, `day`, `period_number`, `subject_code`, `teacher_username`, `room`.

---

## 2. Konsep "Adaptive Authority" (Guru Mapel Saat Ini)

### Latar Belakang
* Sistem 100% manual rentan kecurangan siswa (sengaja memilih guru yang gampang menyetujui).
* Sistem 100% kaku otomatis akan macet saat guru berhalangan hadir / ada guru pengganti.

### Rencana Solusi (Hibrida)
1. **Default: Auto-Detect Cerdas**
   * Saat siswa membuka form izin, sistem otomatis membaca kelas siswa, hari ini, dan jam saat ini.
   * Field guru pengampu langsung otomatis terisi guru yang sedang mengajar di kelasnya (dengan label: *`[Rekomendasi Jadwal Saat Ini]`*).
2. **Manual Fallback / Override Penyelamat**
   * Terdapat opsi tombol kecil: *`[Ubah ke Guru Lain / Guru Pengganti]`*.
   * Jika guru asli sakit/rapat/jam kosong, siswa tetap bisa memilih guru piket secara manual tanpa terhalang sistem.

---

## 3. Konsep "Cross-Period Co-Monitoring" (Guru Mapel Selanjutnya)

### Latar Belakang
Jika siswa izin dengan durasi panjang (misal 60–90 menit), izinnya akan menyeberang ke jam pelajaran berikutnya yang diajar oleh guru lain.

### Rencana Solusi (Bukan Double-Blocking Approval)
1. **Single Authority Melepas Siswa:**
   * Persetujuan tetap dilakukan oleh **Guru Jam Saat Ini** agar siswa tidak tertahan menunggu 2 guru membuka HP bersamaan.
2. **Auto-Notification / Co-Awareness:**
   * Sistem mendeteksi durasi izin yang melintasi jam berikutnya.
   * Di dasbor **Guru Mapel Selanjutnya**, nama siswa otomatis muncul di daftar pemantauan dengan badge khusus:
     > 🔄 *“Izin Lintas Jam (Disetujui oleh: Pak Ahmad Dahlan - PWPB)”*
   * Guru berikutnya langsung tahu siswa belum hadir karena izin resmi dari jam sebelumnya, lengkap dengan estimasi jam kembali.

---

## 4. Konsep "Emergency & Anomaly Bypass" via BK

### Latar Belakang
Bagaimana jika jadwal sekolah hari itu mendadak berubah? Upacara bendera molor, ada jam kosong massal, class meeting, atau siswa pingsan/sakit di UKS?

### Rencana Solusi (Master Authority)
Guru BK memiliki otoritas horizontal (lintas kelas dan lintas jam pelajaran) untuk menangani anomali dan kondisi krisis melalui 2 jalur:

1. **Jalur Self-Service Siswa (Izin Darurat / Non-KBM):**
   * Di form siswa ada opsi: *`Izin Darurat / BK (Sakit, Upacara, Jam Kosong)`*.
   * Form mengabaikan guru mapel dan permohonan langsung masuk ke antrean **Pusat Data BK**.
2. **Jalur Direct Issuance (Diterbitkan Langsung oleh Guru BK):**
   * Siswa yang sakit parah di UKS atau pingsan saat upacara **tidak memegang HP**.
   * Guru BK dapat langsung menerbitkan izin pulang darurat dari Konsol BK:
     * Pilih nama siswa → Masukkan alasan (Sakit UKS / Penjemputan Ortu) → Klik *“Terbitkan Tiket Gerbang”*.
     * Tiket QR Code langsung aktif seketika agar satpam gerbang bisa memindainya saat orang tua menjemput.

---

## 5. Poin Pitching & Nilai Jual untuk Juri JHIC 2026

Saat presentasi di depan dewan juri, sampaikan argumen ini:
> *"Banyak sistem digitalisasi sekolah gagal karena berasumsi kondisi KBM selalu berjalan sempurna 100%. StudentCare dirancang dengan **Real-World Operational Resilience**: mengusung **Adaptive Authority** yang memadukan deteksi jadwal otomatis dengan fleksibilitas guru pengganti, serta **Emergency Bypass via BK** yang menjamin penanganan siswa darurat tidak pernah terhambat oleh birokrasi jadwal."*
