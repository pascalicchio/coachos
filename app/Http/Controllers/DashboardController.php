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
        // Demo data - works without DB
        return [
            'members' => 127,
            'leads' => 23,
            'classes_today' => 8,
            'revenue_this_month' => 15420,
            'expiring_soon' => 5,
        ];
    }
}
