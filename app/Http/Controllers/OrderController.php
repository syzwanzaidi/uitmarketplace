<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();

        // 1. Get selected cart items ONLY
        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->whereIn('id', $request->cart_items ?? [])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')
                ->with('error', 'No items selected for checkout.');
        }

        // 2. Group by seller (MARKETPLACE LOGIC)
        $grouped = $cartItems->groupBy('seller_id');

        foreach ($grouped as $sellerId => $items) {

            $total = $items->sum(fn($i) => $i->product->price);

            // 3. Create order (ONE seller = ONE order)
            $order = Order::create([
                'buyer_id' => $user->id,
                'seller_id' => $sellerId,
                'total' => $total,
                'status' => 'pending',
            ]);

            // 4. Create order items
            foreach ($items as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'price' => $cart->product->price,
                ]);
            }
        }

        // 5. Remove purchased items from cart
        Cart::whereIn('id', $cartItems->pluck('id'))->delete();

        return redirect()->route('account.orders')
            ->with('success', 'Order placed successfully!');
    }
}
