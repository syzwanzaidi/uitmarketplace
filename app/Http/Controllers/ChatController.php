<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent; // later step

class ChatController extends Controller
{
    // Start or open a conversation and redirect to chat with conversation_id
    public function start(Product $product)
    {
        $buyer = auth()->user();
        $seller = $product->user;

        if (!$seller) {
            return redirect()->back()->with('error', 'Product seller not found.');
        }

        if ($buyer->id === $seller->id) {
            return redirect()->back()->with('error', 'You cannot chat with yourself.');
        }

        $conversation = Conversation::firstOrCreate(
            [
                'buyer_id' => $buyer->id,
                'seller_id' => $seller->id,
                'product_id' => $product->id,
            ]
        );

        // redirect to chat page and pass conversation id
        return redirect()->route('chat', ['conversation_id' => $conversation->id]);
    }

    // Chat page: load conversation if provided
    public function index(Request $request)
    {
        $conversation = null;
        $product = null;

        if ($request->has('conversation_id')) {
            $conversation = Conversation::with(['buyer', 'seller', 'product', 'messages.sender'])
                ->find($request->conversation_id);
        }

        // If conversation exists set product for preview
        if ($conversation) {
            $product = $conversation->product;
        }

        return view('user.chat', [
            'conversation' => $conversation,
            'product' => $product,
        ]);
    }

    // simple send (store message + broadcast in later steps)
    public function send(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // event(new MessageSent($message->conversation_id, $message)); // step 4 later

        return response()->json(['success' => true, 'message' => $message]);
    }
}
