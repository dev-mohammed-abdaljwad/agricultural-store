<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;

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
                'name' => 'حصاد Admin',
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

      
        // call category seeder
        $this->call(CategorySeeder::class);
        
       
    }
}

