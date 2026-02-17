<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\AppNotification;
use Illuminate\Console\Command;

class CheckExpiringMemberships extends Command
{
    protected $signature = 'members:check-expiring {--days=7 : Days before expiry to notify}';
    protected $description = 'Check for expiring memberships and send notifications';

    public function handle()
    {
        $days = $this->option('days');
        
        $expiring = Member::where('status', 'active')
            ->whereBetween('expiry_date', [now(), now()->addDays($days)])
            ->get();

        $this->info("Found {$expiring->count()} memberships expiring in {$days} days");

        foreach ($expiring as $member) {
            AppNotification::membershipExpiring($member);
            $this->line("Notified: {$member->name} - expires {$member->expiry_date}");
        }

        return Command::SUCCESS;
    }
}
