<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CheckoutPaymentTest extends TestCase
{
    use RefreshDatabase;

    private const GATEWAY = 'https://toyyibpay.test';

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.toyyibpay' => ['url' => self::GATEWAY, 'secret' => 's', 'category' => 'c']]);
    }

    private function shipping(array $items): array
    {
        return [
            'items' => $items,
            'shipping_name' => 'Daniel',
            'shipping_phone' => '0123456789',
            'address1' => '1 Jalan A',
            'postcode' => '40000',
            'city' => 'Shah Alam',
            'state' => 'Selangor',
        ];
    }

    /** Fake ToyyibPay: createBill returns BILL1; getBillTransactions returns $status for $amount. */
    private function fakeGateway(int $status = 1, string $amount = '0.00'): void
    {
        Http::fake([
            self::GATEWAY . '/index.php/api/createBill' => Http::response([['BillCode' => 'BILL1']]),
            self::GATEWAY . '/index.php/api/getBillTransactions' => Http::response([
                ['billpaymentStatus' => (string) $status, 'billpaymentAmount' => $amount, 'billpaymentInvoiceNo' => 'TP123'],
            ]),
        ]);
    }

    public function test_cannot_add_own_or_sold_items_to_cart(): void
    {
        $own = Product::factory()->create();
        $sold = Product::factory()->sold()->create();

        $this->actingAs($own->user)->post(route('cart.add', $own))->assertSessionHas('error');
        $this->actingAs($own->user)->post(route('cart.add', $sold))->assertSessionHas('error');

        $this->assertSame(0, Cart::count());
    }

    public function test_buy_now_keeps_the_rest_of_the_cart(): void
    {
        $buyer = User::factory()->create();
        [$a, $b] = Product::factory()->count(2)->create();

        $this->actingAs($buyer)->post(route('cart.add', $a));
        $this->actingAs($buyer)->post(route('buy.now', $b))->assertRedirectContains('/checkout');

        $this->assertSame(2, Cart::where('user_id', $buyer->id)->count());
    }

    public function test_checkout_creates_one_order_per_seller_under_one_bill(): void
    {
        $this->fakeGateway();
        $buyer = User::factory()->create();
        [$a, $b] = Product::factory()->count(2)->create(); // two different sellers

        $carts = collect([$a, $b])->map(fn ($p) => Cart::create(['user_id' => $buyer->id, 'product_id' => $p->id, 'seller_id' => $p->user_id]));

        $this->actingAs($buyer)
            ->post(route('orders.store'), $this->shipping($carts->pluck('id')->all()))
            ->assertRedirect(self::GATEWAY . '/BILL1');

        $orders = Order::where('buyer_id', $buyer->id)->get();
        $this->assertCount(2, $orders);
        $this->assertTrue($orders->every(fn ($o) => $o->bill_code === 'BILL1' && $o->status === Order::STATUS_PENDING));
        $this->assertSame('Daniel', $orders->first()->shipping_name);
    }

    public function test_forged_callback_does_not_mark_order_paid(): void
    {
        $this->fakeGateway(status: 3); // ToyyibPay says the bill failed
        $order = $this->pendingOrder(25);

        $this->post(route('toyyibpay.callback'), ['billcode' => 'BILL1', 'status_id' => 1, 'amount' => '25.00'])->assertOk();

        $this->assertSame(Order::STATUS_FAILED, $order->fresh()->status);
        $this->assertTrue($order->items->first()->product->fresh()->isAvailable());
    }

    public function test_amount_mismatch_is_not_settled(): void
    {
        $this->fakeGateway(status: 1, amount: '1.00');
        $order = $this->pendingOrder(25);

        $this->post(route('toyyibpay.callback'), ['billcode' => 'BILL1'])->assertOk();

        $this->assertSame(Order::STATUS_PENDING, $order->fresh()->status);
    }

    public function test_verified_payment_marks_order_paid_and_item_sold(): void
    {
        $this->fakeGateway(status: 1, amount: '25.00');
        $order = $this->pendingOrder(25);
        $product = $order->items->first()->product;
        $other = User::factory()->create();
        Cart::create(['user_id' => $other->id, 'product_id' => $product->id, 'seller_id' => $product->user_id]);

        $this->actingAs($order->buyer)->get(route('payment.return', ['billcode' => 'BILL1']))->assertOk()->assertSee('Payment successful');
        $this->post(route('toyyibpay.callback'), ['billcode' => 'BILL1'])->assertOk(); // duplicate is harmless

        $order->refresh();
        $this->assertSame(Order::STATUS_PAID, $order->status);
        $this->assertSame('TP123', $order->payment_ref);
        $this->assertSame(1, $order->payments()->count());
        $this->assertSame(Product::STATUS_SOLD, $product->fresh()->status);
        $this->assertSame(0, Cart::where('product_id', $product->id)->count());
    }

    public function test_buyers_can_only_complete_their_own_shipped_orders(): void
    {
        $order = $this->pendingOrder(10);
        $order->update(['status' => Order::STATUS_SHIPPED]);

        $this->actingAs(User::factory()->create())->patch(route('buyer.orders.received', $order))->assertForbidden();
        $this->actingAs($order->buyer)->patch(route('buyer.orders.received', $order));

        $this->assertSame(Order::STATUS_COMPLETED, $order->fresh()->status);
    }

    private function pendingOrder(float $total): Order
    {
        $product = Product::factory()->create(['price' => $total]);
        $order = Order::create([
            'buyer_id' => User::factory()->create()->id,
            'seller_id' => $product->user_id,
            'total' => $total,
            'status' => Order::STATUS_PENDING,
            'bill_code' => 'BILL1',
        ]);
        $order->items()->create(['product_id' => $product->id, 'price' => $total]);

        return $order->load('items.product', 'buyer');
    }
}
