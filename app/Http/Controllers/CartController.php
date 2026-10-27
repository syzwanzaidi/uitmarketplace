<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $items = Cart::with(['product.user', 'product.category'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get()
            ->filter(fn (Cart $item) => $item->product && $item->product->user);

        return view('user.cart', [
            'grouped' => $items->groupBy(fn (Cart $item) => $item->product->user_id),
        ]);
    }

    public function add(Product $product): RedirectResponse
    {
        if ($error = self::purchaseError($product)) {
            return back()->with('error', $error);
        }

        $item = Cart::firstOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $product->id],
            ['seller_id' => $product->user_id],
        );

        return back()->with('success', $item->wasRecentlyCreated ? 'Added to your cart.' : 'This item is already in your cart.');
    }

    public function remove(Cart $cart): RedirectResponse
    {
        abort_if($cart->user_id !== auth()->id(), 403);

        $cart->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear(): RedirectResponse
    {
        Cart::where('user_id', auth()->id())->delete();

        return back()->with('success', 'Cart cleared.');
    }

    /** Why the current user cannot buy this product, or null if they can. */
    public static function purchaseError(Product $product): ?string
    {
        return match (true) {
            $product->user_id === auth()->id() => 'You cannot buy your own listing.',
            ! $product->isAvailable() => 'Sorry, this item is no longer available.',
            default => null,
        };
    }
}
