<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('source')->nullable(); // instagram, referral, etc
            $table->enum('status', ['new', 'contacted', 'scheduled', 'converted', 'lost'])->default('new');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        
        Schema::create('lead_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->timestamp('scheduled_at');
            $table->boolean('confirmed')->default(false);
            $table->boolean('reminder_sent')->default(false);
            $table->boolean('follow_up_sent')->default(false);
            $table->timestamps();
        });
    }
};
