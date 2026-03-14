<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\EventoController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\NotificationController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/email/verify/{id}/{token}', [AuthController::class, 'verifyEmail']);

Route::middleware('throttle:3,1')->post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::middleware('throttle:5,1')->post('/auth/verify-otp-reset', [AuthController::class, 'verifyOtpReset']);

// sanctum middleware
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'updatePassword']);
    
    Route::post('/eventos', [EventoController::class, 'store']);
    Route::get('/eventos', [SurveyController::class, 'index']);
    Route::get('/eventos/{id}', [SurveyController::class, 'show']);
    Route::post('/eventos/{id}/vote', [SurveyController::class, 'vote']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    // Example protected route
    Route::get('/verified-only', function () {
        return response()->json(['message' => 'You are verified!']);
    })->middleware('verified');

    // Admin only route
    Route::get('/admin-only', function () {
        return response()->json(['message' => 'You are an admin!']);
    })->middleware('admin');

    // Ejemplo: Solo Administradores (rol id: 1)
    Route::middleware('role:1')->group(function () {
        Route::get('/admin-ejemplo', function () {
            return response()->json(['message' => 'Acceso permitido solo para Administradores']);
        });
    });

    // Ejemplo: Administradores y Residentes (rol ids: 1,2)
    Route::middleware('role:1,2')->group(function () {
        Route::get('/admin-o-residente-ejemplo', function () {
            return response()->json(['message' => 'Acceso permitido para Administradores o Residentes']);
        });
    });
});
