<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'organization_id', 'member_id', 'invoice_number', 'subtotal',
        'tax', 'total', 'status', 'due_date', 'paid_at', 'notes'
    ];

    protected $casts = ['due_date' => 'date', 'paid_at' => 'datetime'];

    public function organization() { return $this->belongsTo(Organization::class); }
    public function member() { return $this->belongsTo(Member::class); }
    public function items() { return $this->hasMany(InvoiceItem::class); }

    public static function generateNumber()
    {
        $last = self::orderBy('id', 'desc')->first();
        $num = $last ? $last->id + 1 : 1;
        return 'INV-' . date('Y') . '-' . str_pad($num, 5, '0', STR_PAD_LEFT);
    }

    public function markAsPaid()
    {
        $this->update(['status' => 'paid', 'paid_at' => now()]);
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->items->sum('total');
        $this->total = $this->subtotal + $this->tax;
        $this->save();
    }
}

class InvoiceItem extends Model
{
    protected $table = 'invoice_items';
    
    protected $fillable = ['invoice_id', 'description', 'quantity', 'unit_price', 'total'];
    protected $casts = ['total' => 'decimal:2', 'unit_price' => 'decimal:2'];

    public function invoice() { return $this->belongsTo(Invoice::class); }

    public function setTotalAttribute()
    {
        $this->attributes['total'] = $this->quantity * $this->unit_price;
    }
}
