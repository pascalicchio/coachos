<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invitation extends Model
{
    protected $fillable = ['organization_id', 'invited_by', 'email', 'role', 'token', 'expires_at', 'is_used'];

    protected $casts = ['expires_at' => 'datetime', 'is_used' => 'boolean'];

    public function organization() { return $this->belongsTo(Organization::class); }
    public function inviter() { return $this->belongsTo(User::class, 'invited_by'); }

    public static function invite($orgId, $email, $role, $invitedBy)
    {
        return self::create([
            'organization_id' => $orgId,
            'email' => $email,
            'role' => $role,
            'invited_by' => $invitedBy,
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
        ]);
    }

    public function isValid()
    {
        return !$this->is_used && $this->expires_at->isFuture();
    }
}
