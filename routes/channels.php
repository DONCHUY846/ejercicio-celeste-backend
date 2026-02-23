<?php

use Illuminate\Support\Facades\Broadcast;
// sanctum middleware
Broadcast::channel('App.Models.Usuario.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
}, ['guards' => ['sanctum']]);

Broadcast::channel('surveys.{id}', function ($user, $id) {
    return true; 
}, ['guards' => ['sanctum']]);
