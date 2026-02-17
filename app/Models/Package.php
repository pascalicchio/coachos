<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['organization_id', 'name', 'type', 'quantity', 'price', 'validity_days', 'is_active'];
    protected $casts = ['price' => 'decimal:2', 'is_active' => 'boolean'];

    public function organization() { return $this->belongsTo(Organization::class); }
    public function purchases() { return $this->hasMany(PackagePurchase::class); }
}

class PackagePurchase extends Model
{
    protected $table = 'package_purchases';
    
    protected $fillable = ['organization_id', 'member_id', 'package_id', 'remaining_uses', 'expires_at'];
    protected $casts = ['expires_at' => 'date'];

    public function organization() { return $this->belongsTo(Organization::class); }
    public function member() { return $this->belongsTo(Member::class); }
    public function package() { return $this->belongsTo(Package::class); }

    public function useOne()
    {
        if ($this->remaining_uses > 0) {
            $this->remaining_uses--;
            $this->save();
            return true;
        }
        return false;
    }

    public function isValid()
    {
        if ($this->remaining_uses <= 0) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        return true;
    }
}
