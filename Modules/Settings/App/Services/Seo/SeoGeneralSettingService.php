<?php

namespace Modules\Settings\App\Services\Seo;


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
        return [
            'site_name' => $settings->site_name,
            'site_alternative_name' => $settings->site_alternative_name,
            'site_slogan' => $settings->site_slogan,
            'og_image' => $settings->og_image,
            'title_separator' => $settings->title_separator,
        ];
    }

    public function updateSettings(array $data): array
    {
        $settings = $this->repository->updateSettings($data);
        return [
            'site_name' => $settings->site_name,
            'site_alternative_name' => $settings->site_alternative_name,
            'site_slogan' => $settings->site_slogan,
            'og_image' => $settings->og_image,
            'title_separator' => $settings->title_separator,
            'message' => 'تنظیمات با موفقیت به‌روزرسانی شد.',
        ];
    }
}
