<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cari user BK dan Siswa demo
        $bk1 = DB::table('users')->where('role', 'bk')->first();
        $student1 = DB::table('users')->where('username', 'siswa1')->orWhere('email', 'like', '21872%')->first();
        $student2 = DB::table('users')->where('username', 'siswa2')->orWhere('email', 'like', '21875%')->first();
        $student3 = DB::table('users')->where('username', 'siswa3')->orWhere('email', 'like', '21867%')->first();
        $student4 = DB::table('users')->where('username', 'siswa4')->orWhere('email', 'like', '21895%')->first();

        // Bersihkan data laporan lama (termasuk dummy kerusakan alat/switch hub)
        DB::table('reports')->delete();

        // 1. Siswa 1 (Muhammad Hafidin) - Masalah Belajar (ACADEMIC)
        if ($student1) {
            DB::table('reports')->insert([
                'student_id' => $student1->user_id,
                'category' => 'ACADEMIC',
                'follow_up_preference' => 'WEB_MESSAGE',
                'title' => 'Kesulitan Memahami Logika Algoritma Praktikum Koding AI',
                'description' => 'Saya merasa tertinggal dan kesulitan memahami implementasi logika Python pada modul machine learning Koding AI. Ingin berkonsultasi mengenai strategi belajar atau referensi tambahan.',
                'status' => 'OPEN',
                'created_at' => Carbon::now()->subHours(5),
                'updated_at' => Carbon::now()->subHours(5),
            ]);
        }

        // 2. Siswa 2 (Muhammad Rofiif Arifian) - Masalah Belajar (ACADEMIC)
        if ($student2) {
            DB::table('reports')->insert([
                'student_id' => $student2->user_id,
                'category' => 'ACADEMIC',
                'follow_up_preference' => 'WEB_MESSAGE',
                'title' => 'Tertinggal Materi Konfigurasi Cloud AWS Setelah Izin Sakit',
                'description' => 'Setelah izin sakit selama 3 hari minggu lalu, saya tertinggal materi implementasi VPC dan arsitektur cloud. Saya membutuhkan konsultasi pendampingan belajar dengan guru pengampu.',
                'status' => 'IN_PROGRESS',
                'created_at' => Carbon::now()->subHours(8),
                'updated_at' => Carbon::now()->subHours(8),
            ]);
        }

        // 3. Siswa 3 (Mahesa Gita Dharma) - Konseling Pribadi / Karir (PERSONAL)
        if ($student3) {
            DB::table('reports')->insert([
                'student_id' => $student3->user_id,
                'category' => 'PERSONAL',
                'follow_up_preference' => 'NEUTRAL_MEET',
                'title' => 'Sesi Konseling Karir: Persiapan Magang Industri Program 4 Tahun SIJA',
                'description' => 'Siswa membutuhkan konsultasi dan bimbingan penempatan industri magang program 4 tahun SIJA untuk peminatan Cloud Infrastructure vs AI Engineer.',
                'status' => 'IN_PROGRESS',
                'created_at' => Carbon::now()->subHours(12),
                'updated_at' => Carbon::now()->subHours(12),
            ]);
        }

        // 4. Siswa 4 (Vincentius Rafa Christa P) - Pendampingan Kedisiplinan (PERSONAL)
        if ($student4) {
            DB::table('reports')->insert([
                'student_id' => $student4->user_id,
                'category' => 'PERSONAL',
                'follow_up_preference' => 'WEB_MESSAGE',
                'title' => 'Klarifikasi Keterlambatan Masuk Kelas & Pendampingan Kedisiplinan',
                'description' => 'Siswa telah melakukan klarifikasi dan pendampingan bersama guru BK terkait kendala transportasi dan telah kembali beraktivitas normal.',
                'counselor_response' => 'Siswa telah berkonsultasi dengan guru BK, kendala transportasi telah dicarikan solusi, dan siswa berkomitmen untuk hadir tepat waktu.',
                'responded_at' => Carbon::now()->subDays(1),
                'counselor_id' => $bk1?->user_id,
                'status' => 'RESOLVED',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(1),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
