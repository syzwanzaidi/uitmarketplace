<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class BuyerOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with([
                'items.product',
                'seller'
            ])
            ->where('buyer_id', Auth::id())
            ->latest()
            ->get();

        return view('user.orders', compact('orders'));
    }

    public function markAsReceived(Order $order)
    {
        // Security: only the buyer can confirm
        abort_if($order->buyer_id !== Auth::id(), 403);

        // Only shipped orders can be completed
        if ($order->status !== 'shipped') {
            return back()->with('error', 'This order cannot be completed.');
        }

        $order->update([
            'status' => 'completed',
        ]);

        return back()->with('success', 'Order marked as completed.');
    }
}
