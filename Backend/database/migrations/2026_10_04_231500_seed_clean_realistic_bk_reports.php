<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
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
            // 1. Bersihkan seluruh data aduan uji coba lama
            DB::table('reports')->delete();

            // 2. Ambil user_id riil dari database
            $bkUser = User::where('username', 'bk1')->first();
            $bkId = $bkUser?->user_id;

            $s1 = User::where('username', 'siswa1')->value('user_id');
            $s2 = User::where('username', 'siswa2')->value('user_id');
            $s3 = User::where('username', 'siswa3')->value('user_id');
            $s4 = User::where('username', 'siswa4')->value('user_id');
            $s5 = User::where('username', 'siswa5')->value('user_id');

            $now = Carbon::now();

            $reports = [
                // ==========================================
                // 1. KOLOM OPEN (Aduan Masuk Baru)
                // ==========================================
                [
                    'student_id' => $s2 ?? $s1,
                    'request_id' => null,
                    'category' => 'BULLYING',
                    'follow_up_preference' => 'WEB_MESSAGE',
                    'title' => 'Laporan Intimidasi Verbal di Koridor Belakang Sekolah',
                    'description' => "Saya merasa tidak nyaman karena sering diejek dan disoraki secara verbal oleh sekelompok siswa saat jam istirahat di koridor belakang. Mohon bantuan bimbingan konseling agar suasana belajar tetap aman dan tertib.",
                    'counselor_response' => null,
                    'responded_at' => null,
                    'counselor_id' => null,
                    'status' => 'OPEN',
                    'created_at' => $now->copy()->subHours(2),
                    'updated_at' => $now->copy()->subHours(2),
                ],
                [
                    'student_id' => $s3 ?? $s1,
                    'request_id' => null,
                    'category' => 'PERSONAL',
                    'follow_up_preference' => 'NEUTRAL_MEET',
                    'title' => 'Konsultasi Kecemasan dan Tekanan Belajar',
                    'description' => "Belakangan ini saya mengalami kesulitan tidur dan cemas berlebihan memikirkan tugas serta penyesuaian lingkungan sekolah. Saya ingin berkonsultasi secara privat dengan konselor BK untuk mendapatkan saran pengelolaan stres.",
                    'counselor_response' => null,
                    'responded_at' => null,
                    'counselor_id' => null,
                    'status' => 'OPEN',
                    'created_at' => $now->copy()->subHours(5),
                    'updated_at' => $now->copy()->subHours(5),
                ],

                // ==========================================
                // 2. KOLOM IN_PROGRESS (Sedang Ditangani BK)
                // ==========================================
                [
                    'student_id' => $s4 ?? $s1,
                    'request_id' => null,
                    'category' => 'BULLYING',
                    'follow_up_preference' => 'NEUTRAL_MEET',
                    'title' => 'Mediasi Perselisihan dan Pengucilan di Kelas',
                    'description' => "Terjadi kesalahpahaman antarkelompok teman di kelas yang berujung pada saling menyindir di media sosial dan pengucilan salah satu teman saat kerja kelompok.\n\n[Catatan Internal BK - " . $now->copy()->subDays(1)->format('d/m/Y H:i') . "]:\nKonselor telah memanggil kedua pihak untuk mendengarkan kronologi dari masing-masing siswa. Sesi mediasi tatap muka dijadwalkan pada jam istirahat kedua.",
                    'counselor_response' => "Halo, konselor BK sudah menerima laporanmu dan telah berkoordinasi dengan wali kelas. Jadwal mediasi bersama akan dilaksanakan hari ini di ruang BK.",
                    'responded_at' => $now->copy()->subDays(1),
                    'counselor_id' => $bkId,
                    'status' => 'IN_PROGRESS',
                    'created_at' => $now->copy()->subDays(1)->subHours(3),
                    'updated_at' => $now->copy()->subDays(1),
                ],
                [
                    'student_id' => $s5 ?? $s1,
                    'request_id' => null,
                    'category' => 'PERSONAL',
                    'follow_up_preference' => 'WEB_MESSAGE',
                    'title' => 'Bimbingan Adaptasi Sosial dan Hubungan Pertemanan',
                    'description' => "Saya merasa sulit berbaur dan membuka diri dengan teman-teman sekelas sehingga sering merasa terasingkan saat kegiatan belajar kelompok mandiri.\n\n[Catatan Internal BK - " . $now->copy()->subDays(2)->format('d/m/Y H:i') . "]:\nGuru BK telah memberikan panduan refleksi diri dan merancang pendampingan berkala bersama guru pembimbing.",
                    'counselor_response' => "Terima kasih sudah berani bercerita. Guru BK sangat terbuka untuk membantumu menemukan ritme pertemanan yang nyaman. Lembar panduan sudah disiapkan di ruang BK.",
                    'responded_at' => $now->copy()->subDays(2),
                    'counselor_id' => $bkId,
                    'status' => 'IN_PROGRESS',
                    'created_at' => $now->copy()->subDays(2)->subHours(4),
                    'updated_at' => $now->copy()->subDays(2),
                ],

                // ==========================================
                // 3. KOLOM RESOLVED (Kasus Tuntas Selesai)
                // ==========================================
                [
                    'student_id' => $s1,
                    'request_id' => null,
                    'category' => 'OTHERS',
                    'follow_up_preference' => 'WEB_MESSAGE',
                    'title' => 'Klarifikasi Keterlambatan Masuk Kelas Pasca Kegiatan OSIS',
                    'description' => "Yth. Bapak/Ibu Guru BK, saya menyampaikan permohonan klarifikasi absensi karena terlambat kembali ke ruang kelas setelah membantu inventarisasi perlengkapan upacara bendera bersama pengurus OSIS.",
                    'counselor_response' => "Klarifikasi telah diverifikasi bersama pembina OSIS. Catatan kehadiran telah disesuaikan dan siswa diingatkan untuk selalu melapor ke guru piket sebelum bertugas.",
                    'responded_at' => $now->copy()->subDays(3),
                    'counselor_id' => $bkId,
                    'status' => 'RESOLVED',
                    'created_at' => $now->copy()->subDays(3)->subHours(5),
                    'updated_at' => $now->copy()->subDays(3),
                ],
                [
                    'student_id' => $s2 ?? $s1,
                    'request_id' => null,
                    'category' => 'PERSONAL',
                    'follow_up_preference' => 'WEB_MESSAGE',
                    'title' => 'Penyelesaian Kesalahpahaman Antar Siswa di Kantin',
                    'description' => "Permohonan mediasi atas perselisihan kecil yang sempat memicu adu mulut antarsiswa saat antrean di kantin sekolah beberapa hari lalu.",
                    'counselor_response' => "Kedua pihak telah dipertemukan secara kekeluargaan di ruang BK, saling memaafkan, dan menandatangani komitmen bersama menjaga kerukunan serta ketertiban lingkungan sekolah.",
                    'responded_at' => $now->copy()->subDays(4),
                    'counselor_id' => $bkId,
                    'status' => 'RESOLVED',
                    'created_at' => $now->copy()->subDays(4)->subHours(6),
                    'updated_at' => $now->copy()->subDays(4),
                ],
            ];

            DB::table('reports')->insert($reports);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('reports')->delete();
    }
};
