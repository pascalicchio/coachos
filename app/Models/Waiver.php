<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waiver extends Model
{
    protected $fillable = [
        'organization_id', 'member_id', 'title', 'content',
        'is_signed', 'signed_at', 'ip_address'
    ];

    protected $casts = ['signed_at' => 'datetime', 'is_signed' => 'boolean'];

    public function organization() { return $this->belongsTo(Organization::class); }
    public function member() { return $this->belongsTo(Member::class); }

    public function sign($ipAddress = null)
    {
        $this->update([
            'is_signed' => true,
            'signed_at' => now(),
            'ip_address' => $ipAddress,
        ]);
    }
}
