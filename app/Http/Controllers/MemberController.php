<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::with('membership')
            ->where('organization_id', auth()->user()->organization_id)
            ->orderBy('name')
            ->get();
        
        return inertia('Members', [
            'members' => $members,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'membership_id' => 'nullable|exists:memberships,id',
            'join_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
        ]);

        $member = Member::create([
            'organization_id' => auth()->user()->organization_id,
            ...$request->all(),
            'join_date' => $request->join_date ?? now(),
            'status' => 'active',
        ]);

        return back()->with('success', 'Member added successfully');
    }

    public function update(Request $request, Member $member)
    {
        $member->update($request->all());
        return back()->with('success', 'Member updated');
    }

    public function freeze(Member $member)
    {
        $member->update(['status' => 'frozen']);
        return back()->with('success', 'Member frozen');
    }

    public function cancel(Member $member)
    {
        $member->update(['status' => 'cancelled']);
        return back()->with('success', 'Member cancelled');
    }
}
