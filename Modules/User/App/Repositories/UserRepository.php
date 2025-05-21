<?php

namespace Modules\User\App\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\User\App\Models\User;
use Modules\User\App\Models\UserVerify;


class UserRepository
{
    public function signedupByEmail(array $data): User
    {

        $user = new User([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'email_verify_token' => Str::random(60),
            'password' => Hash::make($data['password']),
            'register_ip' => $data['ip'],
        ]);
        $user->save();

        $userVerify = new UserVerify([
            'user_id' => $user->id,
            'email_verify_token' => Str::random(60),
            'status' => 'signup',
        ]);
        $userVerify->save();

        return $user;
    }

    public function convertLeadToUserRepo(array $data): User
    {

        $user = new User([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'mobile_number' => $data['mobile_number'],
            'password' => Hash::make($data['password']),
        ]);
        $user->save();

        $userVerify = new UserVerify([
            'user_id' => $user->id,
            'email_verify_token' => Str::random(60),
            'status' => 'signup',
        ]);
        $userVerify->save();

        return $user;
    }
    public function convertLeadToUserWithPassword(array $data): User
    {

        $user = new User([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'mobile_number' => $data['mobile_number'],
            'password' => Hash::make($data['password']),
        ]);
        $user->save();

        $userVerify = new UserVerify([
            'user_id' => $user->id,
            'email_verify_token' => Str::random(60),
            'status' => 'signup',
        ]);
        $userVerify->save();

        return $user;
    }
}
