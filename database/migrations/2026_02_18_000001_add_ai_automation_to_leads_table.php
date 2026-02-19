<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->boolean('ai_enabled')->default(false)->after('notes');
            $table->integer('ai_sequence_step')->default(0)->after('ai_enabled');
            $table->timestamp('next_followup_at')->nullable()->after('ai_sequence_step');
            $table->string('goals')->nullable()->after('next_followup_at');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['ai_enabled', 'ai_sequence_step', 'next_followup_at', 'goals']);
        });
    }
};
