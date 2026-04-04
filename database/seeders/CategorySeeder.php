<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'حشري',
                'icon' => '🐛',
                'is_active' => true,
                'parent_id' => null,
            ],
            [
                'name' => 'فطري',
                'icon' => '🍄',
                'is_active' => true,
                'parent_id' => null,
            ],
            [
                'name' => 'مغذيات',
                'icon' => '💊',
                'is_active' => true,
                'parent_id' => null,
            ],
            [
                'name' => 'نيماتودا',
                'icon' => '🪱',
                'is_active' => true,
                'parent_id' => null,
            ],
            [
                'name' => 'حشائش',
                'icon' => '🌾',
                'is_active' => true,
                'parent_id' => null,
            ],
            [
                'name' => 'أسمدة',
                'icon' => '🥕',
                'is_active' => true,
                'parent_id' => null,
            ],
            [
                'name' => 'بذور',
                'icon' => '🌱',
                'is_active' => true,
                'parent_id' => null,
            ],
            [
                'name' => 'شتلات',
                'icon' => '🌿',
                'is_active' => true,
                'parent_id' => null,
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
