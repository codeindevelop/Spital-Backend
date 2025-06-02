<?php

namespace Modules\User\App\Services;

use Illuminate\Support\Facades\Auth;


class GetUserProfileService
{


    public function getUser(): array
    {

        $user = Auth::user();
        $role = $user->getRoleNames();

        return [
            'user' => $user,
            'role' => $role,
        ];
    }

}
