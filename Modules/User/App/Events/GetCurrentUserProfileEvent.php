<?php

namespace Modules\User\App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\User\App\Models\User;


class GetCurrentUserProfileEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;


    /**
     * Create a new event instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;

    }

    /**
     * Get the channels the event should be broadcast on.
     */
//    public function broadcastOn(): array
//    {
//        return [
//            new Channel('user-registered'),
//        ];
//    }
}
