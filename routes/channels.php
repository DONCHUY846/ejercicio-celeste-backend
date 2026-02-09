<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.Usuario.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
}, ['guards' => ['sanctum']]);

Broadcast::channel('surveys.{id}', function ($user, $id) {
    return true; // Everyone authenticated can listen
}, ['guards' => ['sanctum']]);
