<?php

namespace Modules\Auth\App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\User\App\Models\User;


class LeadConvertToUserEvent implements ShouldQueue
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



}
