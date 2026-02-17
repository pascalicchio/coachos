<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    protected $fillable = ['member_id', 'scheduled_class_id', 'method', 'checked_in_by'];

    protected $casts = ['check_in_time' => 'datetime'];

    public function member() { return $this->belongsTo(Member::class); }
    public function scheduledClass() { return $this->belongsTo(ScheduledClass::class, 'scheduled_class_id'); }
    public function user() { return $this->belongsTo(User::class, 'checked_in_by'); }

    public static function checkIn($memberId, $classId = null, $method = 'qr')
    {
        return self::create([
            'member_id' => $memberId,
            'scheduled_class_id' => $classId,
            'method' => $method,
            'checked_in_by' => auth()->id(),
        ]);
    }

    public static function todayCount()
    {
        return self::whereDate('check_in_time', today())->count();
    }
}
