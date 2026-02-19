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
        // Get organization_id from logged in user
        $orgId = session()->get('organization_id', 1);

        try {
            return [
                'members' => \App\Models\Member::where('organization_id', $orgId)->where('status', 'active')->count() ?? 0,
                'leads' => \App\Models\Lead::where('organization_id', $orgId)->where('status', 'new')->count() ?? 0,
                'classes_today' => \App\Models\ScheduledClass::where('organization_id', $orgId)->whereDate('start_time', today())->count() ?? 0,
                'revenue_this_month' => \App\Models\Payment::where('organization_id', $orgId)
                    ->where('status', 'completed')
                    ->whereMonth('payment_date', now()->month)
                    ->sum('amount') ?? 0,
                'expiring_soon' => \App\Models\Member::where('organization_id', $orgId)
                    ->where('status', 'active')
                    ->whereBetween('expiry_date', [now(), now()->addDays(7)])
                    ->count() ?? 0,
            ];
        } catch (\Exception $e) {
            // Return error for debugging (v2)
            return [
                'error' => 'DEBUG: ' . $e->getMessage(),
                'members' => -1, // Use -1 to clearly see it's error state
                'leads' => -1,
                'classes_today' => -1,
                'revenue_this_month' => -1,
                'expiring_soon' => -1,
            ];
        }
    }
}
