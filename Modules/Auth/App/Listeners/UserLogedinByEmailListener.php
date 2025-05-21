<?php

namespace Modules\Auth\App\Listeners;


use Illuminate\Contracts\Queue\ShouldQueue;


use Modules\Auth\App\Events\UserLogedinByEmailEvent;
use Modules\Auth\App\Jobs\UserLogedinByEmailJob;



class UserLogedinByEmailListener
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
    public function handle(UserLogedinByEmailEvent $event): void
    {



        // Dispatch Jobs After user Login for send Alert Email for Login
        UserLogedinByEmailJob::dispatchAfterResponse($event->user,$event->ip);


    }
}
