<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSpec;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create or update admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@nileharvest.com'],
            [
                'name' => 'Nile Harvest Admin',
                'phone' => '+201001234567',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // Create sample customers
        $customers = [
            [
                'name' => 'Ahmed Hassan',
                'email' => 'ahmed@example.com',
                'phone' => '+201101234567',
                'customer_type' => 'farmer',
                'governorate' => 'Giza',
                'address' => '123 Main St, Giza',
            ],
            [
                'name' => 'Fatima Mohamed',
                'email' => 'fatima@example.com',
                'phone' => '+201201234567',
                'customer_type' => 'trader',
                'governorate' => 'Cairo',
                'address' => '456 Trade Ave, Cairo',
            ],
            [
                'name' => 'Ibrahim Khalil',
                'email' => 'ibrahim@example.com',
                'phone' => '+201301234567',
                'customer_type' => 'farmer',
                'governorate' => 'Beheira',
                'address' => '789 Farm Road, Beheira',
            ],
        ];

        foreach ($customers as $customerData) {
            User::updateOrCreate(
                ['email' => $customerData['email']],
                [
                    ...$customerData,
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'status' => 'active',
                ]
            );
        }

        // Create categories
        $categories = [
            ['name' => 'Vegetables', 'is_active' => true],
            ['name' => 'Fruits', 'is_active' => true],
            ['name' => 'Grains & Cereals', 'is_active' => true],
            ['name' => 'Dairy Products', 'is_active' => true],
        ];

        $createdCategories = [];
        foreach ($categories as $catData) {
            $createdCategories[] = Category::create($catData);
        }

        // Create products
        $products = [
            [
                'category_id' => $createdCategories[0]->id,
                'name' => 'Egyptian Tomatoes',
                'description' => 'Fresh, high-quality Egyptian tomatoes',
                'unit' => 'kg',
                'min_order_qty' => 5,
                'is_certified' => true,
                'data_sheet_url' => 'https://example.com/tomato-datasheet.pdf',
                'usage_instructions' => 'Use fresh for salads or cooking',
                'safety_instructions' => 'Wash before use',
                'manufacturer_info' => 'Grown in the Nile Delta',
                'expert_tip' => 'Best used within 3 days of delivery',
                'expert_name' => 'Dr. Ahmed',
                'expert_title' => 'Agricultural Expert',
                'expert_image_url' => 'https://example.com/expert1.jpg',
                'supplier_name' => 'Delta Farms',
                'supplier_code' => 'DF-001',
                'status' => 'active',
            ],
            [
                'category_id' => $createdCategories[0]->id,
                'name' => 'Bell Peppers',
                'description' => 'Colorful bell peppers',
                'unit' => 'kg',
                'min_order_qty' => 3,
                'is_certified' => true,
                'data_sheet_url' => null,
                'usage_instructions' => 'Remove seeds and use in cooking',
                'safety_instructions' => 'Wash thoroughly',
                'manufacturer_info' => 'Grown in Upper Egypt',
                'expert_tip' => 'Rich in Vitamin C',
                'expert_name' => 'Prof. Fatima',
                'expert_title' => 'Nutritionist',
                'expert_image_url' => 'https://example.com/expert2.jpg',
                'supplier_name' => 'Upper Egypt Foods',
                'supplier_code' => 'UEF-002',
                'status' => 'active',
            ],
            [
                'category_id' => $createdCategories[1]->id,
                'name' => 'Egyptian Oranges',
                'description' => 'Sweet and juicy oranges',
                'unit' => 'box',
                'min_order_qty' => 2,
                'is_certified' => false,
                'data_sheet_url' => null,
                'usage_instructions' => 'Fresh consumption or juice',
                'safety_instructions' => 'Peel before eating',
                'manufacturer_info' => 'Sourced from Qalyubia governorate',
                'expert_tip' => 'High in natural sugars',
                'expert_name' => 'Dr. Mostafa',
                'expert_title' => 'Food Safety Specialist',
                'expert_image_url' => 'https://example.com/expert3.jpg',
                'supplier_name' => 'Citrus Kingdom',
                'supplier_code' => 'CK-003',
                'status' => 'active',
            ],
        ];

        $createdProducts = [];
        foreach ($products as $prodData) {
            $createdProducts[] = Product::create($prodData);
        }

        // Add images to products
        foreach ($createdProducts as $product) {
            ProductImage::create([
                'product_id' => $product->id,
                'url' => 'https://example.com/product-' . $product->id . '-1.jpg',
                'is_primary' => true,
                'sort_order' => 1,
            ]);
            if (rand(0, 1)) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'url' => 'https://example.com/product-' . $product->id . '-2.jpg',
                    'is_primary' => false,
                    'sort_order' => 2,
                ]);
            }
        }

        // Add specs to products
        foreach ($createdProducts as $product) {
            ProductSpec::create([
                'product_id' => $product->id,
                'key' => 'Origin',
                'value' => 'Egypt',
                'sort_order' => 1,
            ]);
            ProductSpec::create([
                'product_id' => $product->id,
                'key' => 'Shelf Life',
                'value' => '7-10 days',
                'sort_order' => 2,
            ]);
            if (rand(0, 1)) {
                ProductSpec::create([
                    'product_id' => $product->id,
                    'key' => 'Organic',
                    'value' => 'Yes',
                    'sort_order' => 3,
                ]);
            }
        }
    }
}

