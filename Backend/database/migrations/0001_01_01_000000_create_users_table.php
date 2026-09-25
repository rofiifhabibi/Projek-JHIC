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
         Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username')->unique(); // NIS untuk siswa, NIP/ID untuk guru, bk, satpam
            $table->string('email')->unique()->nullable(); // Contoh: 21875@student.stembayo.sch.id
            $table->string('password');
            $table->enum('role', ['student', 'teacher', 'bk', 'satpam']); //[cite: 3]
            $table->string('name');
            $table->string('class_name')->nullable(); // Contoh: "10 SIJA (K)" atau "11 TKR (N)"
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
