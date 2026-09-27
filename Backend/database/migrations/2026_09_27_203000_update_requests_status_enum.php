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
        // Update enum kolom status pada tabel requests agar menyertakan APPROVED dan CANCELLED
        DB::statement("ALTER TABLE requests MODIFY COLUMN status ENUM('PENDING', 'APPROVED', 'ACTIVE', 'OVERDUE', 'COMPLETED', 'ALPHA', 'CLOSED', 'REJECTED', 'CANCELLED') NOT NULL DEFAULT 'PENDING'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE requests MODIFY COLUMN status ENUM('PENDING', 'ACTIVE', 'OVERDUE', 'COMPLETED', 'ALPHA', 'CLOSED', 'REJECTED') NOT NULL DEFAULT 'PENDING'");
    }
};
