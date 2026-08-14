<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class ChatController extends Controller
{
    // Start or open a conversation
    public function start(Product $product)
    {
        $buyer = auth()->user();
        $seller = $product->user;

        if (!$seller) {
            return back()->with('error', 'Product seller not found.');
        }

        if ($buyer->id === $seller->id) {
            return back()->with('error', 'You cannot chat with yourself.');
        }

        $conversation = Conversation::firstOrCreate([
            'buyer_id'   => $buyer->id,
            'seller_id'  => $seller->id,
            'product_id' => $product->id,
        ]);

        return redirect()->route('chat', [
            'conversation_id' => $conversation->id
        ]);
    }

    // Chat page
    public function index(Request $request)
    {
        $userId = auth()->id();
        $conversationId = $request->conversation_id;

        // Conversations + unread count ONLY
        $conversations = Conversation::with(['product', 'buyer', 'seller'])
            ->withCount([
                'messages as unread_count' => function ($q) use ($userId) {
                    $q->where('sender_id', '!=', $userId)
                      ->whereNull('read_at');
                }
            ])
            ->where(function ($q) use ($userId) {
                $q->where('buyer_id', $userId)
                  ->orWhere('seller_id', $userId);
            })
            ->latest('updated_at')
            ->get();

        $conversation = null;
        $messages = collect();
        $product = null;
        $otherUser = null;

        if ($conversationId) {
            $conversation = Conversation::with(['product', 'buyer', 'seller'])
                ->findOrFail($conversationId);
            
            // Mark messages as read when opening conversation
            Message::where('conversation_id', $conversationId)
                ->where('sender_id', '!=', $userId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            // DO NOT mark as read (keep realtime safe)
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

    // Send message (REAL-TIME)
    public function send(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message'         => 'required|string',
        ]);

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_id'       => auth()->id(),
            'message'         => $request->message,
        ]);

        // REAL-TIME broadcast (unchanged)
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
