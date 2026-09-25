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
        Schema::create('reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->unsignedBigInteger('student_id')->nullable(); // Null jika pelapor anonim[cite: 5]
            $table->enum('category', ['BULLYING', 'FACILITY', 'ACADEMIC', 'PERSONAL', 'OTHERS']); //[cite: 5]
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['OPEN', 'IN_PROGRESS', 'RESOLVED'])->default('OPEN'); //[cite: 3]
            $table->timestamps();

            $table->foreign('student_id')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
