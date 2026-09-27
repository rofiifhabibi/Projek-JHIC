<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah kolom request_id pada tabel reports untuk mengaitkan aduan/klarifikasi dengan perizinan tertentu
        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedBigInteger('request_id')->nullable()->after('student_id');
            $table->foreign('request_id')->references('request_id')->on('requests')->onDelete('set null');
        });

        // 2. Tambah kolom alpha_at pada tabel requests untuk mencatat waktu persis siswa dinyatakan ALPHA
        Schema::table('requests', function (Blueprint $table) {
            $table->dateTime('alpha_at')->nullable()->after('expiry_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['request_id']);
            $table->dropColumn('request_id');
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('alpha_at');
        });
    }
};
