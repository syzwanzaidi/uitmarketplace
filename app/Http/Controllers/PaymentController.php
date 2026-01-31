<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function callback(Request $request)
    {
        logger()->info('TOYYIBPAY CALLBACK HIT', $request->all());

        // 1️⃣ Validate required payload
        if (!$request->filled(['billcode', 'status_id', 'amount'])) {
            return response('Invalid callback', 400);
        }

        $order = Order::where('bill_code', $request->billcode)->first();

        if (!$order) {
            logger()->error('Order not found for billcode', [
                'billcode' => $request->billcode
            ]);
            return response('Order not found', 404);
        }

        // 2️⃣ Idempotency check (VERY IMPORTANT)
        if ($order->status === 'paid') {
            logger()->info('Order already paid, ignoring duplicate callback', [
                'order_id' => $order->id
            ]);
            return response('OK');
        }

        // 3️⃣ Verify amount (ToyyibPay sends SEN)
        $expectedAmount = (int) ($order->total * 100);
        $paidAmount     = (int) $request->amount;

        if ($expectedAmount !== $paidAmount) {
            logger()->error('Payment amount mismatch', [
                'order_id' => $order->id,
                'expected' => $expectedAmount,
                'paid'     => $paidAmount,
            ]);

            return response('Amount mismatch', 400);
        }

        // 4️⃣ Payment success
        if ((int) $request->status_id === 1) {

            $transactionRef = $request->refno
                ?? $request->transaction_id
                ?? $request->billcode;

            $order->update([
                'status'      => 'paid',
                'payment_ref' => $transactionRef,
                'paid_at'     => now(),
            ]);

            Payment::create([
                'order_id'              => $order->id,
                'payment_method'        => 'toyyibpay',
                'payment_status'        => 'paid',
                'transaction_reference' => $transactionRef,
                'paid_at'               => now(),
            ]);

            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)
                    ->update(['status' => 'sold']);
            }

            logger()->info('Payment completed', [
                'order_id' => $order->id
            ]);

        } else {
            // Only mark failed if not already paid
            if ($order->status !== 'paid') {
                $order->update(['status' => 'failed']);
            }
        }

        return response('OK');
    }

}
