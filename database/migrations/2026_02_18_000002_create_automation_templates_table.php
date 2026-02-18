<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('automation_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('trigger'); // welcome, followup, offer, urgency, final
            $table->integer('step')->default(0);
            $table->string('subject');
            $table->text('message');
            $table->integer('delay_days')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['organization_id', 'step']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automation_templates');
    }
};
