<?php

namespace Modules\Leads\App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use Modules\Auth\App\Events\LeadConvertToUserEvent;
use Modules\Leads\App\Events\CreateLeadEvent;
use Modules\Leads\App\Listeners\ConvertLeadToUserListener;
use Modules\Leads\App\Listeners\CreateLeadListener;


class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CreateLeadEvent::class => [
            CreateLeadListener::class,
        ],
        LeadConvertToUserEvent::class => [
            ConvertLeadToUserListener::class,
        ],

    ];
}
