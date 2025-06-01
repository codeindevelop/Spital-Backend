<?php

namespace Modules\Auth\App\Services;


use Illuminate\Support\Str;
use Modules\Auth\App\Events\LeadConvertToUserEvent;
use Modules\Auth\App\Events\UserRegisteredEvent;
use Modules\Auth\App\Repositories\UserRepository;

class CreateUserByAdminService
{
    protected UserRepository $UserRepository;


    public function __construct(UserRepository $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    public function createUser(array $data): array
    {


        $user = $this->UserRepository->convertLeadToUser($data);

        // Dispatch User Register Processed Actions
//        LeadConvertToUserEvent::dispatch($user);
//        UserRegisteredEvent::dispatch($user);
//        // Dispatch User Register Processed Actions
//        LeadConvertToUserEvent::dispatch($user);

        return [
            'user' => $user,
        ];
    }


}
