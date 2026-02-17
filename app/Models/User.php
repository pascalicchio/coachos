<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCoach()
    {
        return in_array($this->role, ['coach', 'admin']);
    }

    public function classTemplates()
    {
        return $this->hasMany(ClassTemplate::class, 'coach_id');
    }

    public function scheduledClasses()
    {
        return $this->hasMany(ScheduledClass::class, 'coach_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function privateClassesAsStudent()
    {
        return $this->hasMany(PrivateClass::class, 'student_id');
    }

    public function privateClassesAsCoach()
    {
        return $this->hasMany(PrivateClass::class, 'coach_id');
    }
}
