<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number', 'customer_id', 'status',
        'delivery_fee', 'total_amount',
        'payment_method', 'payment_status',
        'supplier_ref', 'admin_notes',
        'delivery_address', 'delivery_governorate',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->order_number = 'NH-' . date('Y') . '-' . str_pad(
                (string)(static::withTrashed()->count() + 1), 4, '0', STR_PAD_LEFT
            );
        });
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function quotes()
    {
        return $this->hasMany(PricingQuote::class);
    }

    public function quote()
    {
        return $this->hasOne(PricingQuote::class)->latest();
    }

    public function activeQuote()
    {
        return $this->hasOne(PricingQuote::class)->where('status', 'pending')->latest();
    }

    public function tracking()
    {
        return $this->hasMany(OrderTracking::class)->orderBy('occurred_at', 'desc');
    }

    public function conversation()
    {
        return $this->hasOne(Conversation::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
}
