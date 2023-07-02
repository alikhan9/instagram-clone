<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostCommentSent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $comment;
    public $isResponse;

    /**
     * Create a new event instance.
     */
    public function __construct($postComment, $isResponse = false)
    {
        $this->comment = $postComment;
        $this->isResponse = $isResponse;
    }

    /**
 * Get the data to broadcast.
 *
 * @return array<string, mixed>
 */
    public function broadcastWith(): array
    {
        $this->comment->updated_created_at =  $this->comment->created_at->diffForHumans();
        return [$this->comment,$this->isResponse];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [new Channel($this->isResponse ? 'post-' . $this->comment->postComment->post_id : 'post-' .  $this->comment->post_id)];
    }

    public function broadcastAs()
    {
        return 'comments';
    }
}
