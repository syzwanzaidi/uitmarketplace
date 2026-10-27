<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerOrderController extends Controller
{
    /** Tab key => order status shown under it. */
    public const TABS = [
        'to_ship' => Order::STATUS_PAID,
        'shipped' => Order::STATUS_SHIPPED,
        'completed' => Order::STATUS_COMPLETED,
    ];

    public const COURIERS = ['Pos Laju', 'J&T Express', 'Ninja Van', 'GDex', 'DHL eCommerce', 'Shopee Xpress', 'Self pickup / meetup'];

    public function index(Request $request): View
    {
        $tab = array_key_exists($request->query('tab'), self::TABS) ? $request->query('tab') : 'to_ship';
        $orderId = Order::idFromSearch($request->query('search'));

        $orders = Order::with(['items.product', 'buyer'])
            ->where('seller_id', auth()->id())
            ->where('status', self::TABS[$tab])
            ->when($orderId, fn ($q) => $q->whereKey($orderId))
            ->latest('paid_at')
            ->paginate(8)
            ->withQueryString();

        $counts = Order::where('seller_id', auth()->id())
            ->whereIn('status', self::TABS)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('seller.orders', [
            'orders' => $orders,
            'tab' => $tab,
            'counts' => collect(self::TABS)->map(fn ($status) => $counts[$status] ?? 0),
            'couriers' => self::COURIERS,
        ]);
    }

    public function ship(Request $request, Order $order): RedirectResponse
    {
        abort_if($order->seller_id !== auth()->id(), 403);

        if (! in_array($order->status, [Order::STATUS_PAID, Order::STATUS_SHIPPED], true)) {
            return back()->with('error', 'Only paid orders can be shipped.');
        }

        $validated = $request->validate([
            'courier' => ['required', 'string', 'max:100'],
            'tracking_number' => ['required', 'string', 'max:255'],
        ]);

        $order->update([
            ...$validated,
            'status' => Order::STATUS_SHIPPED,
            'shipped_at' => $order->shipped_at ?? now(),
        ]);

        return back()->with('success', "Order #{$order->reference} marked as shipped.");
    }
}
