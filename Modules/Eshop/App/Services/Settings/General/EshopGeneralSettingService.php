<?php

namespace Modules\Eshop\App\Services\Settings\General;


use Modules\Eshop\App\Models\Settings\General\EshopGeneralSetting;
use Modules\Eshop\App\Repositories\Settings\Product\EshopGeneralSettingRepository;

class EshopGeneralSettingService
{
    protected EshopGeneralSettingRepository $settingRepository;

    public function __construct(EshopGeneralSettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * Get the general settings.
     *
     * @return EshopGeneralSetting|null
     */
    public function getSettings(): ?EshopGeneralSetting
    {
        return $this->settingRepository->getFirst();
    }

    /**
     * Update or create the general settings.
     *
     * @param  array  $data
     * @return EshopGeneralSetting
     * @throws \Exception
     */
    public function updateSettings(array $data): EshopGeneralSetting
    {
        return $this->settingRepository->updateOrCreate($data);
    }
}
