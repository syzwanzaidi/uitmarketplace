<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        $user = auth()->user();

        // Hidden listings are only visible to their seller.
        abort_if($product->status === Product::STATUS_INACTIVE && $product->user_id !== $user->id, 404);

        $product->load(['category', 'user', 'locationPoint']);
        $location = LocationController::current();

        $distance = null;
        if ($location && $product->locationPoint) {
            $distance = Product::whereKey($product->id)
                ->withDistanceFrom($location['lat'], $location['lng'])
                ->value('distance');
        }

        $related = Product::with('category')
            ->active()
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->latest()
            ->take(4)
            ->get();

        return view('user.product-details', [
            'product' => $product,
            'distance' => $distance,
            'related' => $related,
            'isOwner' => $product->user_id === $user->id,
            'inCart' => Cart::where('user_id', $user->id)->where('product_id', $product->id)->exists(),
            'inWishlist' => Wishlist::where('user_id', $user->id)->where('product_id', $product->id)->exists(),
            'sellerListings' => $product->user->products()->active()->count(),
        ]);
    }
}
