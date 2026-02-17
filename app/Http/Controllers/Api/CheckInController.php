<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\CheckIn;
use App\Models\AppNotification;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function lookup(Request $request)
    {
        $query = $request->get('q');
        
        $members = Member::where('organization_id', $request->user()->organization_id)
            ->where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'email', 'phone', 'membership_id', 'expiry_date']);

        return response()->json($members);
    }

    public function checkIn(Request $request)
    {
        $request->validate(['member_id' => 'required|exists:members,id']);

        $member = Member::findOrFail($request->member_id);
        
        if ($member->organization_id !== $request->user()->organization_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($member->isExpired()) {
            return response()->json(['error' => 'Membership expired'], 400);
        }

        $checkIn = CheckIn::checkIn($member->id, null, 'mobile');

        return response()->json([
            'success' => true,
            'member' => $member,
            'check_in' => $checkIn,
        ]);
    }

    public function today()
    {
        $checkIns = CheckIn::with('member')
            ->whereHas('member', fn($q) => $q->where('organization_id', auth()->user()->organization_id))
            ->whereDate('check_in_time', today())
            ->orderBy('check_in_time', 'desc')
            ->get();

        return response()->json($checkIns);
    }
}
