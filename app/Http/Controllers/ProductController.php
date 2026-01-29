<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request, $id)
    {
        $buyerLat = $request->query('lat');
        $buyerLng = $request->query('lng');

        $query = Product::query()
            ->with(['category', 'user', 'locationPoint'])
            ->where('products.id', $id);

        // Only calculate distance if buyer location exists
        if ($buyerLat && $buyerLng) {

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
                    $buyerLat,
                    $buyerLng,
                    $buyerLat,
                ]);
        }

        $product = $query->firstOrFail();

        return view('user.product-details', compact('product'));
    }
}
