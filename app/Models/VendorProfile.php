<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'commercial_register',
        'governorate',
        'address',
        'primary_category',
        'status',
    ];

    /**
     * Get the user that owns this vendor profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
