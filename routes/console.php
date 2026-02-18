<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// AI Lead Automation - runs every hour
Artisan::command('leads:process-automation', function () {
    \App\Models\Lead::where('ai_enabled', true)
        ->whereNotNull('next_followup_at')
        ->where('next_followup_at', '<=', now())
        ->each(function ($lead) {
            $organization = $lead->organization;
            $gymName = $organization?->name ?? 'Our Gym';
            \App\Services\LeadAutomationService::processFollowUp($lead, $gymName);
        });
})->purpose('Process AI lead follow-ups')->hourly();
