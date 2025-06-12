<?php

namespace Modules\Auth\App\Listeners\Admin;


use Illuminate\Contracts\Queue\ShouldQueue;


use Modules\Auth\App\Events\Admin\AdminLoginErrorEvent;
use Modules\Auth\App\Jobs\Admin\AdminLoginErrorJob;


class AdminLoginErrorListener
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
    public function handle(AdminLoginErrorEvent $event): void
    {



        // Dispatch Jobs After user logout for send Alert Email for Login
        AdminLoginErrorJob::dispatch($event->user, $event->ip);


    }
}
