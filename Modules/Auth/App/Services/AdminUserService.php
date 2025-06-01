<?php

namespace Modules\Auth\App\Services;

use Modules\Auth\App\Repositories\AdminUserRepository;
use Modules\User\App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Spatie\Activitylog\Facades\Activity;

class AdminUserService
{
    protected AdminUserRepository $adminUserRepository;

    public function __construct(AdminUserRepository $adminUserRepository)
    {
        $this->adminUserRepository = $adminUserRepository;
    }

    public function createUser(array $data): array
    {
        if ($data['create_password']) {
            $data['password'] = Str::random(12);
        }

        $user = $this->adminUserRepository->createUser($data);
        $user->assignRole('regular-user');

        if ($data['send_verify_email']) {
            $verify = $user->verify;
            Mail::raw("لطفاً برای تأیید ایمیل خود روی این لینک کلیک کنید: ".env('EMAIL_ACTIVE_LINK_PREFIX').$verify->email_verify_token,
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject(env('VERIFY_EMAIL_TO_USER_SUBJECT', 'ایمیل شما تایید شد'));
                });
        }

        if ($data['send_welcome_sms']) {
            $smsData = [
                'pattern_code' => 'welcome_pattern',
                'originator' => env('SEND_SMS_NUMBER', '3000505'),
                'recipient' => $user->mobile_number,
                'values' => [
                    'name' => $data['first_name'].' '.$data['last_name'],
                ],
            ];

            Http::withHeaders([
                'Authorization' => 'AccessKey '.env('SMS_API_KEY'),
            ])->post(env('SEND_SMS_SERVER'), $smsData);
        }

//        Activity::log()
//            ->causedBy(User::find($data['operator_id']))
//            ->performedOn($user)
//            ->event('user_created')
//            ->withProperties(['user_id' => $user->id])
//            ->log('User created by admin.');

        $accessToken = $user->createToken('authToken')->accessToken;

        if ($data['create_password'] && $data['send_verify_email']) {
            Mail::raw("حساب شما ایجاد شد. پسورد شما: {$data['password']}", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('خوش‌آمدگویی به اسپیرال');
            });
        }

        return [
            'user' => $user->load('personalInfo', 'verify', 'roles'),
            'accessToken' => $accessToken,
        ];
    }

    public function getAllUsers(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->adminUserRepository->getAllUsers();
    }

    // ... (بقیه متدها مثل updateUser, suspendUser, verifyUser بدون تغییر)
}
