<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Services\LeadAutomationService;
use Illuminate\Console\Command;

class ProcessLeadAutomation extends Command
{
    protected $signature = 'leads:process-automation';
    protected $description = 'Process AI automation follow-ups for leads';

    public function handle(): int
    {
        $this->info('Processing lead automation...');
        
        $leads = Lead::where('ai_enabled', true)
            ->whereNotNull('next_followup_at')
            ->where('next_followup_at', '<=', now())
            ->get();
        
        $this->info("Found {$leads->count()} leads to process.");
        
        foreach ($leads as $lead) {
            $organization = $lead->organization;
            $gymName = $organization?->name ?? 'Our Gym';
            
            $message = LeadAutomationService::processFollowUp($lead, $gymName);
            
            if ($message) {
                $this->info("Step {$lead->ai_sequence_step}: {$lead->name} - " . substr($message, 0, 50) . '...');
                
                // TODO: Send via SMS/Email based on lead preference
                // For now, just log it
                $lead->notes = ($lead->notes ?? '') . "\n[AI Follow-up @ " . now()->toDateTimeString() . "]: " . $message;
                $lead->save();
            }
        }
        
        $this->info('Done!');
        
        return Command::SUCCESS;
    }
}
