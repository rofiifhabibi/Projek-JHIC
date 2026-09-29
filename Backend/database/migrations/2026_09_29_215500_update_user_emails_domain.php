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
        // Update domain email siswa & staf agar tidak memuat nama domain lama
        DB::table('users')
            ->where('email', 'like', '%@student.stembayo.sch.id')
            ->update([
                'email' => DB::raw("REPLACE(email, '@student.stembayo.sch.id', '@student.smkn2depoksleman.sch.id')")
            ]);

        DB::table('users')
            ->where('email', 'like', '%@stembayo.sch.id')
            ->update([
                'email' => DB::raw("REPLACE(email, '@stembayo.sch.id', '@smkn2depoksleman.sch.id')")
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
