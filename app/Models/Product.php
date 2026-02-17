<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'organization_id', 'category_id', 'name', 'sku', 'price',
        'stock_quantity', 'low_stock_threshold', 'is_active'
    ];

    protected $casts = ['price' => 'decimal:2', 'is_active' => 'boolean'];

    public function organization() { return $this->belongsTo(Organization::class); }
    public function category() { return $this->belongsTo(ProductCategory::class); }
    public function transactions() { return $this->hasMany(InventoryTransaction::class); }

    public function isLowStock()
    {
        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function adjustStock($quantity, $type, $notes = '')
    {
        $this->stock_quantity += $quantity;
        $this->save();

        $this->transactions()->create([
            'user_id' => auth()->id(),
            'quantity_change' => $quantity,
            'type' => $type,
            'notes' => $notes,
        ]);
    }
}
