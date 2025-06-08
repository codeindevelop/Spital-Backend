<?php

namespace Modules\Auth\App\Events\Admin;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\User\App\Models\User;

class AdminLoginErrorEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $ip;

    public function __construct(User $user, string $ip)
    {
        $this->user = $user;
        $this->ip = $ip;
    }
}
