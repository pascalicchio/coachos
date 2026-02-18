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

    /**
     * Enable AI automation for a lead
     */
    public function enableAi(Request $request, Lead $lead)
    {
        $request->validate([
            'goals' => 'nullable|string|max:500',
        ]);

        $lead->update([
            'ai_enabled' => true,
            'ai_sequence_step' => 0,
            'next_followup_at' => now(), // Start immediately
            'goals' => $request->goals,
            'status' => 'nurturing',
        ]);

        // Trigger first follow-up immediately
        $organization = $lead->organization;
        $gymName = $organization?->name ?? 'Our Gym';
        $message = \App\Services\LeadAutomationService::processFollowUp($lead, $gymName);

        return back()->with('success', 'AI automation enabled for this lead!');
    }

    /**
     * Disable AI automation for a lead
     */
    public function disableAi(Lead $lead)
    {
        $lead->update([
            'ai_enabled' => false,
            'next_followup_at' => null,
        ]);

        return back()->with('success', 'AI automation disabled for this lead');
    }

    /**
     * Get AI message preview
     */
    public function aiPreview(Request $request, Lead $lead)
    {
        $step = $request->get('step', 0);
        $organization = $lead->organization;
        $gymName = $organization?->name ?? 'Our Gym';
        
        $message = \App\Services\LeadAutomationService::generateMessage($step, [
            'name' => $lead->name,
            'goals' => $lead->goals,
        ], $gymName);

        return response()->json(['message' => $message]);
    }
}
