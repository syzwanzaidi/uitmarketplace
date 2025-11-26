<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            [
                'user_id' => 1,
                'category_id' => 1,
                'name' => 'Basic Cotton T-Shirt',
                'description' => 'Soft, breathable cotton T-shirt suitable for daily wear.',
                'price' => 29.90,
                'image' => 'https://picsum.photos/600/600?random=1',
                'location' => 'Shah Alam',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'category_id' => 2,
                'name' => 'Wireless Earbuds',
                'description' => 'Bluetooth earbuds with noise cancellation.',
                'price' => 89.00,
                'image' => 'https://picsum.photos/600/600?random=2',
                'location' => 'Kuala Lumpur',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'category_id' => 3,
                'name' => 'The Art of Coding',
                'description' => 'Beginner-friendly guide to programming.',
                'price' => 45.50,
                'image' => 'https://picsum.photos/600/600?random=3',
                'location' => 'Penang',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
