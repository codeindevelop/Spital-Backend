<?php

namespace Modules\Auth\App\Listeners;


use Illuminate\Contracts\Queue\ShouldQueue;


use Modules\Auth\App\Events\UserLogedinByEmailEvent;
use Modules\Auth\App\Events\UserLogoutEvent;
use Modules\Auth\App\Jobs\UserLogedinByEmailJob;
use Modules\Auth\App\Jobs\UserLogoutJob;


class UserLogoutListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserLogoutEvent $event): void
    {


        // Dispatch Jobs After user logout for send Alert Email for Login
        UserLogoutJob::dispatchAfterResponse($event->user, $event->ip);


    }
}
