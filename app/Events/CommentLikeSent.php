<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentLikeSent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    public $like;
    public $postId;
    public $add;
    public $isResponse;
    public $commentId;
    /**
     * Create a new event instance.
     */
    public function __construct($like, $postId, $add, $isResponse = false, $commentId = null)
    {
        $this->like = $like;
        $this->postId = $postId;
        $this->add = $add;
        $this->isResponse = $isResponse;
        $this->commentId = $commentId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [new Channel('post-' . $this->postId)];
    }

    public function broadcastAs()
    {
        return 'likes';
    }
}
