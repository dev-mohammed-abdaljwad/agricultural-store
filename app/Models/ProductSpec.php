<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpec extends Model
{
    protected $fillable = ['product_id', 'key', 'value', 'sort_order'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
