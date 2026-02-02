<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\EventoController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\NotificationController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Survey/Event Routes
    Route::post('/eventos', [EventoController::class, 'store']);
    Route::get('/eventos', [SurveyController::class, 'index']);
    Route::get('/eventos/{id}', [SurveyController::class, 'show']);
    Route::post('/eventos/{id}/vote', [SurveyController::class, 'vote']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});
