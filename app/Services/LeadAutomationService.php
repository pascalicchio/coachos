<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class LeadAutomationService
{
    /**
     * AI Follow-up sequence templates
     * Days: 0, 3, 7, 14, 21
     */
    public static function getSequenceTemplates(): array
    {
        return [
            0 => [
                'subject' => 'Welcome to {{gym_name}}!',
                'message' => "Hey {{name}}! 👋\n\nThanks for checking out {{gym_name}}! We're super excited to help you start your BJJ journey.\n\nHere at {{gym_name}}, we train people of all ages and fitness levels. Whether you've never rolled or you're a seasoned grappler, we've got you covered.\n\nWhat brings you to BJJ? Any specific goals you're looking to achieve?",
                'delay_days' => 0,
            ],
            3 => [
                'subject' => 'Still have questions?',
                'message' => "Hey {{name}}! 👋\n\nJust wanted to follow up and see if you had any questions about getting started at {{gym_name}}.\n\nWe offer a free trial class so you can experience what we're about before committing. No pressure at all!\n\nWhat goals would you like to achieve with BJJ? Self-defense? Fitness? Competition?",
                'delay_days' => 3,
            ],
            7 => [
                'subject' => 'Ready to try BJJ?',
                'message' => "Hey {{name}}! 🌍\n\nJust checking in! BJJ is one of the most rewarding skills you can learn - and it's genuinely fun.\n\nOur next beginner-friendly class is coming up this week. Want me to save you a spot?\n\nNo gi needed for your first class - just show up in comfortable athletic clothes!",
                'delay_days' => 7,
            ],
            14 => [
                'subject' => 'Last chance to start this month!',
                'message' => "Hey {{name}}! ⏰\n\nThis month is almost over, but it's never too late to start your BJJ journey!\n\nWe have limited spots in our upcoming beginner program. Would you like to come in for a free intro session?\n\nJust reply with your availability and we'll make it happen!",
                'delay_days' => 14,
            ],
            21 => [
                'subject' => 'Ready when you are!',
                'message' => "Hey {{name}}! ✨\n\nI haven't heard from you in a while, but I wanted to let you know we're here whenever you're ready!\n\nBJJ changes lives - improved confidence, better fitness, real self-defense skills. We'd love to be part of your journey.\n\nNo rush. Just reply when you're ready to give it a try!",
                'delay_days' => 21,
            ],
        ];
    }

    /**
     * Generate personalized message based on lead info
     */
    public static function generateMessage(int $step, array $lead, string $gymName): string
    {
        $templates = self::getSequenceTemplates();
        $template = $templates[$step] ?? $templates[0];
        
        $message = $template['message'];
        $message = str_replace('{{name}}', explode(' ', $lead['name'])[0], $message);
        $message = str_replace('{{gym_name}}', $gymName, $message);
        
        // Add personalization based on lead goals if available
        if (!empty($lead['goals'])) {
            $message .= "\n\nWe saw you're interested in: " . $lead['goals'];
        }
        
        return $message;
    }

    /**
     * Get next step in sequence
     */
    public static function getNextStep(int $currentStep): ?int
    {
        $templates = self::getSequenceTemplates();
        $nextKeys = array_keys($templates);
        sort($nextKeys);
        
        foreach ($nextKeys as $key) {
            if ($key > $currentStep) {
                return $key;
            }
        }
        
        return null; // Sequence complete
    }

    /**
     * Process AI follow-up for a lead
     */
    public static function processFollowUp($lead, string $gymName): ?string
    {
        if (!$lead->ai_enabled) {
            return null;
        }
        
        $currentStep = $lead->ai_sequence_step ?? 0;
        $nextStep = self::getNextStep($currentStep);
        
        if ($nextStep === null) {
            // Sequence complete
            return null;
        }
        
        // Check if it's time for next follow-up
        if ($lead->next_followup_at && now()->lt($lead->next_followup_at)) {
            return null;
        }
        
        // Generate message
        $message = self::generateMessage($nextStep, [
            'name' => $lead->name,
            'goals' => $lead->goals,
        ], $gymName);
        
        // Update lead
        $lead->update([
            'ai_sequence_step' => $nextStep,
            'next_followup_at' => now()->addDays(self::getSequenceTemplates()[$nextStep]['delay_days']),
            'last_contacted_at' => now(),
        ]);
        
        return $message;
    }

    /**
     * Generate AI response to lead question
     */
    public static function generateAIResponse(string $question, array $lead, string $gymName): string
    {
        // For now, use template-based responses
        // Later, integrate with OpenAI for smarter responses
        
        $question = strtolower($question);
        
        if (str_contains($question, 'price') || str_contains($question, 'cost') || str_contains($question, 'how much')) {
            return "Great question! Our membership plans start at just $19/month for the basic plan. We also have Pro ($39) and Business ($79) options depending on your goals. Would you like me to send you the full details?";
        }
        
        if (str_contains($question, 'schedule') || str_contains($question, 'when')) {
            return "We have classes throughout the day! Morning, afternoon, and evening sessions available. What time works best for you? I can send you our full schedule.";
        }
        
        if (str_contains($question, 'beginner') || str_contains($question, 'never') || str_contains($question, 'experience')) {
            return "Perfect for beginners! We love teaching people who have never done BJJ before. Our instructors are patient and experienced in working with complete newcomers. You'll be learning in a supportive environment!";
        }
        
        if (str_contains($question, 'kids') || str_contains($question, 'child') || str_contains($question, 'young')) {
            return "Yes! We have youth programs for kids ages 4+. It's a great way to build confidence, discipline, and fitness. Would you like info on our kids' schedule?";
        }
        
        // Default response
        return "That's a great question! I'd be happy to help. Would you like to schedule a free trial class so we can chat in person?";
    }
}
