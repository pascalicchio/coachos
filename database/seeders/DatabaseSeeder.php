<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Plans and Roles are seeded via migrations

        // Demo Organization
        $org = Organization::create([
            'name' => 'Demo BJJ Academy',
            'slug' => 'demo-bjj',
            'is_active' => true,
        ]);

        // Demo User
        $owner = User::create([
            'name' => 'Demo Owner',
            'email' => 'demo@gymmanageros.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);
    }
}
