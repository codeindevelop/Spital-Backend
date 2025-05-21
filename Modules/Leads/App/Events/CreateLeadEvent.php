<?php

namespace Modules\Leads\App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Leads\App\Models\Lead;


class CreateLeadEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Lead $lead;


    /**
     * Create a new event instance.
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;

    }
}
