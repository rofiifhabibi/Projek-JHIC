<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            // 1. Lepaskan relasi request_id pada reports (jika ada) agar tidak melanggar foreign key
            DB::table('reports')->update(['request_id' => null]);

            // 2. Bersihkan seluruh data perizinan uji coba lama
            DB::table('requests')->delete();

            // 3. Ambil user_id riil guru dan siswa
            $g1 = User::where('username', 'guru1')->value('user_id') ?? 6;
            $g2 = User::where('username', 'guru2')->value('user_id') ?? 7;
            $g3 = User::where('username', 'guru3')->value('user_id') ?? 8;

            $s1 = User::where('username', 'siswa1')->value('user_id') ?? 1;
            $s2 = User::where('username', 'siswa2')->value('user_id') ?? 2;
            $s3 = User::where('username', 'siswa3')->value('user_id') ?? 3;
            $s4 = User::where('username', 'siswa4')->value('user_id') ?? 4;
            $s5 = User::where('username', 'siswa5')->value('user_id') ?? 5;

            $now = Carbon::now();

            $permits = [
                // 1. Izin Keluar Sementara Gerbang (TEMP) - Beli Komponen Praktik
                [
                    'student_id' => $s1,
                    'initial_teacher_id' => $g1,
                    'type' => 'TEMP',
                    'status' => 'COMPLETED',
                    'reason' => 'Membeli komponen konektor & kabel LAN di toko ruko depan gerbang untuk kelengkapan praktik bengkel',
                    'duration_minutes' => 30,
                    'expiry_time' => $now->copy()->subDays(1)->addMinutes(30),
                    'qr_token' => (string) Str::uuid(),
                    'alpha_at' => null,
                    'created_at' => $now->copy()->subDays(1)->subHours(3),
                    'updated_at' => $now->copy()->subDays(1)->subHours(2)->subMinutes(30),
                ],
                // 2. Izin Keluar Sementara Gerbang (TEMP) - Fotokopi Laporan PKL
                [
                    'student_id' => $s2,
                    'initial_teacher_id' => $g1,
                    'type' => 'TEMP',
                    'status' => 'COMPLETED',
                    'reason' => 'Fotokopi lembar kerja dan jilid laporan PKL/Magang di kios fotokopi luar gerbang sekolah',
                    'duration_minutes' => 20,
                    'expiry_time' => $now->copy()->subDays(2)->addMinutes(20),
                    'qr_token' => (string) Str::uuid(),
                    'alpha_at' => null,
                    'created_at' => $now->copy()->subDays(2)->subHours(4),
                    'updated_at' => $now->copy()->subDays(2)->subHours(3)->subMinutes(40),
                ],
                // 3. Izin Keluar Sementara Gerbang (TEMP) - Ambil Laptop Ketinggalan di Rumah
                [
                    'student_id' => $s3,
                    'initial_teacher_id' => $g2,
                    'type' => 'TEMP',
                    'status' => 'COMPLETED',
                    'reason' => 'Mengambil laptop dan modul praktik yang tertinggal di rumah (sudah konfirmasi orang tua)',
                    'duration_minutes' => 45,
                    'expiry_time' => $now->copy()->subDays(3)->addMinutes(45),
                    'qr_token' => (string) Str::uuid(),
                    'alpha_at' => null,
                    'created_at' => $now->copy()->subDays(3)->subHours(2),
                    'updated_at' => $now->copy()->subDays(3)->subHours(1)->subMinutes(15),
                ],
                // 4. Izin Keluar Sementara Gerbang (TEMP) - Antar Surat Dispensasi Mitra
                [
                    'student_id' => $s4,
                    'initial_teacher_id' => $g3,
                    'type' => 'TEMP',
                    'status' => 'COMPLETED',
                    'reason' => 'Mengantar surat dispensasi lomba perwakilan sekolah ke kantor instansi mitra dekat sekolah',
                    'duration_minutes' => 30,
                    'expiry_time' => $now->copy()->subDays(3)->addMinutes(30),
                    'qr_token' => (string) Str::uuid(),
                    'alpha_at' => null,
                    'created_at' => $now->copy()->subDays(3)->subHours(5),
                    'updated_at' => $now->copy()->subDays(3)->subHours(4)->subMinutes(30),
                ],
                // 5. Izin Pulang Sekolah (EXIT_SCHOOL) - Sakit Demam Dijemput Orang Tua
                [
                    'student_id' => $s5,
                    'initial_teacher_id' => $g1,
                    'type' => 'EXIT_SCHOOL',
                    'status' => 'COMPLETED',
                    'reason' => 'Izin pulang lebih awal karena demam tinggi (sudah konfirmasi orang tua untuk dijemput di gerbang)',
                    'duration_minutes' => 0,
                    'expiry_time' => null,
                    'qr_token' => (string) Str::uuid(),
                    'alpha_at' => null,
                    'created_at' => $now->copy()->subDays(4)->subHours(3),
                    'updated_at' => $now->copy()->subDays(4)->subHours(2),
                ],
                // 6. Izin Pulang Sekolah (EXIT_SCHOOL) - Keperluan Keluarga Mendesak
                [
                    'student_id' => $s2,
                    'initial_teacher_id' => $g2,
                    'type' => 'EXIT_SCHOOL',
                    'status' => 'COMPLETED',
                    'reason' => 'Izin pulang sekolah mendahului karena ada keperluan keluarga mendesak (didampingi wali murid)',
                    'duration_minutes' => 0,
                    'expiry_time' => null,
                    'qr_token' => (string) Str::uuid(),
                    'alpha_at' => null,
                    'created_at' => $now->copy()->subDays(5)->subHours(4),
                    'updated_at' => $now->copy()->subDays(5)->subHours(3),
                ],
            ];

            DB::table('requests')->insert($permits);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('requests')->delete();
    }
};
