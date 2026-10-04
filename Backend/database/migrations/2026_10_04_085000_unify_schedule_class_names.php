<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Hapus entri jadwal 'XII SIJA B' yang redundan dengan '12 SIJA B'
        DB::table('schedules')->where('class_name', 'XII SIJA B')->delete();

        // 2. Pastikan akun siswa yang mungkin menggunakan 'XII SIJA B' distandarisasi ke '12 SIJA B'
        DB::table('users')->where('class_name', 'XII SIJA B')->update(['class_name' => '12 SIJA B']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data duplikasi tidak perlu dipulihkan
    }
};
