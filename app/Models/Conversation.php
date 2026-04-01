<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['order_id', 'customer_id', 'last_message_at'];

    protected $casts = ['last_message_at' => 'datetime'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function unreadByAdmin()
    {
        return $this->hasMany(Message::class)
            ->where('sender_type', 'customer')
            ->where('is_read', false);
    }
}
