<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $conversationId;

    public function __construct($conversationId, Message $message)
    {
        $this->conversationId = $conversationId;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel("chat.{$this->conversationId}");
    }
}
