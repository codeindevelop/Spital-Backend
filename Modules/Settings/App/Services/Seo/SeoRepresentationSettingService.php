<?php

namespace Modules\Settings\App\Services\Seo;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Settings\App\Models\Eshop\Seo\SeoRepresentationSetting;
use Modules\Settings\App\Repositories\Eshop\Seo\SeoRepresentationSettingRepository;

class SeoRepresentationSettingService
{
    protected SeoRepresentationSettingRepository $repository;

    public function __construct(SeoRepresentationSettingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getSettings(): SeoRepresentationSetting
    {
        return $this->repository->getSettings();
    }

    public function updateSettings(array $data, string $userId): SeoRepresentationSetting
    {
        // مدیریت آپلود لوگو
        if (isset($data['company_logo']) && $data['company_logo'] instanceof UploadedFile) {
            // حذف همه فایل‌های sitelogo.* قبلی
            $this->deletePreviousLogos();

            $logoPath = $this->uploadLogo($data['company_logo']);
            $data['company_logo'] = $logoPath;
        } elseif (isset($data['company_logo']) && in_array($data['company_logo'], ['', 'null', 'empty'])) {
            // حذف همه فایل‌های sitelogo.* اگه درخواست حذف شده
            $this->deletePreviousLogos();
            $data['company_logo'] = null;
        }

        return $this->repository->updateSettings($data, $userId);
    }

    protected function uploadLogo(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension(); // گرفتن فرمت فایل (مثل png یا jpg)
        $filename = 'sitelogo.'.$extension; // نام فایل: sitelogo.png یا sitelogo.jpg
        $path = $file->storeAs('settings/seo/brand', $filename, 'public'); // ذخیره در مسیر مشخص
        return Storage::disk('public')->url($path); // برگرداندن URL عمومی
    }

    protected function deletePreviousLogos(): void
    {
        // پیدا کردن همه فایل‌های sitelogo.* توی پوشه
        $files = Storage::disk('public')->files('settings/seo/brand');
        foreach ($files as $file) {
            if (preg_match('/^settings\/seo\/brand\/sitelogo\.(png|jpg|jpeg)$/i', $file)) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
