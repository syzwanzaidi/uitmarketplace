<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ListingTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return [
            'name' => 'Desk lamp',
            'category_id' => Category::factory()->create()->id,
            'description' => 'Bright',
            'price' => 25,
            'location' => 'Seksyen 2, Shah Alam',
            'latitude' => 3.07,
            'longitude' => 101.5,
            ...$overrides,
        ];
    }

    /** A real 1x1 PNG, so tests don't depend on the GD extension. */
    private function tinyPng(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('lamp.png', base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
        ));
    }

    public function test_seller_can_create_a_listing(): void
    {
        Storage::fake('public');
        $seller = User::factory()->seller()->create();

        $this->actingAs($seller)
            ->post(route('listings.store'), $this->payload(['image' => $this->tinyPng()]))
            ->assertSessionHasNoErrors();

        $product = $seller->products()->sole();
        $this->assertSame('Desk lamp', $product->name);
        $this->assertNotNull($product->locationPoint);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $product->image));
    }

    public function test_public_members_cannot_create_listings(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('listings.store'), $this->payload())
            ->assertForbidden();
    }

    public function test_sellers_cannot_edit_or_delete_someone_elses_listing(): void
    {
        $product = Product::factory()->create(['name' => 'Original']);
        $intruder = User::factory()->seller()->create();

        $this->actingAs($intruder)->patch(route('listings.update', $product), $this->payload(['name' => 'Hacked']))->assertForbidden();
        $this->actingAs($intruder)->patch(route('listings.updateStatus', $product), ['status' => 'inactive'])->assertForbidden();
        $this->actingAs($intruder)->delete(route('listings.delete', $product))->assertForbidden();

        $this->assertSame('Original', $product->fresh()->name);
    }

    public function test_listings_with_orders_cannot_be_deleted(): void
    {
        $product = Product::factory()->create();
        $order = Order::create(['buyer_id' => User::factory()->create()->id, 'seller_id' => $product->user_id, 'total' => 10, 'status' => Order::STATUS_CANCELLED]);
        $order->items()->create(['product_id' => $product->id, 'price' => 10]);

        $this->actingAs($product->user)->delete(route('listings.delete', $product))->assertSessionHas('error');

        $this->assertModelExists($product);
    }

    public function test_seller_can_ship_a_paid_order(): void
    {
        $product = Product::factory()->sold()->create();
        $order = Order::create(['buyer_id' => User::factory()->create()->id, 'seller_id' => $product->user_id, 'total' => 10, 'status' => Order::STATUS_PAID]);

        $this->actingAs($product->user)
            ->patch(route('seller.orders.ship', $order), ['courier' => 'Pos Laju', 'tracking_number' => 'EP1MY'])
            ->assertSessionHasNoErrors();

        $order->refresh();
        $this->assertSame(Order::STATUS_SHIPPED, $order->status);
        $this->assertNotNull($order->shipped_at);
    }
}
