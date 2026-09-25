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
        Schema::create('requests', function (Blueprint $table) {
            $table->id('request_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('initial_teacher_id'); // Guru pengampu yang pertama menyetujui[cite: 3]
            $table->enum('type', ['TEMP', 'EXIT_SCHOOL']); // TEMP = Izin keluar sementara, EXIT_SCHOOL = Izin pulang
            $table->enum('status', ['PENDING', 'ACTIVE', 'OVERDUE', 'COMPLETED', 'ALPHA', 'CLOSED', 'REJECTED'])->default('PENDING');
            $table->text('reason')->nullable();
            $table->integer('duration_minutes')->default(30);
            $table->dateTime('expiry_time')->nullable(); // Batas kembali siswa sebelum status OVERDUE
            $table->string('qr_token')->unique()->nullable(); // Token acak untuk QR Code di HP siswa[cite: 3]
            $table->timestamps();

            $table->foreign('student_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('initial_teacher_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
