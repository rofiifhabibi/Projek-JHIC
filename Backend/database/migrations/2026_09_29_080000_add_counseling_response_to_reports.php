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
        Schema::table('reports', function (Blueprint $table) {
            $table->string('follow_up_preference', 30)->default('WEB_MESSAGE')->after('category');
            $table->text('counselor_response')->nullable()->after('description');
            $table->dateTime('responded_at')->nullable()->after('counselor_response');
            $table->unsignedBigInteger('counselor_id')->nullable()->after('responded_at');

            $table->foreign('counselor_id')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['counselor_id']);
            $table->dropColumn(['follow_up_preference', 'counselor_response', 'responded_at', 'counselor_id']);
        });
    }
};
