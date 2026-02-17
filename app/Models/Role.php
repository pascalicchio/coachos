<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function hasPermission($permission)
    {
        $permissions = $this->permissions ?? [];
        
        // Check if permission exists in any role permissions
        foreach ($permissions as $resource => $actions) {
            if (in_array($permission, $actions)) {
                return true;
            }
        }
        
        return false;
    }
}
