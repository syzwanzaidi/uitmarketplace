<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;

/*
|--------------------------------------------------------------------------
| User private channel (default – keep this)
|--------------------------------------------------------------------------
*/
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/*
|--------------------------------------------------------------------------
| Chat private channel
|--------------------------------------------------------------------------
| Only buyer or seller of the conversation may listen
*/
Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {

    $conversation = Conversation::find($conversationId);

    if (!$conversation) {
        return false;
    }

    return $user->id === $conversation->buyer_id
        || $user->id === $conversation->seller_id;
});
