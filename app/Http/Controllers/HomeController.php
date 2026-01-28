<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Buyer location from query (will be sent by JS later)
        $buyerLat = $request->query('lat');
        $buyerLng = $request->query('lng');
        $radius   = $request->query('radius', 10); // default 10km

        $productsQuery = Product::query()
            ->with('category', 'locationPoint')
            ->where('status', 'active');

        /**
         * ===============================
         * APPLY LOCATION FILTER (STEP 4)
         * ===============================
         */
        if ($buyerLat && $buyerLng) {

            // Haversine formula (km)
            $haversine = "
                (6371 * acos(
                    cos(radians(?)) *
                    cos(radians(locations.latitude)) *
                    cos(radians(locations.longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(locations.latitude))
                ))
            ";

            $productsQuery
                ->join('locations', 'products.id', '=', 'locations.product_id')
                ->select('products.*')
                ->selectRaw("$haversine AS distance", [
                    $buyerLat,
                    $buyerLng,
                    $buyerLat,
                ])
                ->whereRaw("$haversine <= ?", [
                    $buyerLat,
                    $buyerLng,
                    $buyerLat,
                    $radius,
                ])
                ->orderBy('distance');
        } else {
            // No location yet → normal latest products
            $productsQuery->latest();
        }

        // Limit for homepage
        $products = $productsQuery
            ->take(20)
            ->get();

        return view('user.home', compact('products'));
    }
}
