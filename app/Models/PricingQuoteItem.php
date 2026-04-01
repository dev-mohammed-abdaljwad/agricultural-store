<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingQuoteItem extends Model
{
    protected $fillable = [
        'pricing_quote_id', 'order_item_id',
        'unit_price', 'total_price',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function pricingQuote()
    {
        return $this->belongsTo(PricingQuote::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
