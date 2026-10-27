<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    private function conversation(): Conversation
    {
        $product = Product::factory()->create();

        return Conversation::create([
            'buyer_id' => User::factory()->create()->id,
            'seller_id' => $product->user_id,
            'product_id' => $product->id,
        ]);
    }

    public function test_outsiders_cannot_read_or_post_in_a_conversation(): void
    {
        $conversation = $this->conversation();
        $outsider = User::factory()->create();

        $this->actingAs($outsider)->get(route('chat.show', $conversation))->assertForbidden();
        $this->actingAs($outsider)->postJson(route('chat.send', $conversation), ['message' => 'hi'])->assertForbidden();
        $this->actingAs($outsider)->getJson(route('chat.poll', $conversation))->assertForbidden();

        $this->assertSame(0, $conversation->messages()->count());
    }

    public function test_participants_can_message_and_poll(): void
    {
        $conversation = $this->conversation();

        $this->actingAs($conversation->buyer)
            ->postJson(route('chat.send', $conversation), ['message' => '<b>Still available?</b>'])
            ->assertCreated()
            ->assertJsonPath('message.message', '<b>Still available?</b>')
            ->assertJsonMissingPath('message.sender');

        $this->actingAs($conversation->seller)
            ->getJson(route('chat.poll', $conversation))
            ->assertOk()
            ->assertJsonCount(1, 'messages');

        $this->assertNotNull($conversation->messages()->first()->read_at);
    }

    public function test_starting_a_chat_reuses_the_conversation(): void
    {
        $product = Product::factory()->create();
        $buyer = User::factory()->create();

        $this->actingAs($buyer)->post(route('chat.start', $product));
        $this->actingAs($buyer)->post(route('chat.start', $product));

        $this->assertSame(1, Conversation::count());
        $this->actingAs($product->user)->post(route('chat.start', $product))->assertSessionHas('error');
    }
}
