<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{receiverId}', function ($user, $receiverId) {
    return true;
});


Broadcast::channel('typing.{id}', function ($user, $id) {
    return true ;
});
Broadcast::channel('presence.chat', function ($user) {
    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});

