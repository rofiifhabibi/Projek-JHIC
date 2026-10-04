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
        // Ubah foreign key initial_teacher_id dari cascade menjadi restrict untuk menjaga integritas riwayat perizinan siswa
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['initial_teacher_id']);
            $table->foreign('initial_teacher_id')->references('user_id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['initial_teacher_id']);
            $table->foreign('initial_teacher_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }
};
