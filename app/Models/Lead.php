<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'email',
        'phone',
        'source',
        'status',
        'notes',
        'last_contacted_at',
        'ai_enabled',
        'ai_sequence_step',
        'next_followup_at',
        'goals',
    ];

    protected $casts = [
        'last_contacted_at' => 'datetime',
        'next_followup_at' => 'datetime',
        'ai_enabled' => 'boolean',
        'ai_sequence_step' => 'integer',
    ];

    // AI Automation statuses
    const STATUS_NEW = 'new';
    const STATUS_CONTACTED = 'contacted';
    const STATUS_NURTURING = 'nurturing';
    const STATUS_INTERESTED = 'interested';
    const STATUS_CONVERTED = 'converted';
    const STATUS_LOST = 'lost';

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function schedules()
    {
        return $this->hasMany(LeadSchedule::class);
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeConverted($query)
    {
        return $query->where('status', 'converted');
    }
}
