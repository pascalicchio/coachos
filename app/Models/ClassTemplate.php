<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_name',
        'day_of_week',
        'start_time',
        'end_time',
        'coach_id',
        'location',
        'martial_art',
        'is_active',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function scheduledClasses()
    {
        return $this->hasMany(ScheduledClass::class, 'class_template_id');
    }
}
