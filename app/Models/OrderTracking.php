<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTracking extends Model
{
    protected $fillable = ['order_id', 'status', 'title', 'description', 'occurred_at'];

    protected $casts = ['occurred_at' => 'datetime'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
