<?php

namespace Modules\Settings\App\Services\Seo;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Settings\App\Models\Seo\SeoRepresentationSetting;
use Modules\Settings\App\Repositories\Seo\SeoRepresentationSettingRepository;

class SeoRepresentationSettingService
{
    protected SeoRepresentationSettingRepository $repository;

    public function __construct(SeoRepresentationSettingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getSettings(): \Modules\Settings\App\Models\Seo\SeoRepresentationSetting
    {
        return $this->repository->getSettings();
    }

    public function updateSettings(
        array $data,
        string $userId
    ): SeoRepresentationSetting {
        // مدیریت آپلود لوگو
        if (isset($data['company_logo']) && $data['company_logo'] instanceof UploadedFile) {
            $logoPath = $this->uploadLogo($data['company_logo']);
            $data['company_logo'] = $logoPath;
        } elseif (isset($data['company_logo']) && in_array($data['company_logo'], ['', 'null', 'empty'])) {
            $data['company_logo'] = null;
        }

        return $this->repository->updateSettings($data, $userId);
    }

    protected function uploadLogo(UploadedFile $file): string
    {
        $path = $file->store('company_logos', 'minio');
        return Storage::disk('minio')->url($path);
    }
}
