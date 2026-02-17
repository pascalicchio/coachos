<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'membership_id',
        'name',
        'email',
        'phone',
        'join_date',
        'expiry_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'join_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function daysUntilExpiry()
    {
        if (!$this->expiry_date) return null;
        return now()->diffInDays($this->expiry_date);
    }
}
