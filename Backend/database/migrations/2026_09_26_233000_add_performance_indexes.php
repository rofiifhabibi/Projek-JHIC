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
        Schema::table('requests', function (Blueprint $table) {
            $table->index('status', 'requests_status_idx');
            $table->index(['student_id', 'status'], 'requests_student_status_idx');
            $table->index(['initial_teacher_id', 'status'], 'requests_teacher_status_idx');
            $table->index(['status', 'expiry_time'], 'requests_status_expiry_idx');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->index('status', 'reports_status_idx');
            $table->index(['student_id', 'status'], 'reports_student_status_idx');
            $table->index('category', 'reports_category_idx');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->index(['teacher_id', 'day'], 'schedules_teacher_day_idx');
            $table->index('class_name', 'schedules_class_name_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropIndex('requests_status_idx');
            $table->dropIndex('requests_student_status_idx');
            $table->dropIndex('requests_teacher_status_idx');
            $table->dropIndex('requests_status_expiry_idx');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex('reports_status_idx');
            $table->dropIndex('reports_student_status_idx');
            $table->dropIndex('reports_category_idx');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex('schedules_teacher_day_idx');
            $table->dropIndex('schedules_class_name_idx');
        });
    }
};
