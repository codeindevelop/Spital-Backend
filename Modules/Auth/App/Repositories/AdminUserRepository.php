<?php

namespace Modules\Auth\App\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\User\App\Models\User;
use Modules\User\App\Models\UserPersonalInfo;
use Modules\User\App\Models\UserVerify;
use Modules\User\App\Models\UserAuthData;

use Modules\User\App\Models\UserAuthSetting;
use Modules\User\App\Models\UserJoinPlan;
use Illuminate\Database\Eloquent\Collection;

class AdminUserRepository
{
    public function createUser(array $data): User
    {
        $user = User::create([
            'id' => (string) Str::uuid(),
            'email' => $data['email'] ?? null,
            'mobile_number' => $data['mobile_number'] ?? null,
            'password' => Hash::make($data['password']),
            'active' => $data['active'] ?? true,
        ]);

        UserPersonalInfo::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'account_type' => 'individual',
            'user_name' => $data['user_name'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'gender' => $data['gender'],
            'phone_number' => $data['phone_number'] ?? $data['mobile_number'],
            'national_id' => $data['national_id'] ?? null,
            'home_address' => $data['home_address'] ?? null,
            'display_name' => $data['first_name'].' '.$data['last_name'],
        ]);

        UserVerify::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'passport_number' => $data['passport_number'] ?? null,
            'shenasname_number' => $data['shenasname_number'] ?? null,
            'mellicard_number' => $data['mellicard_number'] ?? null,
            'status' => 'pending',
            'email_verify_token' => Str::random(60),
        ]);

        UserAuthData::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'register_ip' => $data['ip'],
            'register_device' => 'unknown',
            'register_browser' => 'unknown',
            'register_location' => 'unknown',
            'registered_at' => now(),
        ]);
//
//        UserNotificationSettings::create([
//            'id' => (string) Str::uuid(),
//            'user_id' => $user->id,
//            'login_email' => true,
//            'register_email' => true,
//            'change_password_email' => true,
//            'change_email_email' => true,
//            'change_mobile_email' => true,
//        ]);

        UserAuthSetting::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'two_factor_enabled' => false,
            'password_reset_enabled' => true,
            'email_verification_required' => true,
            'user_profile_visibility' => 'public',
            'user_profile_editing_enabled' => true,
            'account_lockout_enabled' => true,
            'account_lockout_threshold' => 5,
            'account_lockout_duration' => 15,
            'session_management_enabled' => true,
            'data_export_enabled' => true,
            'data_backup_enabled' => true,
        ]);
//
//        UserJoinPlan::create([
//            'id' => (string) Str::uuid(),
//            'user_id' => $user->id,
//            'plan_id' => '123e4567-e89b-12d3-a456-426614174000', // جایگزین با UUID واقعی پلن
//            'active' => true,
//        ]);

        return $user;
    }

    public function getAllUsers(): Collection
    {
        return User::with('personalInfo', 'verify', 'roles')->get();
    }

    public function getUserById(string $id
    ): \Illuminate\Database\Eloquent\Builder|array|Collection|\Illuminate\Database\Eloquent\Model {
        return User::with('personalInfo', 'verify', 'roles', 'permissions')->findOrFail($id);
    }

    public function updateUser(string $id, array $data): User
    {
        $user = User::findOrFail($id);

        $user->update([
            'email' => $data['email'] ?? $user->email,
            'mobile_number' => $data['mobile_number'] ?? $user->mobile_number,
            'password' => isset($data['password']) ? Hash::make($data['password']) : $user->password,
            'active' => $data['active'] ?? $user->active,
        ]);

        $personalInfo = $user->personalInfo ?? new UserPersonalInfo([
            'id' => (string) Str::uuid(), 'user_id' => $user->id
        ]);
        $personalInfo->fill([
            'first_name' => $data['first_name'] ?? $personalInfo->first_name,
            'last_name' => $data['last_name'] ?? $personalInfo->last_name,
            'gender' => $data['gender'] ?? $personalInfo->gender,
            'national_id' => $data['national_id'] ?? $personalInfo->national_id,
            'phone_number' => $data['phone_number'] ?? $personalInfo->phone_number,
            'home_address' => $data['home_address'] ?? $personalInfo->home_address,
            'display_name' => ($data['first_name'] ?? $personalInfo->first_name).' '.($data['last_name'] ?? $personalInfo->last_name),
        ])->save();

        return $user;
    }

    public function suspendUser(string $id, bool $active): User
    {
        $user = User::findOrFail($id);
        $user->update(['active' => $active]);
        return $user;
    }

    public function verifyUser(string $id, array $data): UserVerify
    {
        $verify = UserVerify::where('user_id', $id)->firstOrFail();
        $verify->update([
            'status' => $data['status'],
            'reject_status' => $data['reject_status'] ?? null,
            'verify_dateTime' => $data['status'] === 'verified' ? now() : $verify->verify_dateTime,
            'reject_dateTime' => $data['status'] === 'rejected' ? now() : $verify->reject_dateTime,
        ]);
        return $verify;
    }

    public function getTrashedUsers(): Collection
    {
        return User::onlyTrashed()->with('personalInfo', 'verify', 'roles')->get();
    }

    public function restoreUser(string $id): User
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return $user;
    }

    public function deleteUser(string $id): void
    {
        $user = User::findOrFail($id);
        $user->delete();
    }
}
