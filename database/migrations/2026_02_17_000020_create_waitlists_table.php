<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waitlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheduled_class_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->integer('position');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['scheduled_class_id', 'member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waitlists');
    }
};
