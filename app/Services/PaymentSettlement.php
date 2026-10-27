<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

/**
 * Applies the verified result of a ToyyibPay bill to every order that
 * shares it. Safe to call any number of times (callback + return page).
 */
class PaymentSettlement
{
    public function __construct(private readonly ToyyibPay $gateway)
    {
    }

    public function settle(string $billCode): void
    {
        if (! Order::where('bill_code', $billCode)->where('status', Order::STATUS_PENDING)->exists()) {
            return; // nothing left to settle
        }

        $result = $this->gateway->billStatus($billCode);

        if ($result['status'] === ToyyibPay::STATUS_PENDING) {
            return;
        }

        DB::transaction(function () use ($billCode, $result) {
            $orders = Order::with('items')
                ->where('bill_code', $billCode)
                ->where('status', Order::STATUS_PENDING)
                ->lockForUpdate()
                ->get();

            if ($orders->isEmpty()) {
                return; // settled by a concurrent request
            }

            if ($result['status'] !== ToyyibPay::STATUS_SUCCESS) {
                Order::whereKey($orders->modelKeys())->update(['status' => Order::STATUS_FAILED]);
                logger()->warning('ToyyibPay bill failed', ['bill_code' => $billCode]);

                return;
            }

            $expectedSen = (int) round($orders->sum('total') * 100);
            $paidSen = (int) round($result['amount'] * 100);

            if ($expectedSen !== $paidSen) {
                logger()->error('ToyyibPay amount mismatch', compact('billCode', 'expectedSen', 'paidSen'));

                return;
            }

            $reference = $result['reference'] ?? $billCode;
            $productIds = $orders->flatMap->items->pluck('product_id')->unique();

            foreach ($orders as $order) {
                $order->update([
                    'status' => Order::STATUS_PAID,
                    'payment_ref' => $reference,
                    'paid_at' => now(),
                ]);

                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'toyyibpay',
                    'payment_status' => 'paid',
                    'transaction_reference' => $reference,
                    'paid_at' => now(),
                ]);
            }

            Product::whereIn('id', $productIds)->update(['status' => Product::STATUS_SOLD]);

            // Sold items can no longer be bought by anyone.
            Cart::whereIn('product_id', $productIds)->delete();
        });
    }
}
