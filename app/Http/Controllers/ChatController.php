<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ChatController extends Controller
{
    public function index(?Conversation $conversation = null): View
    {
        $user = auth()->user();

        if ($conversation) {
            abort_unless($conversation->hasParticipant($user), 403);
            $conversation->load(['product', 'buyer', 'seller']);
            $this->markRead($conversation);
        }

        $conversations = Conversation::with(['product', 'buyer', 'seller', 'latestMessage'])
            ->withCount(['messages as unread_count' => fn ($q) => $q
                ->where('sender_id', '!=', $user->id)
                ->whereNull('read_at')])
            ->involving($user->id)
            ->latest('updated_at')
            ->get();

        return view('user.chat', [
            'conversations' => $conversations,
            'conversation' => $conversation,
            'messages' => $conversation?->messages()->get() ?? collect(),
            'partner' => $conversation?->partnerOf($user),
        ]);
    }

    public function start(Product $product): RedirectResponse
    {
        $user = auth()->user();

        if ($product->user_id === $user->id) {
            return back()->with('error', 'This is your own listing.');
        }

        $conversation = Conversation::firstOrCreate([
            'buyer_id' => $user->id,
            'seller_id' => $product->user_id,
            'product_id' => $product->id,
        ]);

        return redirect()->route('chat.show', $conversation);
    }

    public function send(Request $request, Conversation $conversation): JsonResponse
    {
        abort_unless($conversation->hasParticipant($request->user()), 403);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $request->user()->id,
            'message' => trim($validated['message']),
        ]);

        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (Throwable $e) {
            // Realtime is a nice-to-have; the message is saved and polling will deliver it.
            report($e);
        }

        return response()->json(['message' => self::present($message)], 201);
    }

    /** Polling fallback: messages newer than ?after=<id>. */
    public function poll(Request $request, Conversation $conversation): JsonResponse
    {
        abort_unless($conversation->hasParticipant($request->user()), 403);

        $messages = $conversation->messages()
            ->where('id', '>', (int) $request->query('after', 0))
            ->get();

        $this->markRead($conversation);

        return response()->json(['messages' => $messages->map(fn ($m) => self::present($m))]);
    }

    private function markRead(Conversation $conversation): void
    {
        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /** The only message fields the browser needs (no user details leak). */
    public static function present(Message $message): array
    {
        return [
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'message' => $message->message,
            'created_at' => $message->created_at->toIso8601String(),
        ];
    }
}
