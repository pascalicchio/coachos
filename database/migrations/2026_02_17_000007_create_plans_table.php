<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // starter, pro, business
            $table->decimal('price', 10, 2);
            $table->integer('max_locations');
            $table->integer('max_coaches');
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default plans
        DB::table('plans')->insert([
            ['name' => 'starter', 'price' => 19.00, 'max_locations' => 1, 'max_coaches' => 5, 'features' => json_encode(['scheduling', 'payments', 'basic_reports']), 'created_at' => now()],
            ['name' => 'pro', 'price' => 39.00, 'max_locations' => 3, 'max_coaches' => 15, 'features' => json_encode(['scheduling', 'payments', 'private_classes', 'leads', 'reports', 'inventory']), 'created_at' => now()],
            ['name' => 'business', 'price' => 79.00, 'max_locations' => PHP_INT_MAX, 'max_coaches' => PHP_INT_MAX, 'features' => json_encode(['scheduling', 'payments', 'private_classes', 'leads', 'reports', 'inventory', 'api_access', 'white_label']), 'created_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
