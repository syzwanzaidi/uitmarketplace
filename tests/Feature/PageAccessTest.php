<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\Conversation;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PageAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_sent_to_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->get(route('about'))->assertOk();
    }

    public function test_buyer_pages_render(): void
    {
        $buyer = User::factory()->create();
        $product = Product::factory()->create();
        $buyer->wishlist()->create(['product_id' => $product->id]);
        $conversation = Conversation::create(['buyer_id' => $buyer->id, 'seller_id' => $product->user_id, 'product_id' => $product->id]);
        $conversation->messages()->create(['sender_id' => $product->user_id, 'message' => 'Hi!']);
        $order = Order::create(['buyer_id' => $buyer->id, 'seller_id' => $product->user_id, 'total' => 10, 'status' => Order::STATUS_SHIPPED]);
        $order->items()->create(['product_id' => $product->id, 'price' => 10]);

        $this->actingAs($buyer);

        foreach ([
            route('home'),
            route('browse'),
            route('browse', ['search' => 'x', 'sort' => 'price_desc']),
            route('product.show', $product),
            route('cart'),
            route('buyer.orders'),
            route('account'),
            route('account', ['tab' => 'wishlist']),
            route('account', ['tab' => 'security']),
            route('chat.show'),
            route('chat.show', $conversation),
            route('contact'),
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_only_uitm_members_reach_the_seller_centre(): void
    {
        $this->actingAs(User::factory()->create())->get(route('listings'))->assertForbidden();

        $seller = User::factory()->seller()->create();
        Product::factory()->for($seller)->create();

        $this->actingAs($seller)->get(route('listings'))->assertOk();
        $this->actingAs($seller)->get(route('seller.orders'))->assertOk();
        $this->actingAs($seller)->get(route('seller.orders', ['tab' => 'completed']))->assertOk();
    }

    public function test_admin_pages_render_and_are_admin_only(): void
    {
        Complaint::create(['user_id' => User::factory()->create()->id, 'subject' => 'Help', 'message' => 'Body']);

        $this->actingAs(User::factory()->create())->get(route('admin.users'))->assertForbidden();

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->get('/')->assertRedirect(route('admin.users'));

        foreach ([route('admin.users'), route('admin.transactions'), route('admin.complaints')] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_hidden_listings_are_only_visible_to_their_seller(): void
    {
        $product = Product::factory()->hidden()->create();

        $this->actingAs(User::factory()->create())->get(route('product.show', $product))->assertNotFound();
        $this->actingAs($product->user)->get(route('product.show', $product))->assertOk();
    }

    public function test_suspended_users_are_logged_out(): void
    {
        $user = User::factory()->suspended()->create();

        $this->actingAs($user)->get(route('home'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_registration_assigns_membership_from_email(): void
    {
        $this->post('/register', ['name' => 'A', 'email' => 'a@student.uitm.edu.my', 'password' => 'password', 'password_confirmation' => 'password']);
        $this->post('/logout');
        $this->post('/register', ['name' => 'B', 'email' => 'b@gmail.com', 'password' => 'password', 'password_confirmation' => 'password']);

        $this->assertSame(User::TYPE_UITM, User::where('email', 'a@student.uitm.edu.my')->value('membership_type'));
        $this->assertSame(User::TYPE_PUBLIC, User::where('email', 'b@gmail.com')->value('membership_type'));
    }
}
