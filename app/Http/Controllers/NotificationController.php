<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Get the authenticated user's notifications.
     */
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->notifications()->paginate(10)
        );
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['message' => 'Notificación marcada como leída']);
        }

        return response()->json(['message' => 'Notificación no encontrada'], 404);
    }

    /**
     * Get the authenticated user's unread notifications count.
     */
    public function unreadCount(Request $request)
    {
        return response()->json([
            'count' => $request->user()->unreadNotifications()->count()
        ]);
    }
}
