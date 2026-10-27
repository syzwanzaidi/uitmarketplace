<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Sample listings around UiTM Shah Alam so the location filter has data.
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = User::where('membership_type', User::TYPE_UITM)->orderBy('id')->get();
        $categories = Category::pluck('id', 'slug');

        if ($sellers->isEmpty() || $categories->isEmpty()) {
            return;
        }

        // [name, category slug, price, area, lat, lng, description]
        $items = [
            ['Casio fx-570EX scientific calculator', 'electronics', 45.00, 'Seksyen 2', 3.0698, 101.5037, "Used for 2 semesters. Works perfectly, comes with cover.\nAllowed in UiTM exams."],
            ['Calculus: Early Transcendentals (8th ed.)', 'books-notes', 60.00, 'Seksyen 7', 3.0733, 101.4980, 'Some highlighting in chapters 1–4. No missing pages.'],
            ['Study table with drawer', 'furniture', 80.00, 'Seksyen 13', 3.0781, 101.5129, 'Sturdy, minor scratches. Self pickup only — fits in a Myvi with seats down.'],
            ['Rice cooker 1L (Pensonic)', 'hostel-essentials', 35.00, 'Seksyen 2', 3.0675, 101.5011, 'Perfect for hostel. Inner pot non-stick still good.'],
            ['Baju kurung (size M, navy)', 'clothing', 40.00, 'Seksyen 7', 3.0720, 101.4995, 'Worn twice for faculty dinner. Dry cleaned.'],
            ['Logitech wireless mouse M331', 'electronics', 30.00, 'Seksyen 17', 3.0490, 101.5160, 'Silent clicks, battery included.'],
            ['Futsal shoes (Nike, UK 8)', 'shoes', 55.00, 'Seksyen 13', 3.0802, 101.5102, 'Used for one season. Good grip left.'],
            ['Programming notes bundle — CSC (Year 1)', 'books-notes', 15.00, 'Seksyen 2', 3.0702, 101.5049, 'Printed and bound notes for CSC110/CSC128/CSC138.'],
            ['Standing fan (16")', 'hostel-essentials', 45.00, 'Seksyen 18', 3.0651, 101.5302, '3 speeds, oscillating. Moving out sale.'],
            ['Badminton racket (Yonex) + shuttlecocks', 'sports', 70.00, 'Seksyen 7', 3.0745, 101.4962, 'Strung at 24 lbs. Comes with bag and 6 shuttles.'],
        ];

        foreach ($items as $i => [$name, $slug, $price, $area, $lat, $lng, $description]) {
            $product = Product::updateOrCreate(['name' => $name], [
                'user_id' => $sellers[$i % $sellers->count()]->id,
                'category_id' => $categories[$slug] ?? $categories->first(),
                'description' => $description,
                'price' => $price,
                'image' => 'https://picsum.photos/seed/uitm' . ($i + 1) . '/800/800',
                'location' => $area . ', Shah Alam',
                'status' => Product::STATUS_ACTIVE,
            ]);

            $product->locationPoint()->updateOrCreate([], ['latitude' => $lat, 'longitude' => $lng]);
        }
    }
}
