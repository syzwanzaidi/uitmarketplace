<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product', 'buyer'])
            ->where('seller_id', Auth::id())
            ->where('status', 'paid')
            ->latest()
            ->get();

        return view('seller.orders', compact('orders'));
    }

    public function updateTracking(Request $request, Order $order)
    {
        abort_if($order->seller_id !== auth()->id(), 403);

        $request->validate([
            'tracking_number' => 'required|string|max:255'
        ]);

        $order->update([
            'tracking_number' => $request->tracking_number,
        ]);

        return back()->with('success', 'Tracking number saved.');
    }

}
