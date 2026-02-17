<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::with('organization')
            ->where('organization_id', auth()->user()->organization_id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return inertia('Leads', [
            'leads' => $leads,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $lead = Lead::create([
            'organization_id' => auth()->user()->organization_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'source' => $request->source,
            'notes' => $request->notes,
            'status' => 'new',
        ]);

        return back()->with('success', 'Lead created successfully');
    }

    public function update(Request $request, Lead $lead)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:100',
            'status' => 'sometimes|in:new,contacted,scheduled,converted,lost',
            'notes' => 'nullable|string',
        ]);

        $lead->update($request->all());

        if ($request->status === 'contacted') {
            $lead->update(['last_contacted_at' => now()]);
        }

        return back()->with('success', 'Lead updated successfully');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return back()->with('success', 'Lead deleted successfully');
    }

    public function convert(Lead $lead)
    {
        $lead->update(['status' => 'converted']);
        return back()->with('success', 'Lead converted to member!');
    }
}
