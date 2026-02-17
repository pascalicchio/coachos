<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::where('organization_id', auth()->user()->organization_id)
            ->orderBy('name')
            ->get();
        
        return inertia('Locations', [
            'locations' => $locations,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:50',
            'zip' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email',
            'opening_time' => 'nullable',
            'closing_time' => 'nullable',
        ]);

        $location = Location::create([
            'organization_id' => auth()->user()->organization_id,
            ...$request->all(),
        ]);

        return back()->with('success', 'Location created successfully');
    }

    public function update(Request $request, Location $location)
    {
        $this->authorize('update', $location);
        
        $location->update($request->all());

        return back()->with('success', 'Location updated successfully');
    }

    public function destroy(Location $location)
    {
        $this->authorize('delete', $location);
        
        $location->delete();

        return back()->with('success', 'Location deleted successfully');
    }
}
