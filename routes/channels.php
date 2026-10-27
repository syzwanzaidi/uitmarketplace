<?php

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function (User $user, int $id) {
    return $user->id === $id;
});

// Only the buyer or seller in a conversation may listen to it.
Broadcast::channel('chat.{conversation}', function (User $user, Conversation $conversation) {
    return $conversation->hasParticipant($user);
});
