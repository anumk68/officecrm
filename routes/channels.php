<?php

use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });
// Broadcast::channel('online-users', function ($user) {
//     return $user !== null;
// });
// Broadcast::channel('team-leader-notifications', function ($user) {
//     return $user->role === 'team_leader';
// });

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('team-leader-notifications', function ($user) {
    return $user->role === 'team_leader';
});

Broadcast::channel('supervisory-notifications', function ($user) {
    return in_array($user->role, ['manager', 'hr', 'team_leader']);
});

Broadcast::channel('leadership-notifications', function ($user) {
    return in_array($user->role, ['manager', 'team_leader']);
});

Broadcast::channel('online-users', function ($user) {
    return true;
});

Broadcast::channel('all-users', function ($user) {
    return true;
});

 

Broadcast::channel('hr-notifications', function ($user) {
    return $user->role === 'hr';
});

