<?php

namespace Modules\Settings\App\Repositories\Seo;

use Illuminate\Support\Facades\Auth;
use Modules\Settings\App\Models\Seo\SeoRepresentationSetting;
use Modules\User\App\Models\User;

class SeoRepresentationSettingRepository
{
    public function getSettings(): SeoRepresentationSetting
    {
        $user = Auth::guard('api')->user();
        $settings = SeoRepresentationSetting::firstOrCreate(
            [], // شرط خالی برای پیدا کردن اولین رکورد
            ['site_type' => 'personal'] // مقادیر پیش‌فرض اگه رکوردی ساخته شد
        );
        activity()
            ->performedOn($settings)
            ->causedBy($user)
            ->withProperties(['settings' => $settings->toArray()])
            ->log('دریافت تنظیمات نمایندگی SEO از دیتابیس');
        return $settings;
    }

    public function updateSettings(array $data, string $userId): SeoRepresentationSetting
    {
        $settings = SeoRepresentationSetting::firstOrCreate(
            [], // شرط خالی
            ['site_type' => 'personal'] // مقادیر پیش‌فرض
        );
        $settings->update($data);
        activity()
            ->performedOn($settings)
            ->causedBy(User::find($userId))
            ->withProperties(['settings' => $settings->toArray()])
            ->log('به‌روزرسانی تنظیمات نمایندگی SEO');
        return $settings;
    }
}
