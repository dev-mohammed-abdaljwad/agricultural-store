<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$images = \App\Models\ProductImage::take(5)->get();
foreach ($images as $img) {
    echo "Product {$img->product_id}: {$img->url}\n";
}
