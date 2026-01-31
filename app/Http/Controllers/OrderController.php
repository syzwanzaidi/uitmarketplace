<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    /**
     * Create order + redirect to ToyyibPay
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // 1️⃣ Get selected cart items
        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->whereIn('id', $request->cart_items ?? [])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')
                ->with('error', 'No items selected for checkout.');
        }

        // 2️⃣ Group by seller (marketplace logic)
        $grouped = $cartItems->groupBy('seller_id');

        $lastBillCode = null;

        foreach ($grouped as $sellerId => $items) {

            $total = $items->sum(fn ($i) => $i->product->price);

            // 3️⃣ Create NEW order (NO reuse)
            $order = Order::create([
                'buyer_id' => $user->id,
                'seller_id'=> $sellerId,
                'total'    => $total,
                'status'   => 'pending',
            ]);

            // 4️⃣ Create order items
            foreach ($items as $cart) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $cart->product_id,
                    'price'      => $cart->product->price,
                ]);
            }

            // 5️⃣ Fix phone number (ToyyibPay requirement)
            $phone = preg_replace('/[^0-9]/', '', $user->phone ?? '');

            if (str_starts_with($phone, '0')) {
                $phone = '6' . $phone;
            }

            if (strlen($phone) < 10) {
                $phone = '60100000000';
            }

            // 6️⃣ Create ToyyibPay bill
            $response = Http::asForm()
                ->timeout(30)
                ->post(
                    config('services.toyyibpay.url') . '/index.php/api/createBill',
                    [
                        'userSecretKey'           => config('services.toyyibpay.secret'),
                        'categoryCode'            => config('services.toyyibpay.category'),
                        'billName'                => 'Order #' . $order->id,
                        'billDescription'         => 'Marketplace Order Payment',
                        'billPriceSetting'        => 1,
                        'billPayorInfo'           => 1,
                        'billAmount'              => $total * 100, // SEN
                        'billReturnUrl'           => config('app.url') . '/orders/' . $order->id . '/success',
                        'billCallbackUrl'         => config('app.url') . '/toyyibpay/callback',
                        'billExternalReferenceNo' => 'ORDER-' . $order->id,
                        'billTo'                  => $user->name,
                        'billEmail'               => $user->email,
                        'billPhone'               => $phone,
                    ]
                );

            if (!$response->successful() || !isset($response[0]['BillCode'])) {
                logger()->error('ToyyibPay createBill failed', [
                    'response' => $response->body(),
                ]);

                return redirect()->route('cart')
                    ->with('error', 'Payment gateway error. Please try again.');
            }

            // 7️⃣ Save bill code (CRITICAL)
            $billCode = $response[0]['BillCode'];

            $order->update([
                'bill_code' => $billCode,
            ]);

            $lastBillCode = $billCode;
        }

        // 8️⃣ Remove purchased items from cart
        Cart::whereIn('id', $cartItems->pluck('id'))->delete();

        // 9️⃣ Redirect to ToyyibPay
        return redirect(config('services.toyyibpay.url') . '/' . $lastBillCode);
    }

    /**
     * Payment success page (DOES NOT update status)
     */
    public function success(Order $order)
    {
        abort_if($order->buyer_id !== auth()->id(), 403);

        return view('user.order-confirmation', compact('order'));
    }
}
