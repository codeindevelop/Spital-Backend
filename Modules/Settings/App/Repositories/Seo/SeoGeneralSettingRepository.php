<?php

namespace Modules\Settings\App\Repositories\Seo;

use Modules\Settings\App\Models\Seo\SeoGeneralSetting;

class SeoGeneralSettingRepository
{
    public function getSettings(): SeoGeneralSetting
    {
        return SeoGeneralSetting::firstOrFail();
    }

    public function updateSettings(array $data): SeoGeneralSetting
    {
        $settings = SeoGeneralSetting::firstOrFail();
        $settings->update(array_filter([
            'site_name' => $data['site_name'] ?? $settings->site_name,
            'site_alternative_name' => $data['site_alternative_name'] ?? $settings->site_alternative_name,
            'site_slogan' => $data['site_slogan'] ?? $settings->site_slogan,
            'og_image' => $data['og_image'] ?? $settings->og_image,
            'title_separator' => $data['title_separator'] ?? $settings->title_separator,

        ]));
        return $settings;
    }
}
