<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Monthly, Quarterly, Annual, etc
            $table->decimal('price', 10, 2);
            $table->integer('duration_days'); // 30, 90, 365
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('membership_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('join_date');
            $table->date('expiry_date')->nullable();
            $table->string('status')->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
        Schema::dropIfExists('memberships');
    }
};
