<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public const SORTS = [
        'latest' => 'Newest first',
        'nearest' => 'Nearest first',
        'price_asc' => 'Price: low to high',
        'price_desc' => 'Price: high to low',
    ];

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $categorySlug = $request->query('category');
        $location = LocationController::current();
        $sort = $request->query('sort', $location ? 'nearest' : 'latest');

        $query = Product::query()->with(['category', 'user'])->active();

        if ($search !== '') {
            $query->where(fn ($q) => $q
                ->where('products.name', 'like', "%{$search}%")
                ->orWhere('products.description', 'like', "%{$search}%"));
        }

        if ($categorySlug) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($location) {
            $query->withDistanceFrom($location['lat'], $location['lng'], $location['radius']);
        }

        match (true) {
            $sort === 'price_asc' => $query->orderBy('products.price'),
            $sort === 'price_desc' => $query->orderByDesc('products.price'),
            $sort === 'nearest' && $location !== null => $query->orderBy('distance'),
            default => $query->latest('products.created_at'),
        };

        return view('user.category', [
            'products' => $query->paginate(12)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'activeCategory' => Category::where('slug', $categorySlug)->first(),
            'search' => $search,
            'sort' => $sort,
            'sorts' => $location ? self::SORTS : array_diff_key(self::SORTS, ['nearest' => true]),
            'location' => $location,
        ]);
    }
}
