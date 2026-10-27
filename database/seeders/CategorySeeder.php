<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Books & Notes',
            'Electronics',
            'Clothing',
            'Shoes',
            'Furniture',
            'Hostel Essentials',
            'Accessories',
            'Sports',
            'Beauty',
            'Others',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }
    }
}
