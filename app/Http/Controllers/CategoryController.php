<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $search       = $request->search;
        $categorySlug = $request->category;
        $sort         = $request->sort;

        $lat    = $request->lat;
        $lng    = $request->lng;
        $radius = $request->radius ?? 10;

        $query = Product::query()
            ->with(['category', 'user'])
            ->where('status', 'active');

        /* ---------- SEARCH ---------- */
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        /* ---------- CATEGORY ---------- */
        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        /* ---------- LOCATION FILTER ---------- */
        if ($lat && $lng) {

            $haversine = "
                (6371 * acos(
                    cos(radians(?)) *
                    cos(radians(locations.latitude)) *
                    cos(radians(locations.longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(locations.latitude))
                ))
            ";

            $query
                ->join('locations', 'products.id', '=', 'locations.product_id')
                ->select('products.*')
                ->selectRaw("$haversine AS distance", [
                    $lat,
                    $lng,
                    $lat,
                ])
                ->whereRaw("$haversine <= ?", [
                    $lat,
                    $lng,
                    $lat,
                    $radius,
                ])
                ->orderBy('distance');
        }

        /* ---------- SORT ---------- */
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif (!$lat) {
            // only default sort when NOT using distance
            $query->latest();
        }

        /* ---------- PAGINATION ---------- */
        $products = $query->paginate(12)->withQueryString();

        return view('user.category', compact(
            'products',
            'categories',
            'search',
            'categorySlug',
            'sort'
        ));
    }
}
