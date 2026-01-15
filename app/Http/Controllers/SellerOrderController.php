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

        /**
         * ORDERS LIST (what seller sees)
         * Only orders that still need seller action
         */
        $orders = Order::with(['items.product', 'buyer'])
            ->where('seller_id', $sellerId)
            ->whereIn('status', ['paid', 'shipped'])
            ->latest()
            ->get();

        /**
         * TOTAL SALES
         * All successfully paid orders (DO NOT shrink)
         */
        $totalSales = Order::where('seller_id', $sellerId)
            ->whereIn('status', ['paid', 'shipped', 'completed'])
            ->sum('total');

        /**
         * ACTIVE LISTINGS
         */
        $activeListingsCount = Product::where('user_id', $sellerId)
            ->where('status', 'active')
            ->count();

        /**
         * PENDING ORDERS (paid but not shipped)
         */
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
            'status'          => 'shipped',
        ]);

        return back()->with('success', 'Tracking number saved and order marked as shipped.');
    }
}
