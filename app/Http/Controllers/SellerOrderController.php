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
    public function index(Request $request)
    {
        $sellerId = Auth::id();

        // ======================
        // ORDERS QUERY
        // ======================
        $ordersQuery = Order::with(['items.product', 'buyer'])
            ->where('seller_id', $sellerId)
            ->whereIn('status', ['paid', 'shipped']);

        // SEARCH BY ORDER ID
        if ($request->filled('search')) {
            $search = strtoupper($request->search);
            $search = str_replace('ORDER-', '', $search);

            $ordersQuery->where('id', $search);
        }

        // PAGINATION
        $orders = $ordersQuery
            ->latest()
            ->paginate(6)
            ->withQueryString();

        // ======================
        // DASHBOARD STATS (DO NOT TOUCH LOGIC)
        // ======================
        $totalSales = Order::where('seller_id', $sellerId)
            ->whereIn('status', ['paid', 'shipped', 'completed'])
            ->sum('total');

        $activeListingsCount = Product::where('user_id', $sellerId)
            ->where('status', 'active')
            ->count();

        $pendingOrdersCount = Order::where('seller_id', $sellerId)
            ->where('status', 'paid')
            ->whereNull('tracking_number')
            ->count();
        
        $completedOrdersCount = Order::where('seller_id', $sellerId)
            ->where('status', 'completed')
            ->count();

        return view('seller.orders', compact(
            'orders',
            'totalSales',
            'activeListingsCount',
            'pendingOrdersCount',
            'completedOrdersCount'
        ));
    }

    /**
     * Save / Update tracking number
     */
    public function updateTracking(Request $request, Order $order)
    {
        abort_if($order->seller_id !== Auth::id(), 403);

        $request->validate([
            'courier' => 'required|string|max:100',
            'tracking_number' => 'required|string|max:255',
        ]);

        $order->update([
            'courier' => $request->courier,
            'tracking_number' => $request->tracking_number,
            'status' => 'shipped',
        ]);

        return back()->with('success', 'Tracking information saved.');
    }

    /**
 * Seller Order History Page (COMPLETED ONLY)
 */
    public function history(Request $request)
    {
        $sellerId = Auth::id();

        // COMPLETED ORDERS ONLY
        $ordersQuery = Order::with(['items.product', 'buyer'])
            ->where('seller_id', $sellerId)
            ->where('status', 'completed');

        if ($request->filled('search')) {
            $search = strtoupper($request->search);
            $search = str_replace('ORDER-', '', $search);
            $ordersQuery->where('id', $search);
        }

        $orders = $ordersQuery
            ->latest()
            ->paginate(6)
            ->withQueryString();

        // DASHBOARD STATS
        $totalSales = Order::where('seller_id', $sellerId)
            ->whereIn('status', ['paid', 'shipped', 'completed'])
            ->sum('total');

        $activeListingsCount = Product::where('user_id', $sellerId)
            ->where('status', 'active')
            ->count();

        $pendingOrdersCount = Order::where('seller_id', $sellerId)
            ->where('status', 'paid')
            ->whereNull('tracking_number')
            ->count();

        $completedOrdersCount = Order::where('seller_id', $sellerId)
            ->where('status', 'completed')
            ->count();

        return view('seller.order-history', compact(
            'orders',
            'totalSales',
            'activeListingsCount',
            'pendingOrdersCount',
            'completedOrdersCount'
        ));
    }
}
