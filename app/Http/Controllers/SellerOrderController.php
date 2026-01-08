<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerOrderController extends Controller
{
    /**
     * Seller Orders Page
     */
    public function index()
    {
        $sellerId = Auth::id();

        // Paid orders for this seller
        $orders = Order::with(['items.product', 'buyer'])
            ->where('seller_id', $sellerId)
            ->whereIn('status', ['paid', 'shipped'])
            ->latest()
            ->get();

        // Total sales (sum of paid orders)
        $totalSales = $orders->sum('total');

        // Active listings
        $activeListingsCount = Product::where('user_id', $sellerId)
            ->where('status', 'active')
            ->count();

        // Pending orders (paid but no tracking yet)
        $pendingOrdersCount = Order::where('seller_id', $sellerId)
            ->where('status', 'paid')
            ->whereNull('tracking_number')
            ->count();

        return view('seller.orders', compact(
            'orders',
            'totalSales',
            'activeListingsCount',
            'pendingOrdersCount'
        ));
    }

    /**
     * Save / Update tracking number
     */
    public function updateTracking(Request $request, Order $order)
    {
        // Security: only owner seller
        abort_if($order->seller_id !== Auth::id(), 403);

        $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        $order->update([
            'tracking_number' => $request->tracking_number,
            'status'          => 'shipped', //  auto mark as shipped
        ]); 

        return back()->with('success', 'Tracking number saved and order marked as shipped.');
    }
}
