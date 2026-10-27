<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Review page for the selected cart items: GET /checkout?items[]=1&items[]=2
     */
    public function index(Request $request): View|RedirectResponse
    {
        $items = self::selectedItems($request->input('items', []));

        if ($items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Select at least one item to check out.');
        }

        if ($unavailable = $items->first(fn (Cart $item) => CartController::purchaseError($item->product))) {
            return redirect()->route('cart')
                ->with('error', "\"{$unavailable->product->name}\" is no longer available. Please remove it to continue.");
        }

        return view('user.checkout', [
            'grouped' => $items->groupBy('seller_id'),
            'total' => $items->sum(fn (Cart $item) => $item->product->price),
            'user' => $request->user(),
        ]);
    }

    /**
     * Skip the cart: make sure the item is in it, then go straight to checkout
     * for that one item. The rest of the cart is left untouched.
     */
    public function buyNow(Product $product): RedirectResponse
    {
        if ($error = CartController::purchaseError($product)) {
            return back()->with('error', $error);
        }

        $item = Cart::firstOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $product->id],
            ['seller_id' => $product->user_id],
        );

        return redirect()->route('checkout', ['items' => [$item->id]]);
    }

    /** The current user's cart rows for the given ids, with live product data. */
    public static function selectedItems(array $ids): Collection
    {
        return Cart::with('product.user')
            ->where('user_id', auth()->id())
            ->whereIn('id', array_filter($ids, 'is_numeric'))
            ->get()
            ->filter(fn (Cart $item) => $item->product && $item->product->user)
            ->values();
    }
}
