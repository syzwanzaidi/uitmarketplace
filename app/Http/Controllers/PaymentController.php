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
        // Log EVERYTHING (for audit)
        logger()->info('TOYYIBPAY CALLBACK HIT', $request->all());

        // 1️Validate required payload
        if (!$request->filled(['billcode', 'status_id', 'amount'])) {
            logger()->error('Invalid ToyyibPay callback payload');
            return response('Invalid callback', 400);
        }

        // 2 Find order
        $order = Order::where('bill_code', $request->billcode)->first();

        if (!$order) {
            logger()->error('Order not found for billcode', [
                'billcode' => $request->billcode
            ]);
            return response('Order not found', 404);
        }

        // 3 Idempotency guard (CRITICAL)
        if ($order->status === 'paid') {
            logger()->info('Duplicate callback ignored', [
                'order_id' => $order->id
            ]);
            return response('OK');
        }

        // 4 Amount verification (RM → SEN)
        $expectedAmountSen = (int) round(((float) $order->total) * 100);
        $paidAmountSen     = (int) round(((float) $request->amount) * 100);

        if ($expectedAmountSen !== $paidAmountSen) {
            logger()->error('Payment amount mismatch', [
                'order_id' => $order->id,
                'expected' => $expectedAmountSen,
                'paid'     => $paidAmountSen,
            ]);

            return response('Amount mismatch', 400);
        }

        // 5 Payment SUCCESS
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

            // 6️ Mark products as sold
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)
                    ->update(['status' => 'sold']);
            }

            logger()->info('Payment completed successfully', [
                'order_id' => $order->id
            ]);

        } else {
            // 7️ Payment FAILED
            $order->update(['status' => 'failed']);

            logger()->warning('Payment failed', [
                'order_id' => $order->id,
                'status_id' => $request->status_id
            ]);
        }

        return response('OK');
    }
}
