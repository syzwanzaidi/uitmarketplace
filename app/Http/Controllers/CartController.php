<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product.user')
            ->where('user_id', auth()->id())
            ->get();

        // Group by seller
        $grouped = $cartItems->groupBy(function ($item) {
            return $item->product->user->id;
        });

        return view('user.cart', [
            'grouped' => $grouped,
        ]);
    }

    public function add(Product $product)
    {
        $user = auth()->user();

        // Prevent adding your own product
        if ($product->user_id === $user->id) {
            return back()->with('error', 'You cannot add your own product to cart.');
        }

        // Prevent duplicate cart items
        $exists = \App\Models\Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'This item is already in your cart.');
        }

        // Add to cart
        \App\Models\Cart::create([
            'user_id'    => $user->id,
            'seller_id'  => $product->user_id,
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Item added to cart!');
    }

    public function remove(Cart $cart)
    {
        // Make sure user can only remove their own cart items
        if ($cart->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $cart->delete();

        return back()->with('success', 'Item removed from cart.');
    }

}
