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
        // Plans
        $plans = [
            ['name' => 'Starter', 'price' => 19, 'max_locations' => 1, 'max_coaches' => 5, 'features' => ['Basic scheduling', '50 members', 'Email support']],
            ['name' => 'Pro', 'price' => 39, 'max_locations' => 3, 'max_coaches' => 15, 'features' => ['Advanced scheduling', '200 members', 'Priority support', 'SMS notifications']],
            ['name' => 'Business', 'price' => 79, 'max_locations' => 999, 'max_coaches' => 999, 'features' => ['Unlimited everything', 'API access', '24/7 support', 'Custom branding']],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }

        // Roles
        $roles = ['owner', 'manager', 'coach', 'staff'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

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
            'organization_id' => $org->id,
            'role_id' => 1,
        ]);
    }
}
