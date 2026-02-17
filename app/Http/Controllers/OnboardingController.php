<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Location;
use App\Models\Membership;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function step1(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:organizations,slug',
        ]);

        $org = Organization::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'is_active' => true,
        ]);

        return response()->json(['organization' => $org, 'step' => 2]);
    }

    public function step2(Request $request)
    {
        $org = Organization::find($request->organization_id);
        
        $location = $org->locations()->create([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_active' => true,
        ]);

        return response()->json(['location' => $location, 'step' => 3]);
    }

    public function step3(Request $request)
    {
        $org = Organization::find($request->organization_id);
        
        // Create default memberships
        $memberships = [
            ['name' => 'Monthly', 'price' => 79, 'duration_days' => 30],
            ['name' => 'Quarterly', 'price' => 199, 'duration_days' => 90],
            ['name' => 'Annual', 'price' => 699, 'duration_days' => 365],
        ];

        foreach ($memberships as $m) {
            $org->memberships()->create(array_merge($m, ['is_active' => true]));
        }

        return response()->json(['success' => true, 'step' => 'complete']);
    }
}
