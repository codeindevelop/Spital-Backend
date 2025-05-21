<?php

namespace Modules\Leads\App\Services;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Auth\App\Events\LeadConvertToUserEvent;
use Modules\Leads\App\Events\CreateLeadEvent;
use Modules\Leads\App\Models\Lead;
use Modules\User\App\Models\User;
use Modules\User\App\Models\UserVerify;
use Modules\User\App\Repositories\UserRepository;


class ConvertLeadToUserService
{
    public UserRepository $userRepository;


    public function __construct(UserRepository $userRepository)
    {

        $this->userRepository = $userRepository;
    }

    public function convertLead(array $data): array
    {

        $targetLead = Lead::where('id', $data['lead_id'])->first();


        $user = new User([
            'first_name' => $targetLead->first_name,
            'last_name' => $targetLead->last_name,
            'email' => $targetLead->email,
            'mobile_number' => $targetLead->mobile_number,
            'password' => Hash::make($targetLead->password),
        ]);
        $user->save();

        $userVerify = new UserVerify([
            'user_id' => $user->id,
            'email_verify_token' => Str::random(60),
            'status' => 'signup',
        ]);
        $userVerify->save();


        // Dispatch Create Lead Actions
        LeadConvertToUserEvent::dispatch($user);


        return [
            'lead' => $user,
        ];
    }

}
