<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_name',
        'start_time',
        'end_time',
        'coach_id',
        'class_template_id',
        'location',
        'martial_art',
        'attendance_count',
        'is_cancelled',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'attendance_count' => 'integer',
        'is_cancelled' => 'boolean',
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function classTemplate()
    {
        return $this->belongsTo(ClassTemplate::class, 'class_template_id');
    }
}
