<?php

namespace Database\Seeders;

use App\Models\Crop;
use Illuminate\Database\Seeder;

class CropSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $crops = [
            ['name' => 'القمح', 'description' => 'محصول القمح'],
            ['name' => 'الذرة', 'description' => 'محصول الذرة'],
            ['name' => 'الأرز', 'description' => 'محصول الأرز'],
            ['name' => 'الحمص', 'description' => 'محصول الحمص'],
            ['name' => 'الفول', 'description' => 'محصول الفول'],
            ['name' => 'الطماطم', 'description' => 'محصول الطماطم'],
            ['name' => 'الخيار', 'description' => 'محصول الخيار'],
            ['name' => 'الفلفل', 'description' => 'محصول الفلفل'],
            ['name' => 'الكوسة', 'description' => 'محصول الكوسة'],
            ['name' => 'الباذنجان', 'description' => 'محصول الباذنجان'],
            ['name' => 'الملفوف', 'description' => 'محصول الملفوب'],
            ['name' => 'الجزر', 'description' => 'محصول الجزر'],
            ['name' => 'البصل', 'description' => 'محصول البصل'],
            ['name' => 'الثوم', 'description' => 'محصول الثوم'],
            ['name' => 'الفراولة', 'description' => 'محصول الفراولة'],
            ['name' => 'العنب', 'description' => 'محصول العنب'],
            ['name' => 'التفاح', 'description' => 'محصول التفاح'],
            ['name' => 'البرتقال', 'description' => 'محصول البرتقال'],
            ['name' => 'الليمون', 'description' => 'محصول الليمون'],
            ['name' => 'المانجو', 'description' => 'محصول المانجو'],
        ];

        foreach ($crops as $crop) {
            Crop::firstOrCreate(
                ['name' => $crop['name']],
                ['description' => $crop['description'], 'is_active' => true]
            );
        }
    }
}
