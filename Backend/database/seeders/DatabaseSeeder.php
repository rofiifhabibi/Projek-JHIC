<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Request as PermitRequest;
use App\Models\Report;
use App\Models\Subject;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // 1. SEED SISWA ASLI 12 SIJA B (SMKN 2 Depok Sleman)
        $student1 = User::updateOrCreate(
            ['username' => 'siswa1'],
            [
                'password' => Hash::make('password'),
                'role' => 'student',
                'name' => 'Muhammad Hafidin',
                'email' => '21872@student.stembayo.sch.id',
                'class_name' => '12 SIJA B'
            ]
        );
        $student2 = User::updateOrCreate(
            ['username' => 'siswa2'],
            [
                'password' => Hash::make('password'),
                'role' => 'student',
                'name' => 'Muhammad Rofiif Arifian',
                'email' => '21875@student.stembayo.sch.id',
                'class_name' => '12 SIJA B'
            ]
        );
        $student3 = User::updateOrCreate(
            ['username' => 'siswa3'],
            [
                'password' => Hash::make('password'),
                'role' => 'student',
                'name' => 'Mahesa Gita Dharma',
                'email' => '21867@student.stembayo.sch.id',
                'class_name' => '12 SIJA B'
            ]
        );
        $student4 = User::updateOrCreate(
            ['username' => 'siswa4'],
            [
                'password' => Hash::make('password'),
                'role' => 'student',
                'name' => 'Vincentius Rafa Christa P',
                'email' => '21895@student.stembayo.sch.id',
                'class_name' => '12 SIJA B'
            ]
        );
        $student5 = User::updateOrCreate(
            ['username' => 'siswa5'],
            [
                'password' => Hash::make('password'),
                'role' => 'student',
                'name' => 'Yusuf Miftahul Rizqi',
                'email' => '21897@student.stembayo.sch.id',
                'class_name' => '12 SIJA B'
            ]
        );

        // 2. SEED 10 GURU ASLI 12 SIJA B
        $guru1 = User::updateOrCreate(
            ['username' => 'guru1'],
            [
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'name' => 'Margaretha Endah Titisari, S.T.',
                'email' => 'mrg@stembayo.sch.id'
            ]
        );
        $guru2 = User::updateOrCreate(
            ['username' => 'guru2'],
            [
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'name' => 'Eka Nur Ahmad Romadhoni, S.Pd.',
                'email' => 'eka@stembayo.sch.id'
            ]
        );
        $guru3 = User::updateOrCreate(
            ['username' => 'guru3'],
            [
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'name' => 'Sri Wahjuni Pudjiastuti, S.Pd.',
                'email' => 'yun@stembayo.sch.id'
            ]
        );
        $guru4 = User::updateOrCreate(
            ['username' => 'guru4'],
            [
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'name' => 'Dedi Rosyidi, M.Pd.',
                'email' => 'ded@stembayo.sch.id'
            ]
        );
        $guru5 = User::updateOrCreate(
            ['username' => 'guru5'],
            [
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'name' => 'Ratri Budiwati, S.Pd.',
                'email' => 'rtr@stembayo.sch.id'
            ]
        );
        $guru6 = User::updateOrCreate(
            ['username' => 'guru6'],
            [
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'name' => 'Rum Ismawati, S.Si.',
                'email' => 'rum@stembayo.sch.id'
            ]
        );
        $guru7 = User::updateOrCreate(
            ['username' => 'guru7'],
            [
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'name' => 'Wahyu Rajasa, S.Pd.',
                'email' => 'raj@stembayo.sch.id'
            ]
        );
        $guru8 = User::updateOrCreate(
            ['username' => 'guru8'],
            [
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'name' => 'Febrian Hadi Susilo, S.Pd.',
                'email' => 'feb@stembayo.sch.id'
            ]
        );
        $guru9 = User::updateOrCreate(
            ['username' => 'guru9'],
            [
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'name' => 'Bakti Maharani, S.Pd.',
                'email' => 'bkt@stembayo.sch.id'
            ]
        );
        $guru10 = User::updateOrCreate(
            ['username' => 'guru10'],
            [
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'name' => 'Subarna, S.Pd.',
                'email' => 'sbr@stembayo.sch.id'
            ]
        );

        // 3. SEED SATPAM & BK
        $satpam1 = User::updateOrCreate(
            ['username' => 'satpam1'],
            [
                'password' => Hash::make('password'),
                'role' => 'satpam',
                'name' => 'Joko Widodo (Gerbang Utama)',
                'email' => 'satpam1@stembayo.sch.id'
            ]
        );
        $satpam2 = User::updateOrCreate(
            ['username' => 'satpam2'],
            [
                'password' => Hash::make('password'),
                'role' => 'satpam',
                'name' => 'Sutarman (Pos II)',
                'email' => 'satpam2@stembayo.sch.id'
            ]
        );

        $bk1 = User::updateOrCreate(
            ['username' => 'bk1'],
            [
                'password' => Hash::make('password'),
                'role' => 'bk',
                'name' => 'Hani Saraswati, S.Psi (Koordinator BK)',
                'email' => 'hani.bk@stembayo.sch.id'
            ]
        );
        $bk2 = User::updateOrCreate(
            ['username' => 'bk2'],
            [
                'password' => Hash::make('password'),
                'role' => 'bk',
                'name' => 'Drs. Edi Suwarno',
                'email' => 'edi.bk@stembayo.sch.id'
            ]
        );

        // 4. SUBJECTS (10 Mapel 12 SIJA B)
        $subjAws  = Subject::updateOrCreate(['code' => 'MPP-AWS'], ['name' => 'MPP AWS Academy (Cloud SIJA)']);
        $subjAi   = Subject::updateOrCreate(['code' => 'MPP-AI'],  ['name' => 'MPP Koding & AI (SIJA)']);
        $subjBind = Subject::updateOrCreate(['code' => 'BIND'],    ['name' => 'Bahasa Indonesia']);
        $subjPabp = Subject::updateOrCreate(['code' => 'PABP'],    ['name' => 'Pendidikan Agama Islam']);
        $subjBing = Subject::updateOrCreate(['code' => 'BING'],    ['name' => 'Bahasa Inggris']);
        $subjMate = Subject::updateOrCreate(['code' => 'MATE'],    ['name' => 'Matematika']);
        $subjPjok = Subject::updateOrCreate(['code' => 'PJOK'],    ['name' => 'PJOK']);
        $subjBjaw = Subject::updateOrCreate(['code' => 'BJAW'],    ['name' => 'Bahasa Jawa']);
        $subjSeja = Subject::updateOrCreate(['code' => 'SEJA'],    ['name' => 'Sejarah']);
        $subjPanc = Subject::updateOrCreate(['code' => 'PANC'],    ['name' => 'Pendidikan Pancasila']);

        // 5. SCHEDULES (Senin - Jumat 12 SIJA B)
        $classes = ['12 SIJA B', 'XII SIJA B'];

        foreach ($classes as $cls) {
            // SENIN
            for ($p = 1; $p <= 2; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Senin', 'period_number' => $p], ['subject_id' => $subjBind->subject_id, 'teacher_id' => $guru3->user_id, 'room' => 'Ruang Teori 9']);
            }
            for ($p = 3; $p <= 4; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Senin', 'period_number' => $p], ['subject_id' => $subjPjok->subject_id, 'teacher_id' => $guru7->user_id, 'room' => 'Lapangan']);
            }
            for ($p = 5; $p <= 6; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Senin', 'period_number' => $p], ['subject_id' => $subjBjaw->subject_id, 'teacher_id' => $guru8->user_id, 'room' => 'Ruang Teori 7']);
            }
            for ($p = 7; $p <= 8; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Senin', 'period_number' => $p], ['subject_id' => $subjSeja->subject_id, 'teacher_id' => $guru9->user_id, 'room' => 'Ruang Teori 7']);
            }
            for ($p = 9; $p <= 11; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Senin', 'period_number' => $p], ['subject_id' => $subjPabp->subject_id, 'teacher_id' => $guru4->user_id, 'room' => 'Ruang Teori 7']);
            }

            // SELASA
            for ($p = 1; $p <= 4; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Selasa', 'period_number' => $p], ['subject_id' => $subjAws->subject_id, 'teacher_id' => $guru1->user_id, 'room' => 'Lab LAN']);
            }
            for ($p = 5; $p <= 6; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Selasa', 'period_number' => $p], ['subject_id' => $subjBind->subject_id, 'teacher_id' => $guru3->user_id, 'room' => 'Ruang Teori 7']);
            }
            for ($p = 7; $p <= 11; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Selasa', 'period_number' => $p], ['subject_id' => $subjBing->subject_id, 'teacher_id' => $guru5->user_id, 'room' => 'Ruang Teori 7']);
            }

            // RABU
            for ($p = 1; $p <= 3; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Rabu', 'period_number' => $p], ['subject_id' => $subjPabp->subject_id, 'teacher_id' => $guru4->user_id, 'room' => 'Ruang Teori 7']);
            }
            for ($p = 4; $p <= 6; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Rabu', 'period_number' => $p], ['subject_id' => $subjMate->subject_id, 'teacher_id' => $guru6->user_id, 'room' => 'Ruang Teori 7']);
            }
            for ($p = 7; $p <= 9; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Rabu', 'period_number' => $p], ['subject_id' => $subjBing->subject_id, 'teacher_id' => $guru5->user_id, 'room' => 'Ruang Teori 7']);
            }

            // KAMIS
            for ($p = 1; $p <= 3; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Kamis', 'period_number' => $p], ['subject_id' => $subjMate->subject_id, 'teacher_id' => $guru6->user_id, 'room' => 'Ruang Teori 7']);
            }
            for ($p = 4; $p <= 5; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Kamis', 'period_number' => $p], ['subject_id' => $subjPjok->subject_id, 'teacher_id' => $guru7->user_id, 'room' => 'Lapangan']);
            }
            for ($p = 6; $p <= 7; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Kamis', 'period_number' => $p], ['subject_id' => $subjSeja->subject_id, 'teacher_id' => $guru9->user_id, 'room' => 'Ruang Teori 7']);
            }
            for ($p = 8; $p <= 9; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Kamis', 'period_number' => $p], ['subject_id' => $subjPanc->subject_id, 'teacher_id' => $guru10->user_id, 'room' => 'Ruang Teori 12']);
            }

            // JUMAT
            for ($p = 1; $p <= 2; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Jumat', 'period_number' => $p], ['subject_id' => $subjPanc->subject_id, 'teacher_id' => $guru10->user_id, 'room' => 'Ruang Teori 13']);
            }
            for ($p = 3; $p <= 4; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Jumat', 'period_number' => $p], ['subject_id' => $subjBjaw->subject_id, 'teacher_id' => $guru8->user_id, 'room' => 'Ruang Teori 13']);
            }
            for ($p = 5; $p <= 8; $p++) {
                Schedule::updateOrCreate(['class_name' => $cls, 'day' => 'Jumat', 'period_number' => $p], ['subject_id' => $subjAi->subject_id, 'teacher_id' => $guru2->user_id, 'room' => 'Lab LAN']);
            }
        }

        // 6. PERMIT REQUESTS & CARE REPORTS (Default Bersih)
        Report::query()->delete();
        PermitRequest::query()->delete();
        // Dikosongkan secara default agar riwayat perizinan dan konseling BK bersih.
        // Data hanya tercatat saat penguji/pengguna membuat izin atau aduan nyata saat evaluasi.

        Schema::enableForeignKeyConstraints();
    }
}