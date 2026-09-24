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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id('schedule_id');
            $table->string('class_name'); // Contoh: "10 SIJA (K)"[cite: 6]
            $table->enum('day', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']); //[cite: 6]
            $table->unsignedTinyInteger('period_number'); // Jam ke-1 s.d. 12[cite: 6]
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('teacher_id'); // Merujuk ke users.user_id milik guru
            $table->string('room')->nullable(); // Contoh: "Lab PnP", "R 8", "Lapangan"[cite: 6]
            $table->timestamps();

            $table->foreign('subject_id')->references('subject_id')->on('subjects')->onDelete('cascade');
            $table->foreign('teacher_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
