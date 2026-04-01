<?php
require_once 'bootstrap/app.php';

use App\Models\ProductImage;

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$image = ProductImage::first();
if ($image) {
    echo "URL property: " . $image->url . "\n";
    echo "Asset URL: " . $image->asset_url . "\n";
    echo "Image URL: " . $image->image_url . "\n";
} else {
    echo "No product images found\n";
}
