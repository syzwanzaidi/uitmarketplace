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

        if (!$request->filled(['billcode', 'status_id'])) {
            return response('Invalid callback', 400);
        }

        $order = Order::where('bill_code', $request->billcode)->first();

        if (!$order) {
            logger()->error('Order not found for billcode', [
                'billcode' => $request->billcode
            ]);
            return response('Order not found', 404);
        }

        if ((int) $request->status_id === 1) {

            $order->update([
                'status'      => 'paid',
                'payment_ref' => $request->refno ?? $request->transaction_id,
                'paid_at'     => now(),
            ]);

            Payment::create([
                'order_id'              => $order->id,
                'payment_method'        => 'toyyibpay',
                'payment_status'        => 'paid',
                'transaction_reference' => $request->refno ?? $request->transaction_id,
                'paid_at'               => now(),
            ]);

            foreach ($order->orderItems as $item) {
                Product::where('id', $item->product_id)
                    ->update(['status' => 'sold']);
            }

        } else {
            $order->update(['status' => 'failed']);
        }

        return response('OK');
    }
}
