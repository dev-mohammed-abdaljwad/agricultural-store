<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasRole;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes, HasRole;

    protected $fillable = [
        'name', 'email', 'phone', 'password',
        'role', 'status', 'customer_type',
        'governorate', 'address',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['password' => 'hashed'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'customer_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function quotesCreated()
    {
        return $this->hasMany(PricingQuote::class, 'quoted_by');
    }
}
