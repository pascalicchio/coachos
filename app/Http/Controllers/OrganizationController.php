<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function show()
    {
        $organization = auth()->user()->organization;
        
        return inertia('Organization/Settings', [
            'organization' => $organization->load('currentSubscription'),
        ]);
    }

    public function update(Request $request, Organization $organization)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:100|unique:organizations,slug,' . $organization->id,
        ]);

        $organization->update($request->all());

        return back()->with('success', 'Organization updated successfully');
    }

    public function switchPlan(Request $request, Organization $organization)
    {
        $request->validate([
            'plan' => 'required|in:starter,pro,business',
        ]);

        $organization->update(['plan' => $request->plan]);

        return back()->with('success', 'Plan switched to ' . ucfirst($request->plan));
    }

    public function cancelTrial(Request $request, Organization $organization)
    {
        $organization->update([
            'trial_ends_at' => now(),
        ]);

        return back()->with('success', 'Trial cancelled');
    }
}
