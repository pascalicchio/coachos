<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_templates', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');
            $table->integer('day_of_week'); // 0=Sunday, 1=Monday, etc.
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('coach_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('location')->default('main');
            $table->string('martial_art')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};
