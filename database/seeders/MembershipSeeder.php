<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\Membership;

class MembershipSeeder extends Seeder
{
    public function run()
    {
        $orgs = Organization::all();
        
        foreach ($orgs as $org) {
            Membership::create([
                'organization_id' => $org->id,
                'name' => 'Monthly',
                'price' => 79,
                'duration_days' => 30,
                'description' => 'Monthly membership - unlimited classes',
                'is_active' => true,
            ]);
            
            Membership::create([
                'organization_id' => $org->id,
                'name' => 'Quarterly',
                'price' => 199,
                'duration_days' => 90,
                'description' => 'Quarterly membership - save 16%',
                'is_active' => true,
            ]);
            
            Membership::create([
                'organization_id' => $org->id,
                'name' => 'Annual',
                'price' => 699,
                'duration_days' => 365,
                'description' => 'Annual membership - best value, save 26%',
                'is_active' => true,
            ]);
        }
    }
}
