<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // 1. Validate selection exists
        if (!$request->has('cart_items') || empty($request->cart_items)) {
            return redirect()
                ->route('cart')
                ->with('error', 'Please select at least one item to checkout.');
        }

        // 2. Get ONLY selected cart items (belonging to user)
        $cartItems = Cart::with('product.user')
            ->where('user_id', auth()->id())
            ->whereIn('id', $request->cart_items)
            ->get();

        // 3. If nothing valid (tampered request)
        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('cart')
                ->with('error', 'Invalid cart selection.');
        }

        // 4. Validate cart items (product / seller still exist)
        foreach ($cartItems as $cart) {

            if (!$cart->product || !$cart->product->user) {
                $cart->delete();
                return redirect()
                    ->route('cart')
                    ->with('error', 'Some items are no longer available.');
            }
        }

        // 5. Group selected items by seller
        $grouped = $cartItems->groupBy('seller_id');

        // 🚫 NOT creating orders yet
        // 🚫 NOT payment yet

        return view('user.checkout', compact('grouped'));
    }
}
