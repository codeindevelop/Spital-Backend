<?php

namespace Modules\Settings\App\Repositories\Seo;

use Illuminate\Support\Facades\Auth;
use Modules\Settings\App\Models\Seo\SeoRepresentationSetting;
use Modules\User\App\Models\User;

class SeoRepresentationSettingRepository
{
    public function getSettings(): SeoRepresentationSetting
    {
        $user = Auth::user();
        $settings = SeoRepresentationSetting::firstOrFail();
        activity()
            ->performedOn($settings)
            ->causedBy($user)
            ->withProperties(['settings' => $settings])
            ->log('دریافت تنظیمات نمایندگی SEO از دیتابیس');
        return $settings;
    }

    public function updateSettings(array $data, string $userId): SeoRepresentationSetting
    {
        $settings = SeoRepresentationSetting::firstOrFail();
        $settings->update($data);
        activity()
            ->performedOn($settings)
            ->causedBy(User::find($userId))
            ->withProperties(['settings' => $settings->toArray()])
            ->log('به‌روزرسانی تنظیمات نمایندگی SEO');
        return $settings;
    }
}
