<?php

namespace Modules\Auth\App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use Modules\Auth\App\Events\Admin\AdminLoginErrorEvent;
use Modules\Auth\App\Events\UserLogedinByEmailEvent;
use Modules\Auth\App\Events\UserLogoutEvent;
use Modules\Auth\App\Events\UserRegisteredEvent;

use Modules\Auth\App\Listeners\Admin\AdminLoginErrorListener;
use Modules\Auth\App\Listeners\UserLogedinByEmailListener;
use Modules\Auth\App\Listeners\UserLogoutListener;
use Modules\Auth\App\Listeners\UserRegisteredListener;


class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRegisteredEvent::class => [
            UserRegisteredListener::class,
        ],
        UserLogedinByEmailEvent::class => [
            UserLogedinByEmailListener::class,
        ],
        UserLogoutEvent::class => [
            UserLogoutListener::class,
        ],
        AdminLoginErrorEvent::class => [
            AdminLoginErrorListener::class,
        ],


    ];
}
