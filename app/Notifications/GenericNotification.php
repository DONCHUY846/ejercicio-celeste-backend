<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class GenericNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $title;
    public string $message;
    public string $route_path;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $title, string $message, string $route_path)
    {
        $this->title = $title;
        $this->message = $message;
        $this->route_path = $route_path;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification (for Database).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'route_path' => $this->route_path,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => $this->title,
            'message' => $this->message,
            'route_path' => $this->route_path,
        ]);
    }
}
