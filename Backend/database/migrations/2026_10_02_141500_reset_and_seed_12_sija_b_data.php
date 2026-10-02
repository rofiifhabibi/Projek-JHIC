<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\DatabaseSeeder;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // Bersihkan data perizinan, laporan, jadwal, mapel lama agar data bersih
        DB::table('requests')->truncate();
        DB::table('reports')->truncate();
        DB::table('schedules')->truncate();
        DB::table('subjects')->truncate();

        // Jalankan Seeder baru untuk 12 SIJA B
        Artisan::call('db:seed', [
            '--class' => DatabaseSeeder::class,
            '--force' => true,
        ]);

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};