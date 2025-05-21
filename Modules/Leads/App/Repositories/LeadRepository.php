<?php

namespace Modules\Leads\App\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Leads\App\Models\Lead;
use Modules\User\App\Models\User;
use Modules\User\App\Models\UserVerify;


class LeadRepository
{
    public function createLead(array $data): Lead
    {

        $lead = new Lead([
            'staff_id' => $data['staff_id'],
            'source_id' => $data['source_id'],
            'status_id' => $data['status_id'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'country' => $data['country'],
            'city' => $data['city'],
            'address' => $data['address'],
            'zip_code' => $data['zip_code'],
            'website' => $data['website'],
            'phone' => $data['phone'],
            'mobile_number' => $data['mobile_number'],
            'company_name' => $data['company_name'],
            'description' => $data['description'],
            'tags' => $data['tags'],
            'is_public' => $data['is_public'],
            'send_welcome_sms' => $data['send_welcome_sms'],
            'send_welcome_email' => $data['send_welcome_email'],
            'active' => $data['active'],
        ]);
        $lead->save();


        return $lead;
    }
}
