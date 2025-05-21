<?php

namespace Modules\Leads\App\Services;


use Modules\Leads\App\Events\CreateLeadEvent;
use Modules\Leads\App\Repositories\LeadRepository;


class CreateLeadService
{
    protected LeadRepository $LeadRepository;


    public function __construct(LeadRepository $LeadRepository)
    {
        $this->LeadRepository = $LeadRepository;
    }

    public function createLead(array $data): array
    {


        $lead = $this->LeadRepository->createLead($data);


        // Dispatch Create Lead Actions
        CreateLeadEvent::dispatch($lead);


        return [
            'lead' => $lead,
        ];
    }

}
