<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\ScheduledClass;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function revenue()
    {
        $orgId = auth()->user()->organization_id;
        
        $revenue = Payment::where('organization_id', $orgId)
            ->where('status', 'completed')
            ->whereYear('paid_at', now()->year)
            ->selectRaw('MONTH(paid_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');
        
        return response()->json($revenue);
    }

    public function members()
    {
        $orgId = auth()->user()->organization_id;
        
        $members = Member::where('organization_id', $orgId)
            ->whereYear('join_date', now()->year)
            ->selectRaw('MONTH(join_date) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');
        
        return response()->json($members);
    }

    public function leads()
    {
        $orgId = auth()->user()->organization_id;
        
        $leads = Lead::where('organization_id', $orgId)
            ->whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');
        
        return response()->json($leads);
    }

    public function overview()
    {
        $orgId = auth()->user()->organization_id;
        
        return [
            'revenue' => [
                'this_month' => Payment::where('organization_id', $orgId)->where('status', 'completed')->whereMonth('paid_at', now()->month)->sum('amount'),
                'last_month' => Payment::where('organization_id', $orgId)->where('status', 'completed')->whereMonth('paid_at', now()->subMonth()->month)->sum('amount'),
                'this_year' => Payment::where('organization_id', $orgId)->where('status', 'completed')->whereYear('paid_at', now()->year)->sum('amount'),
            ],
            'members' => [
                'total' => Member::where('organization_id', $orgId)->count(),
                'active' => Member::where('organization_id', $orgId)->where('status', 'active')->count(),
                'new_this_month' => Member::where('organization_id', $orgId)->whereMonth('join_date', now()->month)->count(),
            ],
            'leads' => [
                'total' => Lead::where('organization_id', $orgId)->count(),
                'new_this_month' => Lead::where('organization_id', $orgId)->whereMonth('created_at', now()->month)->count(),
                'converted' => Lead::where('organization_id', $orgId)->where('status', 'converted')->count(),
            ],
            'classes' => [
                'this_week' => ScheduledClass::whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            ],
        ];
    }
}
