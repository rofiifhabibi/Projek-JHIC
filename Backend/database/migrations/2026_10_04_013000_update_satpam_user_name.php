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
        DB::table('users')->where('username', 'satpam1')->update([
            'name' => 'Satpam',
        ]);

        DB::table('users')->where('username', 'satpam2')->update([
            'name' => 'Satpam Pos II',
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
