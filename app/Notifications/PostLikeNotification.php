<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostLikeNotification extends Notification
{
    use Queueable;
    private $post;

    /**
     * Create a new notification instance.
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','broadcast'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => auth()->user()->name . ' a aimé(e) votre publication',
            'post_id' => $this->post->id
        ];
    }
}
