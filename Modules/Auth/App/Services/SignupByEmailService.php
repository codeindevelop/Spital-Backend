<?php

namespace Modules\Auth\App\Services;


use Modules\Auth\App\Events\UserRegisteredEvent;
use Modules\User\App\Repositories\UserRepository;

class SignupByEmailService
{
    protected UserRepository $UserRepository;


    public function __construct(UserRepository $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    public function signup(array $data): array
    {


        $user = $this->UserRepository->signedupByEmail($data);

//        // Create Access Token For user After Register
        $accessToken = $user->createToken('authToken')->accessToken;


        // Dispatch User Register Processed Actions
        UserRegisteredEvent::dispatch($user);


        return [
            'user' => $user,
            'accessToken' => $accessToken,
        ];
    }

}
