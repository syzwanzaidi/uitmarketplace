<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with([
                'items.product',
                'seller'
            ])
            ->where('buyer_id', Auth::id());

        // SEARCH BY ORDER ID
        if ($request->filled('search')) {
            $search = $request->search;

            // allow searching "12" or "ORDER-12"
            $search = str_replace('ORDER-', '', strtoupper($search));

            $query->where('id', $search);
        }

        // PAGINATION
        $orders = $query
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('user.orders', compact('orders'));
    }

    public function markAsReceived(Order $order)
    {
        abort_if($order->buyer_id !== Auth::id(), 403);

        if ($order->status !== 'shipped') {
            return back()->with('error', 'This order cannot be completed.');
        }

        $order->update([
            'status' => 'completed',
        ]);

        return back()->with('success', 'Order marked as completed.');
    }
}
