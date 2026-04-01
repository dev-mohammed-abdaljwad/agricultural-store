<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingQuote extends Model
{
    protected $fillable = [
        'order_id', 'quoted_by', 'delivery_fee',
        'total_amount', 'notes', 'expires_at', 'status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'delivery_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function quotedBy()
    {
        return $this->belongsTo(User::class, 'quoted_by');
    }

    public function items()
    {
        return $this->hasMany(PricingQuoteItem::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
