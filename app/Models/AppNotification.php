<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $table = 'notifications';
    
    protected $fillable = [
        'organization_id', 'user_id', 'type', 'title', 'message', 'is_read', 'data'
    ];

    protected $casts = ['data' => 'array', 'is_read' => 'boolean'];

    public function organization() { return $this->belongsTo(Organization::class); }
    public function user() { return $this->belongsTo(User::class); }

    public static function notify($orgId, $type, $title, $message, $data = [])
    {
        return self::create([
            'organization_id' => $orgId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public static function membershipExpiring($member)
    {
        self::notify(
            $member->organization_id,
            'membership_expiring',
            'Membership Expiring',
            "{$member->name}'s membership expires in {$member->daysUntilExpiry()} days",
            ['member_id' => $member->id]
        );
    }
}
