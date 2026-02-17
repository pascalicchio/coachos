<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // owner, manager, coach, staff
            $table->json('permissions')->nullable(); // { "users": ["read", "write"], "schedules": ["read", "write", "delete"] }
            $table->timestamps();
        });

        // Seed default roles with permissions
        DB::table('roles')->insert([
            ['name' => 'owner', 'permissions' => json_encode(['users' => ['read', 'write', 'delete'], 'schedules' => ['read', 'write', 'delete'], 'payments' => ['read', 'write', 'delete'], 'reports' => ['read', 'write'], 'settings' => ['read', 'write', 'delete'], 'leads' => ['read', 'write', 'delete'], 'inventory' => ['read', 'write', 'delete']]), 'created_at' => now()],
            ['name' => 'manager', 'permissions' => json_encode(['users' => ['read', 'write'], 'schedules' => ['read', 'write', 'delete'], 'payments' => ['read', 'write'], 'reports' => ['read'], 'leads' => ['read', 'write', 'delete'], 'inventory' => ['read', 'write']]), 'created_at' => now()],
            ['name' => 'coach', 'permissions' => json_encode(['schedules' => ['read'], 'payments' => ['read'], 'members' => ['read']]), 'created_at' => now()],
            ['name' => 'staff', 'permissions' => json_encode(['schedules' => ['read'], 'leads' => ['read', 'write']]), 'created_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
