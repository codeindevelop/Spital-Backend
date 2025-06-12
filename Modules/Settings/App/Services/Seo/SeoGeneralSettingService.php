<?php

namespace Modules\Settings\App\Services\Seo;


use Illuminate\Support\Facades\Storage;
use Modules\Settings\App\Repositories\Seo\SeoGeneralSettingRepository;

class SeoGeneralSettingService
{
    protected SeoGeneralSettingRepository $repository;

    public function __construct(SeoGeneralSettingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getSettings(): array
    {
        $settings = $this->repository->getSettings();
        \Log::info('Settings fetched from repository', ['settings' => $settings->toArray()]);

        // ساخت URL عمومی برای og_image
        $ogImageUrl = $settings->og_image
            ? Storage::disk('public')->url('settings/seo/general/'.$settings->og_image)
            : null;

        return [
            'site_name' => $settings->site_name,
            'site_alternative_name' => $settings->site_alternative_name,
            'site_slogan' => $settings->site_slogan,
            'og_image' => $ogImageUrl,
            'title_separator' => $settings->title_separator,
        ];
    }

    public function updateSettings(array $data, ?string $userId = null): array
    {
        \Log::info('Updating settings in service', ['data' => $data, 'user_id' => $userId]);
        $settings = $this->repository->updateSettings($data, $userId);
        \Log::info('Settings updated in repository', ['settings' => $settings->toArray()]);

        // ساخت URL عمومی برای og_image
        $ogImageUrl = $settings->og_image
            ? Storage::disk('public')->url('settings/seo/general/'.$settings->og_image)
            : null;

        return [
            'site_name' => $settings->site_name,
            'site_alternative_name' => $settings->site_alternative_name,
            'site_slogan' => $settings->site_slogan,
            'og_image' => $ogImageUrl,
            'title_separator' => $settings->title_separator,
            'message' => 'تنظیمات با موفقیت به‌روزرسانی شد.',
        ];
    }
}
