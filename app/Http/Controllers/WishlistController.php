<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;

class WishlistController extends Controller
{
    /** Save the item if it isn't saved yet, otherwise remove it. */
    public function toggle(Product $product): RedirectResponse
    {
        $attributes = ['user_id' => auth()->id(), 'product_id' => $product->id];

        if (Wishlist::where($attributes)->delete()) {
            return back()->with('success', 'Removed from your wishlist.');
        }

        if ($product->user_id === auth()->id()) {
            return back()->with('error', 'You cannot save your own listing.');
        }

        Wishlist::create($attributes);

        return back()->with('success', 'Saved to your wishlist.');
    }
}
