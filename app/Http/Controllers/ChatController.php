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
        $userId = auth()->id();
        $conversationId = $request->conversation_id;

        // ALL conversations for left panel
        $conversations = Conversation::with(['product', 'buyer', 'seller'])
            ->where('buyer_id', $userId)
            ->orWhere('seller_id', $userId)
            ->latest('updated_at')
            ->get();

        // DEFAULT empty state
        $conversation = null;
        $messages = collect();
        $product = null;
        $otherUser = null;

        // IF user clicked a conversation
        if ($conversationId) {
            $conversation = Conversation::with(['product', 'buyer', 'seller'])
                ->findOrFail($conversationId);

            $messages = Message::where('conversation_id', $conversationId)
                ->orderBy('created_at')
                ->get();

            $otherUser = $conversation->buyer_id === $userId
                ? $conversation->seller
                : $conversation->buyer;

            $product = $conversation->product;
        }

        return view('user.chat', compact(
            'conversations',
            'conversation',
            'messages',
            'product',
            'otherUser'
        ));
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

        // Broadcast real-time event
        broadcast(new MessageSent($request->conversation_id, $message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

}
