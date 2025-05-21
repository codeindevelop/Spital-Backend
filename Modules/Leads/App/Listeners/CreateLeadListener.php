<?php

namespace Modules\Leads\App\Listeners;


use Illuminate\Contracts\Queue\ShouldQueue;


use Modules\Auth\App\Jobs\UserLogedinByEmailJob;
use Modules\Leads\App\Events\CreateLeadEvent;
use Modules\Leads\App\Jobs\SendWelcomeNotificationToLeadJob;


class CreateLeadListener
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
    public function handle(CreateLeadEvent $event): void
    {


        // Dispatch Jobs After Create Lead By Admin for Send Welcome Mail and SMS
        SendWelcomeNotificationToLeadJob::dispatchAfterResponse($event->lead);


    }
}
