<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;
    public int $conversationId;

    public function __construct(int $conversationId, Message $message)
    {
        $this->conversationId = $conversationId;
        $this->message = $message;
    }

    /**
     * Channel name
     * chat.{conversationId}
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat.' . $this->conversationId);
    }

    /**
     * Event name on frontend
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
