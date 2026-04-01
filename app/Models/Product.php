<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'description', 'unit',
        'min_order_qty', 'is_certified',
        'data_sheet_url', 'usage_instructions',
        'safety_instructions', 'manufacturer_info',
        'expert_tip', 'expert_name', 'expert_title', 'expert_image_url',
        'supplier_name', 'supplier_code',
        'status',
    ];

    protected $casts = ['is_certified' => 'boolean'];

    protected $hidden = ['supplier_name', 'supplier_code'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function specs()
    {
        return $this->hasMany(ProductSpec::class)->orderBy('sort_order');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
