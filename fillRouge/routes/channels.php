<?php

use Illuminate\Support\Facades\Broadcast;

/*
 * Channel privé de chat entre deux utilisateurs.
 * Format : chat.{smallerId}.{largerId}
 *
 * Un utilisateur peut écouter ce channel UNIQUEMENT si
 * son ID est l'un des deux IDs dans le nom du channel.
 */
Broadcast::channel('chat.{user1Id}.{user2Id}', function ($user, $user1Id, $user2Id) {
    return (int) $user->id === (int) $user1Id
        || (int) $user->id === (int) $user2Id;
});

/*
 * Canal personnel : notifications de nouveaux messages (badge dans la barre de navigation).
 */
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});