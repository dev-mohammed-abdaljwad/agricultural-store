<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Cart belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cart item references a product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get total price for this cart item
     */
    public function getTotalAttribute()
    {
        return $this->quantity * ($this->product->base_price ?? 0);
    }
}
