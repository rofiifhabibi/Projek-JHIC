<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Request as PermitRequest;
use App\Models\Report;
use App\Models\Subject;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SEED USERS
        $student1 = User::create(['username' => 'siswa1', 'password' => Hash::make('password'), 'role' => 'student', 'name' => 'Budi Santoso', 'email' => 'budi@student.stembayo.sch.id', 'class_name' => 'XII RPL 1']);
        $student2 = User::create(['username' => 'siswa2', 'password' => Hash::make('password'), 'role' => 'student', 'name' => 'Siti Nurhaliza', 'email' => 'siti@student.stembayo.sch.id', 'class_name' => 'XII RPL 1']);
        $student3 = User::create(['username' => 'siswa3', 'password' => Hash::make('password'), 'role' => 'student', 'name' => 'Andi Saputra', 'email' => 'andi@student.stembayo.sch.id', 'class_name' => 'XII TKJ 2']);
        $student4 = User::create(['username' => 'siswa4', 'password' => Hash::make('password'), 'role' => 'student', 'name' => 'Dewi Lestari', 'email' => 'dewi@student.stembayo.sch.id', 'class_name' => 'XI MM 1']);
        $student5 = User::create(['username' => 'siswa5', 'password' => Hash::make('password'), 'role' => 'student', 'name' => 'Rizky Ramadhan', 'email' => 'rizky@student.stembayo.sch.id', 'class_name' => 'X AK 3']);

        $guru1 = User::create(['username' => 'guru1', 'password' => Hash::make('password'), 'role' => 'teacher', 'name' => 'Ahmad Dahlan, S.Pd.', 'email' => 'ahmad@stembayo.sch.id']);
        $guru2 = User::create(['username' => 'guru2', 'password' => Hash::make('password'), 'role' => 'teacher', 'name' => 'Ratna Dewi, M.Pd.', 'email' => 'ratna@stembayo.sch.id']);
        $guru3 = User::create(['username' => 'guru3', 'password' => Hash::make('password'), 'role' => 'teacher', 'name' => 'Bambang Pamungkas, S.Kom', 'email' => 'bambang@stembayo.sch.id']);

        $satpam1 = User::create(['username' => 'satpam1', 'password' => Hash::make('password'), 'role' => 'satpam', 'name' => 'Joko Widodo (Gerbang Utama)', 'email' => 'satpam1@stembayo.sch.id']);
        $satpam2 = User::create(['username' => 'satpam2', 'password' => Hash::make('password'), 'role' => 'satpam', 'name' => 'Sutarman (Pos II)', 'email' => 'satpam2@stembayo.sch.id']);

        $bk1 = User::create(['username' => 'bk1', 'password' => Hash::make('password'), 'role' => 'bk', 'name' => 'Hani Saraswati, S.Psi (Koordinator BK)', 'email' => 'hani.bk@stembayo.sch.id']);
        $bk2 = User::create(['username' => 'bk2', 'password' => Hash::make('password'), 'role' => 'bk', 'name' => 'Drs. Edi Suwarno', 'email' => 'edi.bk@stembayo.sch.id']);

        // 2. SUBJECTS
        $subj1 = Subject::create(['code' => 'PWPB', 'name' => 'Pemrograman Web & Perangkat Bergerak']);
        $subj2 = Subject::create(['code' => 'PBO', 'name' => 'Pemrograman Berorientasi Objek']);
        $subj3 = Subject::create(['code' => 'FIS', 'name' => 'Fisika Terapan Kejuruan']);

        // 3. SCHEDULES (Covering all school weekdays for robust testing)
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        foreach ($days as $day) {
            Schedule::create(['class_name' => 'XII RPL 1', 'day' => $day, 'period_number' => 1, 'subject_id' => $subj1->subject_id, 'teacher_id' => $guru1->user_id, 'room' => 'Lab RPL 1']);
            Schedule::create(['class_name' => 'XII RPL 1', 'day' => $day, 'period_number' => 3, 'subject_id' => $subj2->subject_id, 'teacher_id' => $guru3->user_id, 'room' => 'Lab RPL 2']);
            Schedule::create(['class_name' => 'XII TKJ 2', 'day' => $day, 'period_number' => 2, 'subject_id' => $subj3->subject_id, 'teacher_id' => $guru2->user_id, 'room' => 'Ruang Teori 4']);
        }

        // 4. PERMIT REQUESTS
        PermitRequest::create([
            'student_id' => $student1->user_id,
            'initial_teacher_id' => $guru1->user_id,
            'type' => 'TEMP',
            'reason' => 'Izin mengambil berkas sertifikat di ruang Tata Usaha',
            'duration_minutes' => 30,
            'status' => 'PENDING',
        ]);

        PermitRequest::create([
            'student_id' => $student2->user_id,
            'initial_teacher_id' => $guru1->user_id,
            'type' => 'TEMP',
            'reason' => 'Izin ke ruang UKS karena sakit kepala',
            'duration_minutes' => 30,
            'status' => 'ACTIVE',
            'qr_token' => 'QR-ACTIVE-XII-RPL1-002',
            'expiry_time' => Carbon::now()->addMinutes(25),
        ]);

        PermitRequest::create([
            'student_id' => $student3->user_id,
            'initial_teacher_id' => $guru2->user_id,
            'type' => 'TEMP',
            'reason' => 'Izin membeli obat di luar gerbang sekolah',
            'duration_minutes' => 15,
            'status' => 'OVERDUE',
            'qr_token' => 'QR-OVERDUE-XII-TKJ2-003',
            'expiry_time' => Carbon::now()->subMinutes(10),
        ]);

        PermitRequest::create([
            'student_id' => $student4->user_id,
            'initial_teacher_id' => $guru1->user_id,
            'type' => 'TEMP',
            'reason' => 'Izin ke kamar mandi / toilet lantai 2',
            'duration_minutes' => 15,
            'status' => 'COMPLETED',
            'expiry_time' => Carbon::now()->subHours(2),
        ]);

        PermitRequest::create([
            'student_id' => $student5->user_id,
            'initial_teacher_id' => $guru3->user_id,
            'type' => 'EXIT_SCHOOL',
            'reason' => 'Izin pulang awal karena demam tinggi (ada konfirmasi orang tua)',
            'duration_minutes' => 0,
            'status' => 'CLOSED',
            'qr_token' => 'QR-EXIT-X-AK3-005',
        ]);

        // 5. CARE REPORTS (BK)
        Report::create([
            'student_id' => $student1->user_id,
            'category' => 'BULLYING',
            'title' => 'Tindakan Pemerasan & Intimidasi di Kantin Belakang',
            'description' => 'Terdapat oknum siswa senior yang sering memaksa meminta uang saku dan mengancam siswa kelas 10 di area lorong kantin belakang saat jam istirahat kedua.',
            'status' => 'OPEN',
        ]);

        Report::create([
            'student_id' => $student2->user_id,
            'category' => 'FACILITY',
            'title' => 'Kerusakan Proyektor & Kipas Angin Ruang Lab RPL 1',
            'description' => 'LCD Proyektor di Lab RPL 1 sering mati mendadak setiap 15 menit dan kabel VGA longgar sehingga menghambat kegiatan praktikum.',
            'status' => 'IN_PROGRESS',
        ]);

        Report::create([
            'student_id' => $student3->user_id,
            'category' => 'ACADEMIC',
            'title' => 'Konseling Remedial & Kesulitan Belajar Materi PBO',
            'description' => 'Siswa memerlukan pendampingan belajar khusus untuk persiapan Sertifikasi Ujian Keahlian Kompetensi RPL.',
            'status' => 'IN_PROGRESS',
        ]);

        Report::create([
            'student_id' => $student4->user_id,
            'category' => 'PERSONAL',
            'title' => 'Sesi Konseling Pengelolaan Stress & Kecemasan',
            'description' => 'Sesi konseling pribadi mengenai kesehatan mental dan manajemen waktu pembelajaran di sekolah.',
            'status' => 'RESOLVED',
        ]);
    }
}
