<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomationTemplate extends Model
{
    protected $fillable = [
        'organization_id',
        'trigger',
        'step',
        'subject',
        'message',
        'delay_days',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'delay_days' => 'integer',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get default templates
     */
    public static function defaults(): array
    {
        return [
            ['trigger' => 'welcome', 'step' => 0, 'delay_days' => 0,
                'subject' => 'Welcome to {{gym_name}}!',
                'message' => "Hey {{name}}! 👋\n\nThanks for checking out {{gym_name}}! We're super excited to help you start your BJJ journey.\n\nWhat brings you to BJJ? Any specific goals?"],
            ['trigger' => 'followup', 'step' => 3, 'delay_days' => 3,
                'subject' => 'Still have questions?',
                'message' => "Hey {{name}}! 👋\n\nJust wanted to follow up and see if you had any questions about getting started.\n\nWe offer a free trial class!"],
            ['trigger' => 'offer', 'step' => 7, 'delay_days' => 7,
                'subject' => 'Ready to try BJJ?',
                'message' => "Hey {{name}}! 🌍\n\nJust checking in! BJJ is amazing.\n\nOur next beginner class is this week. Want me to save you a spot?"],
            ['trigger' => 'urgency', 'step' => 14, 'delay_days' => 14,
                'subject' => 'Last chance this month!',
                'message' => "Hey {{name}}! ⏰\n\nThis month is almost over! Would you like to come in for a free intro session?"],
            ['trigger' => 'final', 'step' => 21, 'delay_days' => 21,
                'subject' => 'Ready when you are!',
                'message' => "Hey {{name}}! ✨\n\nWe're here whenever you're ready! Reply when you want to try BJJ!"],
        ];
    }

    /**
     * Seed default templates for an organization
     */
    public static function seedForOrganization($organizationId)
    {
        foreach (self::defaults() as $template) {
            self::firstOrCreate(
                ['organization_id' => $organizationId, 'step' => $template['step']],
                array_merge($template, ['organization_id' => $organizationId, 'is_active' => true])
            );
        }
    }
}
