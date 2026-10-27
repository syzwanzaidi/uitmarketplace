<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\PaymentSettlement;
use App\Services\ToyyibPay;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class OrderController extends Controller
{
    public function __construct(private readonly ToyyibPay $gateway)
    {
    }

    public function index(Request $request): View
    {
        $tab = $request->query('status', 'all');
        $orderId = Order::idFromSearch($request->query('search'));

        $orders = Order::with(['items.product', 'seller'])
            ->where('buyer_id', auth()->id())
            ->when($tab !== 'all', fn ($q) => $q->where('status', $tab))
            ->when($orderId, fn ($q) => $q->whereKey($orderId))
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('user.orders', compact('orders', 'tab'));
    }

    /**
     * Turn the selected cart items into one order per seller, all paid
     * together through a single ToyyibPay bill.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*' => ['integer'],
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]{9,20}$/'],
            'address1' => ['required', 'string', 'max:255'],
            'address2' => ['nullable', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:10'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'save_address' => ['nullable', 'boolean'],
        ], [
            'shipping_phone.regex' => 'Enter a valid phone number.',
        ]);

        $user = $request->user();
        $items = CheckoutController::selectedItems($validated['items']);

        if ($items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Select at least one item to check out.');
        }

        if ($unavailable = $items->first(fn (Cart $item) => CartController::purchaseError($item->product))) {
            return redirect()->route('cart')->with('error', "\"{$unavailable->product->name}\" is no longer available.");
        }

        if ($request->boolean('save_address')) {
            $user->update([
                'phone' => $validated['shipping_phone'],
                'address1' => $validated['address1'],
                'address2' => $validated['address2'] ?? null,
                'postcode' => $validated['postcode'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'country' => $user->country ?: 'Malaysia',
            ]);
        }

        $shipping = [
            'shipping_name' => $validated['shipping_name'],
            'shipping_phone' => $validated['shipping_phone'],
            'shipping_address' => collect([
                $validated['address1'],
                $validated['address2'] ?? null,
                trim($validated['postcode'] . ' ' . $validated['city']),
                $validated['state'],
            ])->filter()->implode(', '),
        ];

        $orders = $this->createOrders($user->id, $items, $shipping);

        return $this->redirectToPayment($orders, $validated['shipping_phone']);
    }

    /** Retry payment for an order that is still awaiting payment. */
    public function pay(Order $order, PaymentSettlement $settlement): RedirectResponse
    {
        abort_if($order->buyer_id !== auth()->id(), 403);

        // The previous bill may have been paid without us hearing about it.
        if ($order->bill_code && $order->status === Order::STATUS_PENDING) {
            $settlement->settle($order->bill_code);
            $order->refresh();
        }

        if ($order->status !== Order::STATUS_PENDING) {
            return back()->with('error', 'This order is not awaiting payment.');
        }

        $order->load('items.product');
        if ($order->items->contains(fn ($item) => ! $item->product?->isAvailable())) {
            $order->update(['status' => Order::STATUS_CANCELLED]);

            return back()->with('error', 'An item in this order has been sold or removed, so the order was cancelled.');
        }

        // Split from any sibling orders so this one gets its own bill.
        return $this->redirectToPayment(collect([$order]), $order->shipping_phone ?? auth()->user()->phone);
    }

    public function cancel(Order $order): RedirectResponse
    {
        abort_if($order->buyer_id !== auth()->id(), 403);

        if ($order->status !== Order::STATUS_PENDING) {
            return back()->with('error', 'Only unpaid orders can be cancelled.');
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        return back()->with('success', "Order #{$order->reference} cancelled.");
    }

    public function markAsReceived(Order $order): RedirectResponse
    {
        abort_if($order->buyer_id !== auth()->id(), 403);

        if ($order->status !== Order::STATUS_SHIPPED) {
            return back()->with('error', 'Only shipped orders can be marked as received.');
        }

        $order->update(['status' => Order::STATUS_COMPLETED, 'completed_at' => now()]);

        return back()->with('success', 'Thanks for confirming! The order is now complete.');
    }

    /**
     * @param  Collection<int, Cart>  $items
     * @return Collection<int, Order>
     */
    private function createOrders(int $buyerId, Collection $items, array $shipping): Collection
    {
        return DB::transaction(function () use ($buyerId, $items, $shipping) {
            $productIds = $items->pluck('product_id');

            // Abandoned attempts for the same items are superseded by this one.
            Order::where('buyer_id', $buyerId)
                ->where('status', Order::STATUS_PENDING)
                ->whereHas('items', fn ($q) => $q->whereIn('product_id', $productIds))
                ->update(['status' => Order::STATUS_CANCELLED]);

            return $items->groupBy('seller_id')->map(function (Collection $sellerItems, $sellerId) use ($buyerId, $shipping) {
                $order = Order::create([
                    'buyer_id' => $buyerId,
                    'seller_id' => $sellerId,
                    'total' => $sellerItems->sum(fn (Cart $item) => $item->product->price),
                    'status' => Order::STATUS_PENDING,
                    ...$shipping,
                ]);

                foreach ($sellerItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'price' => $item->product->price,
                    ]);
                }

                return $order;
            })->values();
        });
    }

    /** @param  Collection<int, Order>  $orders */
    private function redirectToPayment(Collection $orders, ?string $phone): RedirectResponse
    {
        $user = auth()->user();
        $reference = $orders->map->reference->implode(',');

        try {
            $billCode = $this->gateway->createBill(
                payer: $user,
                amount: (float) $orders->sum('total'),
                name: 'Order ' . $orders->map->id->implode(', '),
                reference: mb_substr($reference, 0, 100),
                phone: (string) $phone,
                returnUrl: route('payment.return'),
                callbackUrl: route('toyyibpay.callback'),
            );
        } catch (RuntimeException) {
            return redirect()->route('buyer.orders', ['status' => Order::STATUS_PENDING])
                ->with('error', 'We could not reach the payment gateway. Your order is saved; please try paying again.');
        }

        Order::whereKey($orders->modelKeys())->update(['bill_code' => $billCode]);

        return redirect()->away($this->gateway->paymentUrl($billCode));
    }
}
