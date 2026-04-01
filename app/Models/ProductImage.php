<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'url', 'is_primary', 'sort_order'];

    protected $casts = ['is_primary' => 'boolean'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the full asset URL for the image
     */
    public function getAssetUrlAttribute(): string
    {
        $url = $this->url;
        
        // Ensure URL starts with /storage/
        if (!str_starts_with($url, '/storage/')) {
            // If it's just the filename path from Laravel's store()
            if (!str_starts_with($url, '/')) {
                $url = '/storage/' . $url;
            }
        }
        
        // Return full asset URL via Laravel's asset helper
        return asset($url);
    }

    /**
     * Get image URL (alias for backward compatibility)
     */
    public function getImageUrlAttribute(): string
    {
        return $this->getAssetUrlAttribute();
    }
}
