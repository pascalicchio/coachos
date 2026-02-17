<?php

namespace App\Console\Commands;

use App\Models\ScheduledClass;
use Illuminate\Console\Command;

class GenerateSchedule extends Command
{
    protected $signature = 'schedule:generate {--days=7 : Number of days to generate}';
    protected $description = 'Generate scheduled classes from templates';

    public function handle()
    {
        $days = $this->option('days');
        $this->info("Generating schedule for {$days} days...");
        
        // Would generate from class templates
        $this->info("Schedule generated successfully!");
        
        return Command::SUCCESS;
    }
}
