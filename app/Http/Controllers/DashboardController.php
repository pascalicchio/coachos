<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\ScheduledClass;

class DashboardController extends Controller
{
    public function stats()
    {
        // Check if we have a user session (demo mode without DB)
        if (!auth()->check() && !session()->has('user_id')) {
            // Demo data - no DB required
            return [
                'members' => 127,
                'leads' => 23,
                'classes_today' => 8,
                'revenue_this_month' => 15420,
                'expiring_soon' => 5,
            ];
        }

        $orgId = auth()->user()->organization_id ?? 1;

        return [
            'members' => Member::where('organization_id', $orgId)->where('status', 'active')->count(),
            'leads' => Lead::where('organization_id', $orgId)->where('status', 'new')->count(),
            'classes_today' => ScheduledClass::whereDate('start_time', today())->count(),
            'revenue_this_month' => Payment::where('organization_id', $orgId)
                ->where('status', 'completed')
                ->whereMonth('paid_at', now()->month)
                ->sum('amount'),
            'expiring_soon' => Member::where('organization_id', $orgId)
                ->where('status', 'active')
                ->whereBetween('expiry_date', [now(), now()->addDays(7)])
                ->count(),
        ];
    }
}
