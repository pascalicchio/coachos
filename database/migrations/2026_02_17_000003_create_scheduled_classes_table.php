<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scheduled_classes', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->foreignId('coach_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('class_template_id')->nullable()->constrained('class_templates')->nullOnDelete();
            $table->string('location')->default('main');
            $table->string('martial_art')->nullable();
            $table->integer('attendance_count')->default(0);
            $table->boolean('is_cancelled')->default(false);
            $table->timestamps();
        });
    }
};
