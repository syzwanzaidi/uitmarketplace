<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentSettlement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentSettlement $settlement)
    {
    }

    /**
     * Server-to-server notification from ToyyibPay. The payload is only
     * used to find the bill; its status is re-checked with ToyyibPay's
     * API so a forged request cannot mark an order as paid.
     */
    public function callback(Request $request): Response
    {
        $billCode = (string) $request->input('billcode');

        if ($billCode === '' || ! Order::where('bill_code', $billCode)->exists()) {
            logger()->warning('ToyyibPay callback for unknown bill', ['billcode' => $billCode]);

            return response('Unknown bill', 404);
        }

        $this->settlement->settle($billCode);

        return response('OK');
    }

    /**
     * Where the buyer lands after paying. Callbacks cannot reach a local
     * machine, so this also settles the bill (after verifying it).
     */
    public function return(Request $request): View
    {
        $billCode = (string) $request->query('billcode');

        $orders = Order::with('items.product')
            ->where('bill_code', $billCode)
            ->where('buyer_id', auth()->id())
            ->get();

        abort_if($billCode === '' || $orders->isEmpty(), 404);

        $this->settlement->settle($billCode);

        $orders = $orders->fresh('items.product');

        return view('user.order-confirmation', [
            'orders' => $orders,
            'status' => match (true) {
                $orders->every(fn (Order $o) => in_array($o->status, Order::SETTLED_STATUSES)) => 'paid',
                $orders->contains('status', Order::STATUS_FAILED) => 'failed',
                default => 'pending',
            },
        ]);
    }
}
