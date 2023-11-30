<?php

namespace App\Notifications;

use App\Models\GroupMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewGroupMessage extends Notification
{
    use Queueable;
    public $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(GroupMessage $gp)
    {
        $this->message = $gp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast','database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message
        ];
    }
}
