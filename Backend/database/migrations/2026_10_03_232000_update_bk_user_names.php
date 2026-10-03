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
        DB::table('users')->where('username', 'bk1')->update([
            'name' => 'Tim Bimbingan Konseling (BK)',
            'email' => 'bk@student.stembayo.sch.id'
        ]);

        DB::table('users')->where('username', 'bk2')->update([
            'name' => 'Konselor BK SMKN 2 Depok',
            'email' => 'bk2@student.stembayo.sch.id'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed
    }
};
